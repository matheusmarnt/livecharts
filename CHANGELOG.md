# Changelog

## [2.7.8](https://github.com/matheusmarnt/livecharts/compare/v2.7.7...v2.7.8) (2026-06-01)


### Miscellaneous Chores

* **deps:** bump actions/upload-pages-artifact from 3 to 5 ([#104](https://github.com/matheusmarnt/livecharts/issues/104)) ([cca670a](https://github.com/matheusmarnt/livecharts/commit/cca670a2d4502ed4ef7dec1ac973d78d78e48085))

## [2.7.7](https://github.com/matheusmarnt/livecharts/compare/v2.7.6...v2.7.7) (2026-05-29)


### Bug Fixes

* **livewire:** support wire:navigate via [@assets](https://github.com/assets) asset strategy ([#102](https://github.com/matheusmarnt/livecharts/issues/102)) ([868e6cd](https://github.com/matheusmarnt/livecharts/commit/868e6cd58f21601f5f3def0767d1256b8cfe7bd0))

## [2.7.6](https://github.com/matheusmarnt/livecharts/compare/v2.7.5...v2.7.6) (2026-05-29)


### Miscellaneous Chores

* **deps:** bump actions/setup-node from 4 to 6 ([#96](https://github.com/matheusmarnt/livecharts/issues/96)) ([3fcdaab](https://github.com/matheusmarnt/livecharts/commit/3fcdaabe241b9c994f9ceeb6e2bb06037094a4a8))

## [2.7.5](https://github.com/matheusmarnt/livecharts/compare/v2.7.4...v2.7.5) (2026-05-29)


### Miscellaneous Chores

* **deps:** bump actions/checkout from 4 to 6 ([#97](https://github.com/matheusmarnt/livecharts/issues/97)) ([7fa136c](https://github.com/matheusmarnt/livecharts/commit/7fa136c29c5e11ac7e07af6a571bafa4973dd89f))
* **deps:** bump actions/configure-pages from 5 to 6 ([#95](https://github.com/matheusmarnt/livecharts/issues/95)) ([221f4b9](https://github.com/matheusmarnt/livecharts/commit/221f4b9c4fa2029693ff70dbe8eeec8c2fc720fd))
* **deps:** bump actions/deploy-pages from 4 to 5 ([#94](https://github.com/matheusmarnt/livecharts/issues/94)) ([a3467f9](https://github.com/matheusmarnt/livecharts/commit/a3467f9ab27066accfe917ae04d9343bcd003a9f))
* **deps:** bump googleapis/release-please-action from 4 to 5 ([#98](https://github.com/matheusmarnt/livecharts/issues/98)) ([39a0fc8](https://github.com/matheusmarnt/livecharts/commit/39a0fc88aa0865691ba345c9e9487faf1e325d5f))

## [2.7.4](https://github.com/matheusmarnt/livecharts/compare/v2.7.3...v2.7.4) (2026-05-04)


### Bug Fixes

* **i18n:** rename lang dir pt-BR → pt_BR to match Laravel locale convention ([#91](https://github.com/matheusmarnt/livecharts/issues/91)) ([74166de](https://github.com/matheusmarnt/livecharts/commit/74166deb9c21395697f2652fa3b5da12a5a6f42c))

## [2.7.3](https://github.com/matheusmarnt/livecharts/compare/v2.7.2...v2.7.3) (2026-05-04)


### Bug Fixes

* **i18n:** bypass callAfterResolving for translation namespace registration ([#89](https://github.com/matheusmarnt/livecharts/issues/89)) ([13d39ac](https://github.com/matheusmarnt/livecharts/commit/13d39ac3e5437968914276f6b574c54662e87191))

## [2.7.2](https://github.com/matheusmarnt/livecharts/compare/v2.7.1...v2.7.2) (2026-05-04)


### Bug Fixes

* **install:** publish vendor JS dist assets and fix translation loading ([#86](https://github.com/matheusmarnt/livecharts/issues/86)) ([153cadc](https://github.com/matheusmarnt/livecharts/commit/153cadc4eaa9772d1ae5a5cbe6e67ae05f17841d))

## [2.7.1](https://github.com/matheusmarnt/livecharts/compare/v2.7.0...v2.7.1) (2026-05-03)


### Bug Fixes

* **types:** wrap array_map with array_values to satisfy list&lt;ColorValue&gt; type ([#83](https://github.com/matheusmarnt/livecharts/issues/83)) ([3c13b0e](https://github.com/matheusmarnt/livecharts/commit/3c13b0e290e4c02fcc1120a969c2ce4ae4235fce))

## [2.7.0](https://github.com/matheusmarnt/livecharts/compare/v2.6.0...v2.7.0) (2026-05-03)


### Features

* **install:** apply Laravel Prompts visual pattern to livecharts:install ([a773f23](https://github.com/matheusmarnt/livecharts/commit/a773f23463082865a9e64060175aba857d77134e))


### Bug Fixes

* ColorValue roundtrip, sidecar dot-paths, tooltip CSS, CDN URL, install Prompts UI ([f688443](https://github.com/matheusmarnt/livecharts/commit/f688443164b853008e10554f2f5bb0d51b7ab209))
* **rendering:** ColorValue roundtrip, sidecar dot-paths, tooltip CSS injection, CDN URL ([f469e8d](https://github.com/matheusmarnt/livecharts/commit/f469e8d484f2f098061cc1fb6fec9f8ecfa3e017))

## [2.6.0](https://github.com/matheusmarnt/livecharts/compare/v2.5.2...v2.6.0) (2026-05-03)


### Features

* **colors:** theme-aware Tailwind color tokens with live dark-mode toggle ([#77](https://github.com/matheusmarnt/livecharts/issues/77)) ([0343b50](https://github.com/matheusmarnt/livecharts/commit/0343b50da2d4a0c773a6dc036edbf2eb39fabd23))

## [2.5.2](https://github.com/matheusmarnt/livecharts/compare/v2.5.1...v2.5.2) (2026-05-03)


### Bug Fixes

* **scripts:** use Blade push/stack so [@live](https://github.com/live)ChartsScripts works in layout &lt;head&gt; ([#74](https://github.com/matheusmarnt/livecharts/issues/74)) ([8ce1c60](https://github.com/matheusmarnt/livecharts/commit/8ce1c602e49c99b37d08af5fa9d3d2174eb42e66))

## [2.5.1](https://github.com/matheusmarnt/livecharts/compare/v2.5.0...v2.5.1) (2026-05-03)


### Bug Fixes

* **adapters:** omit empty optional fields to prevent ApexCharts array/object type errors ([#72](https://github.com/matheusmarnt/livecharts/issues/72)) ([8e256df](https://github.com/matheusmarnt/livecharts/commit/8e256df993fc89d0e567d76584b7986934982746))

## [2.5.0](https://github.com/matheusmarnt/livecharts/compare/v2.4.1...v2.5.0) (2026-05-03)


### Features

* **engine:** add availableEnginesForType to expose multi-engine support ([#70](https://github.com/matheusmarnt/livecharts/issues/70)) ([5bbec7f](https://github.com/matheusmarnt/livecharts/commit/5bbec7fc3ab4f3c3fe12e8655b5faf22cb36f5a8))

## [2.4.1](https://github.com/matheusmarnt/livecharts/compare/v2.4.0...v2.4.1) (2026-05-03)


### Bug Fixes

* **views:** resolve bootstrap script path outside Blade-compiled view ([#67](https://github.com/matheusmarnt/livecharts/issues/67)) ([0e578bb](https://github.com/matheusmarnt/livecharts/commit/0e578bb3d2de3aa6d6ee444150823440aa542757))

## [2.4.0](https://github.com/matheusmarnt/livecharts/compare/v2.3.0...v2.4.0) (2026-05-03)


### Features

* **commands:** livecharts:preview launches the browser ([#61](https://github.com/matheusmarnt/livecharts/issues/61)) ([68cf064](https://github.com/matheusmarnt/livecharts/commit/68cf0648c70911f32056bff86e052a207cebdcc0))

## [2.3.0](https://github.com/matheusmarnt/livecharts/compare/v2.2.0...v2.3.0) (2026-05-03)


### Features

* **assets:** bundle chart.js plugins for local-first delivery ([#58](https://github.com/matheusmarnt/livecharts/issues/58)) ([85b639c](https://github.com/matheusmarnt/livecharts/commit/85b639cfe1f08bc6703e5ff78ec82a9b137c3f0b))

## [2.2.0](https://github.com/matheusmarnt/livecharts/compare/v2.1.0...v2.2.0) (2026-05-03)


### Features

* **assets:** bundle apexcharts and chart.js with local-first fallback ([#56](https://github.com/matheusmarnt/livecharts/issues/56)) ([7b7c90f](https://github.com/matheusmarnt/livecharts/commit/7b7c90f5a32cbbe7dfcad328f85f7565e88ab36e))

## [2.1.0](https://github.com/matheusmarnt/livecharts/compare/v2.0.0...v2.1.0) (2026-05-03)


### Features

* **assets:** add Vite-based JS build pipeline ([#54](https://github.com/matheusmarnt/livecharts/issues/54)) ([eecd214](https://github.com/matheusmarnt/livecharts/commit/eecd214b7c4896f2d42e12413b7b0942db29a753))

## [2.0.0](https://github.com/matheusmarnt/livecharts/compare/v1.19.0...v2.0.0) (2026-05-02)


### ⚠ BREAKING CHANGES

* drop EngineFactory static state in favor of container singleton ([#48](https://github.com/matheusmarnt/livecharts/issues/48))

### Code Refactoring

* drop EngineFactory static state in favor of container singleton ([#48](https://github.com/matheusmarnt/livecharts/issues/48)) ([f3a94e0](https://github.com/matheusmarnt/livecharts/commit/f3a94e09da813cb00806fdd115db73dba2424339))

## [1.19.0](https://github.com/matheusmarnt/livecharts/compare/v1.18.1...v1.19.0) (2026-05-02)


### Features

* **install:** publish chart class stubs interactively ([#46](https://github.com/matheusmarnt/livecharts/issues/46)) ([b9f5598](https://github.com/matheusmarnt/livecharts/commit/b9f5598c312c8f00416cc6fee9f0b2d28d199a45))

## [1.18.1](https://github.com/matheusmarnt/livecharts/compare/v1.18.0...v1.18.1) (2026-05-02)


### Bug Fixes

* **alpine:** wire morph.updating + commit.applied Livewire hooks ([#44](https://github.com/matheusmarnt/livecharts/issues/44)) ([d5f789d](https://github.com/matheusmarnt/livecharts/commit/d5f789d48815e6613a6efb5b3b6df59179435338))

## [1.18.0](https://github.com/matheusmarnt/livecharts/compare/v1.17.0...v1.18.0) (2026-05-02)


### Features

* **polling:** wire Chart::poll() and LiveChartsComponent::refresh() ([#42](https://github.com/matheusmarnt/livecharts/issues/42)) ([7d0a88a](https://github.com/matheusmarnt/livecharts/commit/7d0a88a8fb42ca60da9d85882f8b468bfc3cfff2))

## [1.17.0](https://github.com/matheusmarnt/livecharts/compare/v1.16.1...v1.17.0) (2026-05-02)


### Features

* **facade:** expose LiveCharts::registerEngine() per PRD §7.3.2 ([#40](https://github.com/matheusmarnt/livecharts/issues/40)) ([0241973](https://github.com/matheusmarnt/livecharts/commit/02419735482d134722c8dd6d7f33206f90135d23))

## [1.16.1](https://github.com/matheusmarnt/livecharts/compare/v1.16.0...v1.16.1) (2026-05-02)


### Miscellaneous Chores

* **phpstan:** raise level from 5 to 8 ([#37](https://github.com/matheusmarnt/livecharts/issues/37)) ([6f3ad13](https://github.com/matheusmarnt/livecharts/commit/6f3ad136c6d7abea6b6135db61b5d337365abc8e))

## [1.16.0](https://github.com/matheusmarnt/livecharts/compare/v1.15.0...v1.16.0) (2026-05-02)


### Features

* **i18n:** add en, pt-BR, and es translations ([#34](https://github.com/matheusmarnt/livecharts/issues/34)) ([c23e272](https://github.com/matheusmarnt/livecharts/commit/c23e272f4a8bb295c9a40f9e2be41c409ca3379f))


### Bug Fixes

* **livewire:** bind id/class/x-data directly without \$attributes ([#36](https://github.com/matheusmarnt/livecharts/issues/36)) ([0fb234c](https://github.com/matheusmarnt/livecharts/commit/0fb234ce9fafbcf1ad66b9b6af4e279742c054fc))

## [1.15.0](https://github.com/matheusmarnt/livecharts/compare/v1.14.0...v1.15.0) (2026-05-02)


### Features

* **exceptions:** add InvalidChartType, EmptyDataset, DataShapeMismatch ([#33](https://github.com/matheusmarnt/livecharts/issues/33)) ([a7c3ae2](https://github.com/matheusmarnt/livecharts/commit/a7c3ae2daf501c1a2cc92249db91999649f9f8d5))


### Bug Fixes

* resolve persistent PHPStan view-string error in ServiceProvider ([daf5788](https://github.com/matheusmarnt/livecharts/commit/daf578870171c7cc8d689b20dbd25e34ae122d7e))
* resolve PHPStan view-string error and improve test coverage for CI ([c2d7e64](https://github.com/matheusmarnt/livecharts/commit/c2d7e64c26c9d818d22bf5ae88fcf89de1ae3aee))

## [1.14.0](https://github.com/matheusmarnt/livecharts/compare/v1.13.0...v1.14.0) (2026-05-02)


### Features

* setup Astro Starlight documentation site ([5217dbd](https://github.com/matheusmarnt/livecharts/commit/5217dbd306c71899bc8e1c44a3622e36615a8c6a))
* setup documentation site ([3bcb5d2](https://github.com/matheusmarnt/livecharts/commit/3bcb5d24c9da1b91adb45847559b421aa9cc9885))

## [1.13.0](https://github.com/matheusmarnt/livecharts/compare/v1.12.0...v1.13.0) (2026-05-02)


### Features

* implement specialized configuration helpers (xaxis, yaxis, grid, etc) ([83dc14b](https://github.com/matheusmarnt/livecharts/commit/83dc14be2e33d0d488a26cf51fb68c7860ca5bf8))
* specialized configuration helpers ([4e8c897](https://github.com/matheusmarnt/livecharts/commit/4e8c89773147ac6ac8706cb4250219508bae761f))

## [1.12.0](https://github.com/matheusmarnt/livecharts/compare/v1.11.0...v1.12.0) (2026-05-02)


### Features

* implement mixed chart types and fix dataset object mapping ([db53751](https://github.com/matheusmarnt/livecharts/commit/db53751fba3abd2cf89dc4c349d936350f9e7646))
* implement mixed charts support ([5fe0396](https://github.com/matheusmarnt/livecharts/commit/5fe039624d069afa63450337d54a7aa5ce8b459d))

## [1.11.0](https://github.com/matheusmarnt/livecharts/compare/v1.10.0...v1.11.0) (2026-05-02)


### Features

* implement livecharts:preview command and gallery view ([5be3d34](https://github.com/matheusmarnt/livecharts/commit/5be3d345009e94e53919beccb633041a8d63b5da))
* implement preview command ([06eaf81](https://github.com/matheusmarnt/livecharts/commit/06eaf8154ceb1c08ea3564504346e0bf8bebc1f2))

## [1.10.0](https://github.com/matheusmarnt/livecharts/compare/v1.9.0...v1.10.0) (2026-05-02)


### Features

* add logo to README.md ([950da56](https://github.com/matheusmarnt/livecharts/commit/950da560f8092e0b759ab94eb3b2fb5f1428cda7))
* add logo to README.md ([a6172e7](https://github.com/matheusmarnt/livecharts/commit/a6172e744339a99ff80a39684d4a360a5308f11c))

## [1.9.0](https://github.com/matheusmarnt/livecharts/compare/v1.8.0...v1.9.0) (2026-05-02)


### Features

* asset resilience (Local + CDN fallback) ([f422044](https://github.com/matheusmarnt/livecharts/commit/f4220445b2643b40a12953d30e3bdd704395fd56))
* implement asset resilience with local dependencies and CDN fallback ([5f1dcaa](https://github.com/matheusmarnt/livecharts/commit/5f1dcaad435f49622582462ce2ecd74eb589b52e))

## [1.8.0](https://github.com/matheusmarnt/livecharts/compare/v1.7.0...v1.8.0) (2026-05-02)


### Features

* Chart.js plugins support ([f175bc9](https://github.com/matheusmarnt/livecharts/commit/f175bc974df7e3362f5e1f9b3d5dcdd91696d499))
* implement Chart.js plugins support (Treemap, Matrix, Sankey, Financial) ([345ed67](https://github.com/matheusmarnt/livecharts/commit/345ed67079becedd215b5007582004662e362e4c))

## [1.7.0](https://github.com/matheusmarnt/livecharts/compare/v1.6.0...v1.7.0) (2026-05-02)


### Features

* implement WebSocket integration ([de216b5](https://github.com/matheusmarnt/livecharts/commit/de216b55092e5f0a7b54bc4843fe650b28de3183))
* implement WebSocket integration via Laravel Echo ([d1a2a73](https://github.com/matheusmarnt/livecharts/commit/d1a2a7338c8f1883e0680ce6f962d32a739c54a0))

## [1.6.0](https://github.com/matheusmarnt/livecharts/compare/v1.5.0...v1.6.0) (2026-05-02)


### Features

* implement deep integration (Livewire, Alpine, JS, Tailwind) ([6afbc33](https://github.com/matheusmarnt/livecharts/commit/6afbc33050ff26f0b7529dcedfc65ee662c6967c))
* implement full integration with Livewire attributes, Alpine.js, and JS global access ([18bf823](https://github.com/matheusmarnt/livecharts/commit/18bf82361e61fd7de6b0e5baad12b22b7b18db5c))

## [1.5.0](https://github.com/matheusmarnt/livecharts/compare/v1.4.1...v1.5.0) (2026-05-02)


### Features

* implement interaction events ([fe486a8](https://github.com/matheusmarnt/livecharts/commit/fe486a84268b8ae8d340836125ca189af9a710f4))
* implement interaction events (onZoom, onSelection, onScroll) ([5185404](https://github.com/matheusmarnt/livecharts/commit/5185404f699d7b62f4f6de87fd4d9c1b4fb82917))

## [1.4.1](https://github.com/matheusmarnt/livecharts/compare/v1.4.0...v1.4.1) (2026-05-02)


### Miscellaneous Chores

* add workflow_dispatch to release-please for recovery ([698c2d8](https://github.com/matheusmarnt/livecharts/commit/698c2d819669c2c892a9a746be027d09b453278c))

## [1.4.0](https://github.com/matheusmarnt/livecharts/compare/v1.3.0...v1.4.0) (2026-05-02)


### Features

* update engine versions ([fe36ba8](https://github.com/matheusmarnt/livecharts/commit/fe36ba88458ceac6fc717d6ff46f80559d1f796f))
* update engine versions to ApexCharts v5.10.6 and Chart.js v4.5.1 ([f46af0b](https://github.com/matheusmarnt/livecharts/commit/f46af0b2d35b667da990276af674e872bf555c78))

## [1.3.0](https://github.com/matheusmarnt/livecharts/compare/v1.2.0...v1.3.0) (2026-05-02)


### Features

* comprehensive 2026 chart types ([5724062](https://github.com/matheusmarnt/livecharts/commit/5724062fc0a707cebac95de62c75a9f330566b09))
* implement comprehensive 2026 chart types for ApexCharts and Chart.js ([4571a54](https://github.com/matheusmarnt/livecharts/commit/4571a542d7bc57bcb835d4d3225f4b9dc26043c7))

## [1.2.0](https://github.com/matheusmarnt/livecharts/compare/v1.1.0...v1.2.0) (2026-05-02)


### Features

* asset auto-injection and more chart classes ([8e13332](https://github.com/matheusmarnt/livecharts/commit/8e13332930bad87fff790c370d1e6a8a39ce2714))
* implement asset auto-injection and more specialized chart classes ([b084671](https://github.com/matheusmarnt/livecharts/commit/b0846719df1186d7617a3504277782630f97053e))

## [1.1.0](https://github.com/matheusmarnt/livecharts/compare/v1.0.0...v1.1.0) (2026-05-02)


### Features

* expand Chart.js adapter support and options mapping ([a1cff7a](https://github.com/matheusmarnt/livecharts/commit/a1cff7ac38bddc949658953ccb39c9091d92e4d4))
* expand Chart.js adapter support and options mapping ([e1195d3](https://github.com/matheusmarnt/livecharts/commit/e1195d387a6ed94953ffcc6b6f60183f9525e987))

## 1.0.0 (2026-05-02)


### Features

* complete v1.0 foundation with specialized charts and full README ([2dc3077](https://github.com/matheusmarnt/livecharts/commit/2dc307741f513a97f61e90eba30b80d64f545b45))
* expand engine adapters for single-series charts and improved colors ([9d80213](https://github.com/matheusmarnt/livecharts/commit/9d8021396bc0f463901b533f9d117b3a93110660))
* implement core architecture and engine adapters ([9ab3b40](https://github.com/matheusmarnt/livecharts/commit/9ab3b40f90a10ffccdecfb1ee2550a952608ea3b))
* implement frontend integration (Livewire + Alpine) ([775e7ba](https://github.com/matheusmarnt/livecharts/commit/775e7baa6e0657c0508c9cff5627972132e46b14))
* implement make:chart artisan command ([431ad0b](https://github.com/matheusmarnt/livecharts/commit/431ad0be6837b45f7654c8289db65e331043bdff))
* implement onDataPointClick event support ([4696b7e](https://github.com/matheusmarnt/livecharts/commit/4696b7ece1d8d24217ed7dee4d3b320c9109d7b2))
* implement polling support in Livewire component ([1d0da52](https://github.com/matheusmarnt/livecharts/commit/1d0da5248fb4d702b80d6075fe1098e662539a58))
* implement soft/hard update logic in Alpine component ([92c7e2c](https://github.com/matheusmarnt/livecharts/commit/92c7e2c8706934024206b1691b69542a95a9d4d7))
* implement specialized chart classes for better DX ([557429c](https://github.com/matheusmarnt/livecharts/commit/557429cf88f33b74b148a9ce28e140d944cd95bf))
* initial commit with spatie skeleton ([575b845](https://github.com/matheusmarnt/livecharts/commit/575b8458364e4aab06a5b929bfb25ee3692b5b84))
* package configuration and docs ([07f7856](https://github.com/matheusmarnt/livecharts/commit/07f78563bc92a850fc4c7baae729d59409b1969d))


### Bug Fixes

* improve test coverage and fix PHPStan path error ([adde365](https://github.com/matheusmarnt/livecharts/commit/adde365c7d36ab5ce3c26d244e4dc51570306302))


### Miscellaneous Chores

* update release-please action and fix CI config ([ecc6973](https://github.com/matheusmarnt/livecharts/commit/ecc697356f03ed2b4e3311c6a3fa3d08211738d6))

## Changelog

All notable changes to `Eleve sua visualização de dados com gráficos reativos no Laravel.` will be documented in this file.
