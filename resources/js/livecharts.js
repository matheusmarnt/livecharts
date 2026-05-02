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
                this.instance = new ApexCharts(this.$refs.chart, this.options);
            } else if (this.constructor === 'Chart') {
                this.instance = new Chart(this.$refs.chart, this.options);
            }

            if (this.instance) {
                this.instance.render ? this.instance.render() : null;
            }
        },

        update(newPayload) {
            // Basic logic: if data only change, soft update. Else hard reset.
            // For now, simple re-render for POC.
            if (this.instance && this.instance.destroy) {
                this.instance.destroy();
            }
            
            // Re-resolve options from payload logic would go here if done in JS
            // But since Livewire re-renders the whole x-data, Alpine will re-init.
            // This update() is a placeholder for future reactive optimization.
        },

        destroy() {
            if (this.instance && this.instance.destroy) {
                this.instance.destroy();
            }
        }
    }));
});
