<?php

declare(strict_types=1);

namespace App\Handler;

use App\Domain\Post\PostRepositoryInterface;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Handler\NotFoundHandler;
use Zend\Expressive\Template\TemplateRendererInterface;

class BlogPostHandlerFactory
{
    public function __invoke(ContainerInterface $container) : BlogPostHandler
    {
        return new BlogPostHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(PostRepositoryInterface::class),
            $container->get(NotFoundHandler::class)
        );
    }
}
