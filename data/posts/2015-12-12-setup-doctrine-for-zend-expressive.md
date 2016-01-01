---
id: 2015-12-12-setup-doctrine-for-zend-expressive
title: How to setup doctrine for zend expressive
summary: Build a Zend Expressive Doctrine factory and cache driver factory.
draft: false
public: true
published: 2015-12-12T12:00:00+01:00
modified: false
tags:
    - zend expressive
    - doctrine
    - caching
---

You want to use doctrine in your Zend Expressive project but don't know where to start? Here is how to do it. This
guide uses a doctrine factory and a separate cache factory so you can use the doctrine cache for other purposes too.

## Doctrine Factory

The factory reads the configuration and applies it to doctrine for you. It will return the entity manager.

```php
<?php // src/App/Container/DoctrineFactory.php

namespace App\Container;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Interop\Container\ContainerInterface;

class DoctrineFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];
        $proxyDir = (isset($config['doctrine']['orm']['proxy_dir'])) ?
            $config['doctrine']['orm']['proxy_dir'] : 'data/cache/EntityProxy';
        $proxyNamespace = (isset($config['doctrine']['orm']['proxy_namespace'])) ?
            $config['doctrine']['orm']['proxy_namespace'] : 'EntityProxy';
        $autoGenerateProxyClasses = (isset($config['doctrine']['orm']['auto_generate_proxy_classes'])) ?
            $config['doctrine']['orm']['auto_generate_proxy_classes'] : false;
        $underscoreNamingStrategy = (isset($config['doctrine']['orm']['underscore_naming_strategy'])) ?
            $config['doctrine']['orm']['underscore_naming_strategy'] : false;

        // Doctrine ORM
        $doctrine = new Configuration();
        $doctrine->setProxyDir($proxyDir);
        $doctrine->setProxyNamespace($proxyNamespace);
        $doctrine->setAutoGenerateProxyClasses($autoGenerateProxyClasses);
        if ($underscoreNamingStrategy) {
            $doctrine->setNamingStrategy(new UnderscoreNamingStrategy());
        }

        // ORM mapping by Annotation
        AnnotationRegistry::registerFile('vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php');
        $driver = new AnnotationDriver(
            new AnnotationReader(),
            ['src/Domain']
        );
        $doctrine->setMetadataDriverImpl($driver);

        // Cache
        $cache = $container->get(Cache::class);
        $doctrine->setQueryCacheImpl($cache);
        $doctrine->setResultCacheImpl($cache);
        $doctrine->setMetadataCacheImpl($cache);

        // EntityManager
        return EntityManager::create($config['doctrine']['connection']['orm_default'], $doctrine);
    }
}
```

## The cache factory

The cache factory is a separate factory so you can use it for other purposes as well. For this example a Redis cache
driver is used. You can easily create your own factory for other supported cache drivers.

```php
<?php // src/App/Container/DoctrineRedisCacheFactory.php

namespace App\Container;

use Doctrine\Common\Cache\RedisCache;
use Interop\Container\ContainerInterface;

class DoctrineRedisCacheFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->has('config') ? $container->get('config') : [];

        $redis = new \Redis();
        $redis->connect(
            $config['doctrine']['cache']['redis']['host'],
            $config['doctrine']['cache']['redis']['port']
        );

        $cache = new RedisCache();
        $cache->setRedis($redis);

        return $cache;
    }
}
```

## The configuration

You can go several ways to setup the configuration. You can create a ``doctrine.global.php`` config file and overwrite
some settings in a local file. In this case the whole doctrine config is in the local config file since most settings
are different on the production server.

If you are using windows on your dev machine, make sure you use ``127.0.0.1`` as the host in stead of ``localhost``. It
massively speeds up connecting to the database. At least for MySQL, I don't know about other databases.

```php
<?php // config/autoload/doctrine.local.php

return [
    'doctrine' => [
        'orm'        => [
            'auto_generate_proxy_classes' => false,
            'proxy_dir'                   => 'data/cache/EntityProxy',
            'proxy_namespace'             => 'EntityProxy',
            'underscore_naming_strategy'  => true,
        ],
        'connection' => [
            // default connection
            'orm_default' => [
                'driver'   => 'pdo_mysql',
                'host'     => '127.0.0.1',
                'port'     => '3306',
                'dbname'   => '*****',
                'user'     => '*****',
                'password' => '*****',
                'charset'  => 'UTF8',
            ],
        ],
        'cache'      => [
            'redis' => [
                'host' => '127.0.0.1',
                'port' => '6379',
            ],
        ],
    ],
];
```

## The container

What is left is adding doctrine to the container so you can easily access it throughout the project. To do that you need
to add 2 lines to your dependencies config.

```php
<?php // config/autoload/dependencies.global.php

return [
    'dependencies' => [
        'invokables' => [
            // ...
        ],
        'factories'  => [
            // ...
            Doctrine\Common\Cache\Cache::class => App\Container\DoctrineRedisCacheFactory::class,
            Doctrine\ORM\EntityManager::class  => App\Container\DoctrineFactory::class,
        ],
    ],
];
```

## Accessing doctrine

Everything is ready and registered in the container. To access doctrine you need to get it from the container.

```php
<?php // src/App/Action/IndexAction.php

namespace App\Action;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template\TemplateRendererInterface;

class IndexAction
{
    private $em;

    private $template;

    public function __construct(EntityManager $em, TemplateRendererInterface $template)
    {
        $this->em = $em;
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $userRepository = $this->em->getRepository('App\Domain\User\User');
        $users = $userRepository->findAll();

        return new HtmlResponse($this->template->render('app::index'));
    }
}
```

## Using doctrine cache

Because the cache driver has been setup in its own factory, it can now be used whenever you need to cache something.

```php
<?php
use Doctrine\Common\Cache\Cache;

/** @var Cache $cache */
$cache = $container->get(Cache::class);

if ($cache->contains('my_array')) {
    // Fetching the cached data
    $array = $cache->fetch('my_array');
} else {
    // Example data
    $array = array(
        'key1' => 'value1',
        'key2' => 'value2'
    );
    // Save data to the cache
    $cache->save('my_array', $array);
}
```

More info on how to use the cache can be found in the
[doctrine docs](https://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/reference/caching.html).

## Doctrine Console

The Doctrine Console is a very useful command line interface tool. However it doesn't work out of the box. Doctine
needs to know how to setup the entity manager with the right configuration. Fortunately this is easily done by
creating the file ``cli-config.php`` in the project root.

```php
<?php // cli-config.php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\EntityManager;

require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

/** @var \Doctrine\ORM\EntityManager $em */
$em = $container->get(EntityManager::class);

return ConsoleRunner::createHelperSet($em);
```

This file loads the configuration, setup the container and gets the entity manger which will then be injected in the
console. All you need to do now is call the doctrine console.

```bash
# List all commands
php vendor/bin/doctrine list
```

More info about how to use the doctrine console can be found on the
[doctrine site](https://doctrine-orm.readthedocs.org/projects/doctrine-orm/en/latest/reference/tools.html).

Enjoy your zend expressive project with doctrine.
