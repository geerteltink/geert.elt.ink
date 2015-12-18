<?php

namespace App\Action;

use DateTime;
use Domain\Post\PostRepository;
use DOMDocument;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;

class BlogXmlFeed
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
     * HomePageAction constructor.
     *
     * @param ContainerInterface             $container
     * @param TemplateRendererInterface|null $template
     */
    public function __construct(ContainerInterface $container, TemplateRendererInterface $template = null)
    {
        $this->container = $container;
        $this->template = $template;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return HtmlResponse
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $cache = $this->container->get('cache');

        $item = $cache->getItem('xml-feed');
        $feed = $item->get();
        if ($item->isMiss()) {
            $item->lock();

            $feed = $this->generateXmlFeed();

            $item->set($feed);
        }

        $response->getBody()->write($feed);

        return $response->withHeader('Content-Type', 'application/atom+xml');
    }

    public function generateXmlFeed()
    {
        $xml = new DOMDocument('1.0', 'UTF-8');
        $xml->formatOutput = true;

        $feed = $xml->createElementNS('http://www.w3.org/2005/Atom', 'feed');
        $feed->setAttribute('xml:lang', 'en-US');
        $xml->appendChild($feed);

        $title = $xml->createElement('title', 'XtreamWayz');
        $feed->appendChild($title);

        $subtitle = $xml->createElement('subtitle', 'A web developer\'s playground, notes and thoughts.');
        $feed->appendChild($subtitle);

        $updated = $xml->createElement('updated', (new DateTime('now'))->format(DateTime::RFC3339));
        $feed->appendChild($updated);

        $selfLink = $xml->createElement('link');
        $selfLink->setAttribute('type', 'application/atom+xml');
        $selfLink->setAttribute('href', 'https://xtreamwayz.com/blog/feed.xml'); // TODO: Generate self link
        $selfLink->setAttribute('rel', 'self');
        $feed->appendChild($selfLink);

        $alternateLink = $xml->createElement('link');
        $alternateLink->setAttribute('type', 'text/html');
        $alternateLink->setAttribute('href', 'https://xtreamwayz.com/');
        $alternateLink->setAttribute('rel', 'alternate');
        $feed->appendChild($alternateLink);

        $id = $xml->createElement('id', 'https://xtreamwayz.com/');
        $feed->appendChild($id);

        $generator = $xml->createElement('generator', 'zend-expressive');
        $generator->setAttribute('uri', 'https://github.com/zendframework/zend-expressive');
        $feed->appendChild($generator);

        $rights = $xml->createElement(
            'rights',
            sprintf('Copyright (c) 2013-%s Geert Eltink. All Rights Reserved.', date('Y'))
        );
        $feed->appendChild($rights);

        $postRepository = $this->container->get(PostRepository::class);
        $posts = array_reverse($postRepository->findAll());
        /** @var \Domain\Post\Post $post */
        foreach ($posts as $post) {
            $entry = $xml->createElement('entry');

            $entryTitle = $xml->createElement('title', $post->getTitle());
            $entry->appendChild($entryTitle);

            $entryLink = $xml->createElement('link');
            $entryLink->setAttribute('href', 'https://xtreamwayz.com/blog/'.$post->getId());
            $entry->appendChild($entryLink);

            $entryId = $xml->createElement('id', 'https://xtreamwayz.com/blog/'.$post->getId());
            $entry->appendChild($entryId);

            $entryPublished = $xml->createElement('published', $post->getPublished()->format(DateTime::RFC3339));
            $entry->appendChild($entryPublished);

            if ($post->getModified()) {
                $entryUpdated = $xml->createElement('updated', $post->getModified()->format(DateTime::RFC3339));
                $entry->appendChild($entryUpdated);
            }

            $entrySummary = $xml->createElement('summary', $post->getSummary());
            $entry->appendChild($entrySummary);

            $entryAuthor = $xml->createElement('author');
            $entry->appendChild($entryAuthor);

            $entryAuthorName = $xml->createElement('name', 'Geert Eltink');
            $entryAuthor->appendChild($entryAuthorName);

            $entryAuthorUri = $xml->createElement('uri', 'https://xtreamwayz.com/');
            $entryAuthor->appendChild($entryAuthorUri);

            $feed->appendChild($entry);
        }

        return $xml->saveXML();
    }
}
