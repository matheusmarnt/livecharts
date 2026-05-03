// @ts-check
import { defineConfig } from 'astro/config';
import starlight from '@astrojs/starlight';

// https://astro.build/config
export default defineConfig({
	site: 'https://matheusmarnt.github.io',
	base: '/livecharts',
	integrations: [
		starlight({
			title: 'LiveCharts',
			description: 'A unified, reactive chart abstraction layer for Laravel Livewire. Multi-engine. Pure PHP. Zero JavaScript required.',
			logo: {
				src: './src/assets/livecharts-nobg.png',
				replacesTitle: true,
			},
			favicon: '/favicon.svg',
			customCss: ['./src/styles/custom.css'],
			head: [
				{
					tag: 'meta',
					attrs: {
						property: 'og:image',
						content: 'https://matheusmarnt.github.io/livecharts/og-card.png',
					},
				},
				{
					tag: 'meta',
					attrs: {
						name: 'twitter:card',
						content: 'summary_large_image',
					},
				},
				{
					tag: 'meta',
					attrs: {
						name: 'twitter:image',
						content: 'https://matheusmarnt.github.io/livecharts/og-card.png',
					},
				},
			],
			editLink: {
				baseUrl: 'https://github.com/matheusmarnt/livecharts/edit/main/docs-site/',
			},
			lastUpdated: true,
			pagination: true,
			social: [
				{
					icon: 'github',
					label: 'GitHub',
					href: 'https://github.com/matheusmarnt/livecharts',
				},
			],
			sidebar: [
				{
					label: 'Getting Started',
					items: [
						{ label: 'Introduction', slug: 'index' },
						{ label: 'Installation', slug: 'getting-started/installation' },
						{ label: 'Quick Start', slug: 'getting-started/quick-start' },
						{ label: 'Configuration', slug: 'getting-started/configuration' },
					],
				},
				{
					label: 'Usage',
					items: [
						{ label: 'Basic Usage', slug: 'usage/basic-usage' },
						{ label: 'Class-Based Charts', slug: 'usage/class-based' },
						{ label: 'Reactivity & Polling', slug: 'usage/reactivity' },
						{ label: 'Interactive Events', slug: 'usage/events' },
						{ label: 'Theming', slug: 'usage/theming' },
						{ label: 'Asset Management', slug: 'usage/asset-management' },
						{ label: 'Artisan Generator', slug: 'usage/artisan-generator' },
					],
				},
				{
					label: 'Engines',
					items: [
						{ label: 'Overview', slug: 'engines/overview' },
						{ label: 'ApexCharts', slug: 'engines/apexcharts' },
						{ label: 'Chart.js', slug: 'engines/chartjs' },
						{ label: 'Custom Engines', slug: 'engines/custom' },
					],
				},
				{
					label: 'Reference',
					items: [
						{ label: 'Chart API', slug: 'reference/chart-api' },
						{ label: 'Dataset API', slug: 'reference/dataset-api' },
						{ label: 'Artisan Commands', slug: 'reference/commands' },
						{ label: 'Configuration File', slug: 'reference/configuration' },
						{ label: 'Exceptions', slug: 'reference/exceptions' },
					],
				},
				{
					label: 'Advanced',
					items: [
						{ label: 'Drill-Down Pattern', slug: 'advanced/drill-down' },
						{ label: 'Multi-Tenant', slug: 'advanced/multi-tenant' },
						{ label: 'Broadcasting', slug: 'advanced/broadcasting' },
					],
				},
			],
		}),
	],
});
