# www.elt.ink - f.k.a xtreamwayz.com

![Netlify Status](https://api.netlify.com/api/v1/badges/ccb7030a-d423-436b-831d-21a6eabfaa8f/deploy-status)
![Q&A Tests](https://github.com/xtreamwayz/www.elt.ink/workflows/test/badge.svg)
![Nightly build](https://github.com/xtreamwayz/www.elt.ink/workflows/nightly/badge.svg)

This repo contains the source code for www.xtreamwayz.com. It has changed over time. First it started with zend-expressive. Changed in 2018 to a vuejs based progressive web app to learn the language, PWA and service workers concepts. And then there is hugo: A fast way to generate static sites.

## Version 3: Hugo

[source code](https://github.com/xtreamwayz/www.elt.ink/tree/master)

- [Hugo](https://gohugo.io/) a fast framework for building static websites

**Notes:**

Use icons in layouts: `{{ partial "fontawesome.html" "github" }}`
Use icons in content: `{{% fontawesome github %}}`

## Version 2: Vue.js, PWA and service worker

[source code](https://github.com/xtreamwayz/www.elt.ink/tree/vuejs)

- [Vue.js](https://www.npmjs.com/package/vue)
- [vue-analytics](https://www.npmjs.com/package/vue-analytics)
- [vue-meta](https://www.npmjs.com/package/vue-meta)
- [vue-router](https://www.npmjs.com/package/vue-router)
- [vuex](https://www.npmjs.com/package/vuex)

## Version 1: PHP / zend-expressive

[source code](https://github.com/xtreamwayz/www.elt.ink/tree/expressive)

- [zend-expressive](https://github.com/zendframework/zend-expressive)
- [zend-servicemanager](https://github.com/zendframework/zend-servicemanager) dependency container
- [FastRoute](https://github.com/nikic/FastRoute) router
- [Twig](https://github.com/twigphp/Twig) template engine
- [doctrine/cache](https://github.com/doctrine/cache) for caching
- [monolog](https://github.com/monolog/monolog) for logging
- [psr7-sessions/storageless](https://github.com/psr7-sessions/storageless) for storage-less PSR-7 session support
- [html-form-validator](https://github.com/xtreamwayz/html-form-validator) for form validation
- [guzzle](https://github.com/guzzlehttp/guzzle) for grabbing repository data from GitHub
- [pure svg icons](https://icomoon.io/)

## License and Copyright

Following files, directories and their contents are copyrighted by Geert Eltink unless explicitly stated otherwise.
You may not reuse anything therein without permission:

- [content/posts/](/data/posts)
- [assets/img/](resources/public/img)
- [resources/img/](public/assets/img)

All other files and directories are licensed under the [MIT](http://www.opensource.org/licenses/mit-license.php)
unless explicitly stated.
