---
id: 2015-12-29-zend-expressive-action-factory-one-for-all
title: 'Zend Expressive: One Action Factory For All'
summary: Use one action factory for all zend xpressive actions.
draft: false
public: true
published: 2015-12-29T17:47:00+01:00
modified: 2015-12-30T09:55:00+01:00
tags:
    - zend-expressive
    - dependency injection
    - zend-servicemanager
---

I know, it's better to write factories in stead of magical dependency injection. But some people are lazy, me included,
when it comes to repeating code over and over. In this case I'm talking about zend-expressive actions. When a project
grows you might end up with as much factories as actions. This probably goes for every PSR-7 oriented framework.

<blockquote class="blockquote">
    <p class="m-b-0">
        *NOTE:* There is even a better solution using an
        <a href="https://xtreamwayz.com/blog/2015-12-30-psr7-abstract-action-factory-one-for-all">abstract factory</a>.
    </p>
</blockquote>

A very simple action class might look like this:

```php
<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template;

class HomePageAction
{
    private $template;

    public function __construct(Template\TemplateRendererInterface $template = null)
    {
        $this->template = $template;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $data = [];

        // Do stuff here and populate the data array...

        return new HtmlResponse($this->template->render('app::home-page', $data));
    }
}
```

In the dependency container you get something like this:

```php
'dependencies' => [
    'factories' => [
        App\Action\HomePageAction::class => App\Action\HomePageFactory::class,
    ],
],
```

Now can you imagine if you have 20 action classes and you need to write as many factories to inject the template
renderer or anything else they all have in common? It would be nice to write just one factory for all actions. Luckily
this is possible. And it's pretty easy as well. In this example I have chosen for
[zend-servicemanager 3](https://github.com/zendframework/zend-servicemanager/tree/develop). At the time of writing it's
still living in the develop branch. But it's fast, usable and it has a nice feature: FactoryInterface.

> A factory is a callable object that is able to create an object. It is given the instance of the service locator,
the requested name of the class you want to create, and any additional options that could be used to configure the
instance state.

```php
interface FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null);
}
```

And that ``$requestedName`` is exactly what we need:

```php
<?php

namespace App\Action;

use Interop\Container\ContainerInterface;
use ReflectionClass;
use Zend\ServiceManager\Factory\FactoryInterface;

class ActionFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // Construct a new ReflectionClass object for the requested action
        $reflection = new ReflectionClass($requestedName);
        // Get the constructor
        $constructor = $reflection->getConstructor();
        if (is_null($constructor)) {
            // There is no constructor, just return a new class
            return new $requestedName;
        }

        // Get the parameters
        $parameters = $constructor->getParameters();
        $dependencies = [];
        foreach ($parameters as $parameter) {
            // Get the parameter class
            $class = $parameter->getClass();
            // Get the class from the container
            $dependencies[] = $container->get($class->getName());
        }

        // Return the requested class and inject its dependencies
        return $reflection->newInstanceArgs($dependencies);
    }
}
```

Now you need to change the factories for the HomePageAction and PingAction. Just point them to the same action factory:

```php
'dependencies' => [
    'invokables' => [
    ],
    'factories' => [
        App\Action\HomePageAction::class => App\Action\ActionFactory::class,
        App\Action\PingAction::class => App\Action\ActionFactory::class,
    ],
],
```

If the home page is requested, zend-expressive is calling the ``App\Action\HomePageAction`` class and the dependency
container is telling it to use ``App\Action\ActionFactory``. Behind the scene zend-servicemanager is passing along the
requested HomePageAction class, which is need to instantiate the correct class. The ActionFactory gets the right
dependencies from the container and injects those into a newly created HomePageAction.

To get this working zend-servicemanager 3 is needed (there are probably more ways to do this, but I like it this way).
To install it you need to get the develop branch until it's released. Just change the dependency in ``composer.json``:

```json
"require": {
    "zendframework/zend-servicemanager": "3.0.x-dev"
},
```

Anyway, I think this is a better solution than what I used before: Injecting the complete container in every Action :)

If you want to test this code, you can use the [zend-expressive-skeleton](https://github.com/zendframework/zend-expressive-skeleton)
to create a basic project within seconds.
