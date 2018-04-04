<?php

declare(strict_types=1);

namespace App\Handler;

use App\Domain\Post\PostRepositoryInterface;
use Doctrine\Common\Cache\Cache;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;

class BlogXmlFeedHandlerFactory
{
    public function __invoke(ContainerInterface $container) : BlogXmlFeedHandler
    {
        return new BlogXmlFeedHandler(
            $container->get(Cache::class),
            $container->get(PostRepositoryInterface::class),
            $container->get(UrlHelper::class),
            $container->get(ServerUrlHelper::class)
        );
    }
}
