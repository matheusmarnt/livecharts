// @ts-check
import { defineConfig } from 'astro/config';
import starlight from '@astrojs/starlight';

// https://astro.build/config
export default defineConfig({
	integrations: [
		starlight({
			title: 'LiveCharts',
			social: [{ icon: 'github', label: 'GitHub', href: 'https://github.com/matheusmarnt/livecharts' }],
			sidebar: [
				{
					label: 'Getting Started',
					items: [
						{ label: 'Introduction', slug: 'index' },
						{ label: 'Installation', slug: 'getting-started/installation' },
					],
				},
				{
					label: 'Usage',
					items: [
						{ label: 'Basic Usage', slug: 'usage/basic-usage' },
						{ label: 'Artisan Generator', slug: 'usage/artisan-generator' },
						{ label: 'Interactive Events', slug: 'usage/events' },
					],
				},
				{
					label: 'Engines',
					items: [
						{ label: 'ApexCharts', slug: 'engines/apexcharts' },
						{ label: 'Chart.js', slug: 'engines/chartjs' },
					],
				},
			],
		}),
	],
});
