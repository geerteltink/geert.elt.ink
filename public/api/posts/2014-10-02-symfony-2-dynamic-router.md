---
id: 2014-10-02-symfony-2-dynamic-router
title: Symfony 2 Dynamic Router
summary: There are some dynamic router examples out there for Symfony 2. But most are overly complicated. This is an easy way to load dynamic routes from a database.
draft: false
public: true
published: 2014-10-02T10:00:00+01:00
modified: 2015-02-21T15:39:00+01:00
tags:
    - Symfony 2
---

What I tried to achieve is having a content table with the different content types in it. Sort of like WordPress. The idea is that you don't want to add all pages and blog posts to your routing table manually. It would be nicer to add a router that matches the url path against the content table and grab its content. This way if you add pages, blog posts or even move pages around, it always know where to find them.

## The Route

A dynamic router can load routes from a database. There are some good ones out there for Symfony 2 like [Symfony CMF](http://symfony.com/doc/current/cmf/bundles/routing/dynamic.html). They are overly complicated and it can be a lot easier without the need to install a third party Symfony bundle.

What you need is a *catch-all* route. Standard placeholders exclude the slash `/`. If you create a place holder with a `.+` requirement it catches everything and calls the assigned controller. One thing to keep in mind is that Symfony goes through the routes list from first registered to last registered. So if this catch all route would be first, it will always be called. That's why you want this route to be the last one in the list.

```xml
<?xml version="1.0" encoding="UTF-8" ?>

<routes xmlns="http://symfony.com/schema/routing"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/routing http://symfony.com/schema/routing/routing-1.0.xsd">

    <!-- This is the dynamic content loader, it should always be last! -->
    <route id="content.guid" path="/{guid}">
        <default key="_controller">AcmeContentBundle:DynamicRouter:getResourceByGuid</default>
        <requirement key="_method">get</requirement>
        <requirement key="guid">.+</requirement>
    </route>
</routes>
```

## The Controller

Next up is the controller. It doesn't do much magic. It checks the database table, in my case the content table, and tries to match the content against the *guid*. Guid is the global unique identifier, just a fancy name for url paths like `page/my-page-slug` and `blog\2014\10\02\my-blog-slug`.

```php
<?php

namespace Acme\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Dynamic router controller
 *
 * Tries to locate resources by a given path.
 */
class DynamicRouterController extends Controller
{
    /**
     * Dynamic content loader
     *
     * Takes the resource path and search for it in the content table.
     *
     * @param Request $request
     * @param $guid
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResourceByGuidAction(Request $request, $guid)
    {
        $contentService = $this->get('acme.content.content.service');

        // Redirect /wiki to /wiki/main-page
        if ($guid === 'wiki') {
            return $this->redirect('/wiki/main-page', 301);
        }

        $entity = $contentService->findOneByGuid($guid);
        if (!$entity) {
            throw $this->createNotFoundException(sprintf('No resource found for "%s /%s"',
                $request->getMethod(),
                $guid
            ));
        }

        // TODO: Do some tests to check if the content is already published and not yet expired

        return $this->render('AcmeContentBundle:Content:get.html.twig', array(
            'entity' => $entity
        ));
    }
}
```

## The Service Class

What's left is creating the service class with the functionality to store the content entity. This is the tricky one since all the magic is done in here. You can be as creative as you want but it should take care of at least a few things:

- Generate the guid on creating a new entity.
- Re-generate the guid on updating an entity.
- Update its children if the guid changed. e.g. If you have a page content type that can have a parent and it inherits its parent slug as a prefix of the guid. This way you get structures like: `documentation`, `documentation/controller` and `documentation/routing`.
