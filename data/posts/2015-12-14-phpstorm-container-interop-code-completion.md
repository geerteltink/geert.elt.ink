---
id: 2015-12-14-phpstorm-container-interop-code-completion
title: PhpStorm Container Interop Code Completion
summary: Easily add code completion for the container-interop in PhpStorm.
draft: false
public: true
published: 2015-12-14T11:00:00+01:00
modified: 2016-10-05T22:06:00+01:00
tags:
    - phpstorm
    - container-interop
    - code completion
---

For a long time I've been wondering how to make code completion work in PhpStorm for dependency containers. I know
there is a plugin for Symfony projects that works nicely. However I couldn't figure out how to make something like
that work outside a Symfony project. Lately I've been building some projects on top of Zend Expressive and it's really
frustrating not having code completion. Until today...

I guess the past half year I didn't ask the right question to google because this morning it suddenly showed up. It
turns out be be really easy. All you need is a ``.phpstorm.meta.php`` file in your project root, configure it a bit
and the magic is there again.

```php
<?php

/**
 * PhpStorm Container Interop code completion
 *
 * Add code completion for container-interop.
 *
 * \App\ClassName::class will automatically resolve to it's own name.
 *
 * Custom strings like ``"cache"`` or ``"logger"`` need to be added manually.
 */
namespace PHPSTORM_META
{
    $STATIC_METHOD_TYPES = [
        \Interop\Container\ContainerInterface::get('') => [
            'logger' instanceof \Psr\Log\LoggerInterface,
            '' == '@',
        ],
   
        // Add code completion for PSR-7 requests attributes like PSR-7 Storage-less HTTP Session 
        \Psr\Http\Message\ServerRequestInterface::getAttribute('') => [
            \PSR7Session\Http\SessionMiddleware::SESSION_ATTRIBUTE instanceof \PSR7Session\Session\SessionInterface,
        ],
    ];
}
```

The magic happens here: ``"" == "@"``. It tries to resolve anything to a valid class. So now you can do this:

```php
$template = $container->get(Zend\Expressive\Template\TemplateRendererInterface::class);
$template->render('app::home-page');
```

This does not work for custom strings like ``"logger"``. You need to add those manually like this:
``"logger" instanceof \Psr\Log\LoggerInterface``. Once it is added you also have auto completion for that:

```php
$logger = $this->container->get('logger');
$logger->info('Code completion test for strings');
```

Obviously this only works for projects that support the
[ContainerInterface](https://github.com/container-interop/container-interop). And otherwise you can most likely
change the config file a bit and make it work for the dependency container you use. More info on how to use this can
be found on the [JetBrains site](https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Advanced+Metadata).

I just can't believe this isn't activated by default.
