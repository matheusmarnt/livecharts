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

document.addEventListener('alpine:init', () => {
    Alpine.data('livecharts', (config) => ({
        id: config.id,
        instance: null,
        options: config.options,
        engineCtor: config.engineCtor,
        payload: config.payload,

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
            if (this.engineCtor === 'ApexCharts') {
                const apexOptions = {
                    ...this.options,
                    chart: {
                        ...this.options.chart,
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
                    ...this.options,
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
                    this.instance.data.datasets = newPayload.datasets.map(d => ({
                        label: d.name,
                        data: d.data,
                        backgroundColor: d.color,
                        borderColor: d.color
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
