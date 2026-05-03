// Defense-in-depth Livewire integration hooks.
// `wire:ignore` on the container already skips morphing; the explicit
// `morph.updating` hook is a safety net when consumers strip wire:ignore.
// `commit.applied` re-syncs the chart from the latest server payload via the
// soft-update path on the Alpine component, avoiding a full destroy+render.
document.addEventListener('livewire:init', () => {
    if (typeof Livewire === 'undefined') {
        return;
    }

    Livewire.hook('morph.updating', ({ el, skip }) => {
        if (el && el.classList && el.classList.contains('livecharts-container')) {
            skip();
        }
    });

    Livewire.hook('commit.applied', ({ component }) => {
        const wire = component && component.$wire;
        if (!wire || typeof wire.id === 'undefined') {
            return;
        }

        const el = document.getElementById(wire.id);
        if (!el || !el._x_dataStack || !el._x_dataStack[0]) {
            return;
        }

        const alpineData = el._x_dataStack[0];
        const nextPayload = wire.payload;
        if (nextPayload && typeof alpineData.update === 'function') {
            alpineData.update(nextPayload);
        }
    });
});

// ─── Theme observer singleton ──────────────────────────────────────────────
// Reads `window.LiveChartsConfig.themeStrategy` ('class' or 'media').
// 'class' watches <html class="dark"> via MutationObserver.
// 'media' watches prefers-color-scheme.
// Emits to all subscribed chart instances on every theme change.
const themeWatcher = (() => {
    const subs = new Set();
    let mode = detect();

    function strategy() {
        return (window.LiveChartsConfig && window.LiveChartsConfig.themeStrategy) || 'class';
    }

    function detect() {
        if (strategy() === 'media') {
            return matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    }

    function notify() {
        const next = detect();
        if (next !== mode) {
            mode = next;
            subs.forEach(fn => fn(mode));
        }
    }

    new MutationObserver(notify).observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });

    matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (strategy() === 'media') notify();
    });

    return {
        current: () => mode,
        subscribe: (fn) => {
            subs.add(fn);
            return () => subs.delete(fn);
        },
    };
})();

// ─── Sidecar helpers ────────────────────────────────────────────────────────

/**
 * Walk an options object, collect every __lc_themed entry, strip them, and
 * return a map of { dotPath → { dark, light } } for the theme observer to use.
 *
 * @param  {object}  obj   - mutable options object (stripped in place)
 * @param  {string}  path  - current dot-path prefix (internal use)
 * @param  {object}  map   - result map (internal use)
 * @returns {object}  map
 */
function collectThemed(obj, path, map) {
    path = path || '';
    map  = map  || {};

    if (!obj || typeof obj !== 'object') return map;

    if (Array.isArray(obj)) {
        obj.forEach((item, i) => collectThemed(item, path + '[' + i + ']', map));
        return map;
    }

    if (obj.__lc_themed) {
        const themed = obj.__lc_themed;
        Object.keys(themed).forEach(key => {
            map[path ? path + '.' + key : key] = themed[key];
        });
        delete obj.__lc_themed;
    }

    Object.keys(obj).forEach(key => {
        if (key !== '__lc_themed') {
            collectThemed(obj[key], path ? path + '.' + key : key, map);
        }
    });

    return map;
}

/**
 * Apply a theme to a Chart.js options object given the themed map.
 * Calls chart.update('none') after patching.
 */
function applyThemeChartJs(instance, themedMap, mode) {
    Object.keys(themedMap).forEach(dotPath => {
        const value = themedMap[dotPath][mode];
        if (value === undefined) return;

        const parts = dotPath.replace(/\[(\d+)\]/g, '.$1').split('.');
        let obj = instance.options;
        for (let i = 0; i < parts.length - 1; i++) {
            if (obj[parts[i]] === undefined) obj[parts[i]] = {};
            obj = obj[parts[i]];
        }
        obj[parts[parts.length - 1]] = value;
    });

    instance.update('none');
}

/**
 * Build a partial ApexCharts updateOptions payload from the themed map.
 */
function buildApexUpdate(themedMap, mode) {
    const update = {};

    Object.keys(themedMap).forEach(dotPath => {
        const value = themedMap[dotPath][mode];
        if (value === undefined) return;

        const parts = dotPath.replace(/\[(\d+)\]/g, '.$1').split('.');
        let obj = update;
        for (let i = 0; i < parts.length - 1; i++) {
            if (obj[parts[i]] === undefined) obj[parts[i]] = {};
            obj = obj[parts[i]];
        }
        obj[parts[parts.length - 1]] = value;
    });

    return update;
}

