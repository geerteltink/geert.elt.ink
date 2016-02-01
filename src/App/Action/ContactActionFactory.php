<?php

namespace App\Action;

use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;

class ContactActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ContactAction(
            $container->get(TemplateRendererInterface::class),
            $container->get(\Swift_Mailer::class),
            $container->get('config')['mail']
        );
    }
}
