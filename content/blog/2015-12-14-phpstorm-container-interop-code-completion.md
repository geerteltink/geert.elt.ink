---
id: 2015-12-14-phpstorm-container-interop-code-completion
title: PhpStorm PSR-11 Container Interface Code Completion
summary: Easily add code completion for PSR-11 Container Interface in PhpStorm.
date: 2015-12-14
tags:
    - phpstorm
    - php
    - psr-11
    - container-interop
    - code completion
---

<blockquote class="blockquote">
    <p>
        Updated with support for PSR-11 Container Interface and use the new 2016.2+ PHPSTORM_META format.
    </p>
</blockquote>

For a long time I've been wondering how to make code completion work in PhpStorm for dependency containers. These
containers used the `Interop\Container\ContainerInterface` and since February 2017 it's accepted by PHP-Fig as PSR-11
and use the `Psr\Container\ContainerInterface`. I know there is a plugin for Symfony projects that works nicely.
However I couldn't figure out how to make something like that work outside a Symfony project. Lately I've been
building some projects on top of Zend Expressive and it's really frustrating not having code completion. Until
today...

I guess the past half year I didn't ask the right question to google because this morning it suddenly showed up. It
turns out be be really easy. All you need is a `.phpstorm.meta.php` file in your project root, configure it a bit
and the magic is there again.

```php
<?php

/**
 * PhpStorm code completion
 *
 * Add code completion for PSR-11 Container Interface and more...
 */

namespace PHPSTORM_META {

    use Interop\Container\ContainerInterface as InteropContainerInterface;
    use Psr\Container\ContainerInterface as PsrContainerInterface;
    use Psr\Http\Message\ServerRequestInterface;
    use PSR7Session\Http\SessionMiddleware;
    use PSR7Session\Session\SessionInterface;

    // Old Interop\Container\ContainerInterface
    override(InteropContainerInterface::get(0),
        map([
            '' => '@',
        ])
    );

    // PSR-11 Container Interface
    override(PsrContainerInterface::get(0),
        map([
            '' => '@',
        ])
    );

    // PSR-7 requests attributes; e.g. PSR-7 Storage-less HTTP Session
    override(ServerRequestInterface::getAttribute(0),
        map([
            SessionMiddleware::SESSION_ATTRIBUTE instanceof SessionInterface,
        ])
    );
}
```

The magic happens here: `'' == '@'`. It tries to resolve anything to a valid class. So now you can do this:

```php
$template = $container->get(Zend\Expressive\Template\TemplateRendererInterface::class);
$template->render('app::home-page');
```

This does not work for custom strings like `'logger'`. You need to add those manually like this:
`'logger' instanceof \Psr\Log\LoggerInterface`. Once it is added you also have auto completion for that:

```php
$logger = $this->container->get('logger');
$logger->info('Code completion test for strings');
```

Obviously this only works for projects that support the
[PSR-11 Container Interface](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md).
And otherwise you can most likely change the config file a bit and make it work for the dependency container you
use. More info on how to use this can be found on the
[JetBrains site](https://confluence.jetbrains.com/display/PhpStorm/PhpStorm+Advanced+Metadata).
