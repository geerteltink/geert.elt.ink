# xtreamwayz.com

[![Build Status](https://travis-ci.org/xtreamwayz/xtreamwayz.com.svg?branch=master)](https://travis-ci.org/xtreamwayz/xtreamwayz.com)
[![Code Coverage](https://scrutinizer-ci.com/g/xtreamwayz/xtreamwayz.com/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/xtreamwayz/xtreamwayz.com/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/xtreamwayz/xtreamwayz.com/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/xtreamwayz/xtreamwayz.com/?branch=master)

xtreamwayz.com is brought to your browser by [Expressive](https://github.com/zendframework/zend-expressive);
Coded with love conform [PSR-1: Basic Coding Standard](http://www.php-fig.org/psr/psr-1/) and 
[PSR-2: Coding Style Guide](http://www.php-fig.org/psr/psr-2/);
Autoloaded with [PSR-4: Autoloading Standard](http://www.php-fig.org/psr/psr-4/); 
Logged with [PSR-3: Logger Interface](http://www.php-fig.org/psr/psr-3/);
And using [PSR-7: HTTP messages](http://www.php-fig.org/psr/psr-7/) 
and [PSR-15: HTTP Middleware](https://github.com/http-interop/http-middleware).

Other used packages:
- [zendframework/zend-servicemanager](https://github.com/zendframework/zend-servicemanager) dependency container.
- [nikic/FastRoute](https://github.com/nikic/FastRoute) router.
- [twigphp/Twig](https://github.com/nikic/FastRoute) template engine.
- [doctrine/cache](https://github.com/doctrine/cache) for caching.
- [monolog/monolog](https://github.com/monolog/monolog) for logging.
- [ocramius/psr7-session](https://github.com/Ocramius/PSR7Session) for storage-less PSR-7 session support.
- [xtreamwayz/html-form-validator](https://github.com/xtreamwayz/html-form-validator) for form validation.
- [guzzlehttp/guzzle](https://github.com/guzzlehttp/guzzle) for grabbing repository data from GitHub.
- [pure svg icons](https://icomoon.io/).

## Application Architecture

- src/Presentation -> Holds everything that interacts with other systems. (Controller, view, form, api)
- src/Application -> The thin layer that connects clients from outside to your Domain through http requests, API...
- src/Domain -> Business logic.
- src/Infrastructure -> Consists of everything that exists independently of the app: external libs, db engine, messaging...
- src/Factory -> Collection of factories.

## License and Copyright

Following files, directories and their contents are copyrighted by Geert Eltink unless explicitly stated otherwise.
You may not reuse anything therein without permission:

* [data/posts/](/data/posts)
* [resources/public/img/](resources/public/img)
* [public/assets/img/](public/assets/img)

All other files and directories are licensed under the [MIT](http://www.opensource.org/licenses/mit-license.php)
unless explicitly stated.
