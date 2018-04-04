<?php

declare(strict_types=1);

namespace App\Handler;

use App\Domain\Post\PostRepositoryInterface;
use Doctrine\Common\Cache\Cache;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class BlogIndexHandlerFactory
{
    public function __invoke(ContainerInterface $container) : BlogIndexHandler
    {
        return new BlogIndexHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(PostRepositoryInterface::class),
            $container->get(Cache::class)
        );
    }
}
