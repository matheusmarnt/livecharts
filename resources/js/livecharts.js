document.addEventListener('alpine:init', () => {
    Alpine.data('livecharts', (config) => ({
        id: config.id,
        instance: null,
        options: config.options,
        constructor: config.constructor,
        payload: config.payload,

        init() {
            this.render();

            this.$watch('payload', (value) => {
                this.update(value);
            });
        },

        render() {
            if (this.constructor === 'ApexCharts') {
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
                            }
                        }
                    }
                };
                this.instance = new ApexCharts(this.$refs.chart, apexOptions);
            } else if (this.constructor === 'Chart') {
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
                if (this.constructor === 'ApexCharts') {
                    this.instance.updateSeries(newPayload.datasets.map(d => ({
                        name: d.name,
                        data: d.data
                    })));
                } 
                // Soft update for Chart.js
                else if (this.constructor === 'Chart') {
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

        destroy() {
            if (this.instance && this.instance.destroy) {
                this.instance.destroy();
            }
        }
    }));
});
