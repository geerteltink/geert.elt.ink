<?php

namespace App\Action;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Router\RouteResult;
use Zend\Expressive\Template\TemplateRendererInterface;

abstract class ActionAbstract
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var TemplateRendererInterface
     */
    private $template;

    /**
     * @var UrlHelper
     */
    private $urlHelper;

    /**
     * @var ServerUrlHelper
     */
    private $serverUrlHelper;

    /**
     * @var RouteResult
     */
    private $routeResult;

    /**
     * BlogIndexAction constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;
    }

    public function get($id)
    {
        return $this->container->get($id);
    }

    public function render($name, $params = [])
    {
        if (!$this->template) {
            $this->template = $this->container->get(TemplateRendererInterface::class);
        }

        return $this->template->render($name, $params);
    }

    public function generateUrl($route = null, array $params = [], $absoluteUrl = false)
    {
        if (!$this->urlHelper) {
            $this->urlHelper = $this->container->get(UrlHelper::class);
        }

        $url = $this->urlHelper->generate($route, $params);

        if ($absoluteUrl !== true) {
            return $url;
        }

        return $this->generateServerUrl($url);
    }

    public function generateServerUrl($path = null)
    {
        if (!$this->serverUrlHelper) {
            $this->serverUrlHelper = $this->container->get(ServerUrlHelper::class);
        }

        return $this->serverUrlHelper->generate($path);
    }
}
