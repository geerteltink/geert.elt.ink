## Container-Interop wrapper for Pimple 3.0

PHP middleware is growing fast these days. One of the nicest features is that you can mix and match different packages from different frameworks, or even use standalone packages.

For dependency containers, this is now really easy if they implement the [ContainerInterface](https://github.com/container-interop/container-interop). For Pimple 1.x is the [pimle-interop](https://github.com/moufmouf/pimple-interop) wrapper. For Pimple 3.x there isn't one yet and the developers have decided to wait unit container-interop is accepted as a [PSR](https://github.com/php-fig/fig-standards/blob/master/proposed/container.md). But this doesn't mean you have to wait…

It's pretty easy. Just use this code as a wrapper, and you are done.

```php
<?php

namespace Pimple;

use Pimple\Container as Pimple;
use Interop\Container\ContainerInterface;

/**
 * ContainerInterface wrapper for Pimple 3.0
 */
class PimpleContainer extends Pimple implements ContainerInterface
{
    public function get($id)
    {
        return $this->offsetGet($id);
    }
    public function has($id)
    {
        return $this->offsetExists($id);
    }
}
```
