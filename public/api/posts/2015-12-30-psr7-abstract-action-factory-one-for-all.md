---
id: 2015-12-30-psr7-abstract-action-factory-one-for-all
title: One Abstract Action Factory For All
summary: Use one abstract action factory for all PSR-7 actions.
draft: false
public: true
published: 2015-12-30T09:54:00+01:00
modified: 2016-09-23T16:53:00+01:00
tags:
    - zend-expressive
    - dependency injection
    - zend-servicemanager
---

Yesterday I wrote about using [one ActionFactory for all](https://xtreamwayz.com/blog/2015-12-29-zend-expressive-action-factory-one-for-all)
your PSR-7 actions. I used zend-servicemanager for it, together with some voodoo to detect the dependencies and inject
it. I was pretty happy with the solution and then I got this
[tweet](https://twitter.com/samsonasik/status/681891488205160448):

<blockquote class="blockquote">
    <p>
        question: why not use abstract_factories? while it may take slower, it will reduce repetitive reg
        with same factory
    </p>
    <footer class="blockquote-footer">Abdul Malik Ikhsan (@samsonasik)</footer>
</blockquote>

After some more research I decided to try it out and it's actually pretty brilliant. Zend ServiceManger 3 is needed
for this.

```php
<?php

namespace App\Action;

use Interop\Container\ContainerInterface;
use ReflectionClass;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

class AbstractActionFactory implements AbstractFactoryInterface
{
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

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        // Only accept Action classes
        if (substr($requestedName, -6) == 'Action') {
            return true;
        }

        return false;
    }
}
```

As you can see, the code is almost the same as what I did before with the ActionFactory. However it now extends an
``AbstractFactoryInterface`` and it has this ``canCreate`` method.

To register the factory you need to add this line:

```php
'dependencies' => [
    'invokables'         => [
    ],
    'factories'          => [
    ],
    'abstract_factories' => [
        App\Action\AbstractActionFactory::class,
    ],
],
```

And now the most brilliant part... Remove all action factories from dependencies -> factories / invokables. Yes you
read it correctly, you can remove them. The abstract factory will automatically handle all actions from now on.

In case you used the expressive-skeleton, remove these two lines from ``routes.global.php``:

```php
'dependencies' => [
    'invokables' => [
        App\Action\PingAction::class => App\Action\PingAction::class,
    ],
    'factories' => [
        App\Action\HomePageAction::class => App\Action\HomePageFactory::class,
    ],
],
```

While trying to get homepage, under the hood the container (still zend-servicemanager) is looking for the
``HomePageAction`` class at the usual locations. But since it's not registered with the container, it falls back to
this abstract factory. In its ``canCreate`` method it tells the container it can handle all classes
ending with ``Action``. After that the abstract factory returns the Action class with the right dependencies.

There might be a downside though. Since the servicemanager checks if unregistered classes can be handled by a
specific abstract factory, it causes some overhead. As long as you limit the number of abstract factories you still
have a good performance.

At the time of writing this solution is used for this site and it's
[open source](https://github.com/xtreamwayz/xtreamwayz.com).