document.addEventListener('alpine:init', () => {
    Alpine.data('livecharts', (config) => ({
        id: config.id,
        instance: null,
        options: config.options,
        engineCtor: config.engineCtor,
        payload: config.payload,
        _themedMap: {},
        _themeUnsub: null,

        init() {
            this.render();

            this.$watch('payload', (value) => {
                this.update(value);
            });

            // Register global event for manual updates from JS
            window.addEventListener(`livecharts:update:${this.id}`, (event) => {
                if (event.detail.options) {
                    this.options = event.detail.options;
                }
                this.update(event.detail.payload || this.payload);
            });

            // Echo Integration
            if (window.Echo && this.payload.broadcastOn && this.payload.broadcastAs) {
                window.Echo.channel(this.payload.broadcastOn)
                    .listen(this.payload.broadcastAs, (event) => {
                        if (event.payload) {
                            this.update(event.payload);
                        }
                    });
            }
        },

        render() {
            // Deep-clone options so we can strip __lc_themed without mutating
            // the original config object (important for Livewire re-renders).
            const opts = JSON.parse(JSON.stringify(this.options));
            this._themedMap = collectThemed(opts, '', {});

            // Apply initial theme before engine init
            const currentMode = themeWatcher.current();
            applyInitialTheme(opts, this._themedMap, currentMode);

            if (this.engineCtor === 'ApexCharts') {
                const apexOptions = {
                    ...opts,
                    chart: {
                        ...opts.chart,
                        events: {
                            dataPointSelection: (event, chartContext, config) => {
                                if (this.payload.onDataPointClick) {
                                    this.$wire.dispatch(this.payload.onDataPointClick, {
                                        seriesIndex: config.seriesIndex,
                                        dataPointIndex: config.dataPointIndex,
                                        value: config.w.globals.series[config.seriesIndex][config.dataPointIndex],
                                        label: config.w.globals.labels[config.dataPointIndex]
                                    });
                                }
                            },
                            zoomed: (chartContext, { xaxis, yaxis }) => {
                                if (this.payload.onZoom) {
                                    this.$wire.dispatch(this.payload.onZoom, { xaxis, yaxis });
                                }
                            },
                            selection: (chartContext, { xaxis, yaxis }) => {
                                if (this.payload.onSelection) {
                                    this.$wire.dispatch(this.payload.onSelection, { xaxis, yaxis });
                                }
                            },
                            scrolled: (chartContext, { xaxis, yaxis }) => {
                                if (this.payload.onScroll) {
                                    this.$wire.dispatch(this.payload.onScroll, { xaxis, yaxis });
                                }
                            }
                        }
                    }
                };
                this.instance = new ApexCharts(this.$refs.chart, apexOptions);
            } else if (this.engineCtor === 'Chart') {
                const chartjsOptions = {
                    ...opts,
                    onClick: (event, elements) => {
                        if (elements.length > 0 && this.payload.onDataPointClick) {
                            const element = elements[0];
                            const datasetIndex = element.datasetIndex;
                            const index = element.index;

                            this.$wire.dispatch(this.payload.onDataPointClick, {
                                datasetIndex: datasetIndex,
                                index: index,
                                value: this.instance.data.datasets[datasetIndex].data[index],
                                label: this.instance.data.labels[index]
                            });
                        }
                    }
                };
                this.instance = new Chart(this.$refs.chart, chartjsOptions);
            }

            if (this.instance) {
                this.instance.render ? this.instance.render() : null;

                // Expose instance globally for direct JS access
                window.LiveCharts = window.LiveCharts || {};
                window.LiveCharts[this.id] = this.instance;
            }

            // Subscribe to theme changes
            const self = this;
            this._themeUnsub = themeWatcher.subscribe(function(newMode) {
                self.applyTheme(newMode);
            });
        },

        applyTheme(mode) {
            if (!this.instance || Object.keys(this._themedMap).length === 0) return;

            if (this.engineCtor === 'ApexCharts') {
                const update = buildApexUpdate(this._themedMap, mode);
                if (Object.keys(update).length > 0) {
                    this.instance.updateOptions(update, false, true);
                }
            } else if (this.engineCtor === 'Chart') {
                applyThemeChartJs(this.instance, this._themedMap, mode);
            }
        },

        update(newPayload) {
            if (!this.instance) {
                this.render();
                return;
            }

            // Check if structural changes occurred
            const structural = JSON.stringify(this.payload.type) !== JSON.stringify(newPayload.type) ||
                             JSON.stringify(this.payload.labels) !== JSON.stringify(newPayload.labels) ||
                             JSON.stringify(this.payload.options) !== JSON.stringify(newPayload.options);

            if (structural) {
                this.destroy();
                this.options = newPayload.options;
                this.payload = newPayload;
                this.render();
            } else {
                // Soft update for ApexCharts
                if (this.engineCtor === 'ApexCharts') {
                    this.instance.updateSeries(newPayload.datasets.map(d => ({
                        name: d.name,
                        data: d.data
                    })));
                }
                // Soft update for Chart.js
                else if (this.engineCtor === 'Chart') {
                    const mode = themeWatcher.current();
                    this.instance.data.datasets = newPayload.datasets.map(d => ({
                        label: d.name,
                        data: d.data,
                        backgroundColor: d.background ? d.background[mode] : (d.background || null),
                        borderColor: d.border ? d.border[mode] : (d.border || null),
                    }));
                    this.instance.update();
                }

                this.payload = newPayload;
            }
        },

        // Alpine.data auto-invokes destroy() when the element is torn down
        // (Alpine 3.x destroyTree). Drops the engine instance and the global
        // registry entry so the chart can be garbage collected.
        destroy() {
            if (this._themeUnsub) {
                this._themeUnsub();
                this._themeUnsub = null;
            }

            if (this.instance && typeof this.instance.destroy === 'function') {
                this.instance.destroy();
            }

            this.instance = null;

            if (window.LiveCharts && window.LiveCharts[this.id]) {
                delete window.LiveCharts[this.id];
            }
        }
    }));
});

/**
 * Apply initial theme colors directly into options before engine init.
 * Avoids a visible flash on first render.
 */
function applyInitialTheme(opts, themedMap, mode) {
    Object.keys(themedMap).forEach(dotPath => {
        const value = themedMap[dotPath][mode];
        if (value === undefined) return;

        const parts = dotPath.replace(/\[(\d+)\]/g, '.$1').split('.');
        let obj = opts;
        for (let i = 0; i < parts.length - 1; i++) {
            if (obj && obj[parts[i]] !== undefined) {
                obj = obj[parts[i]];
            } else {
                return;
            }
        }
        if (obj) obj[parts[parts.length - 1]] = value;
    });
}
