<?php

namespace App\Action;

use DateTime;
use Domain\Post\PostRepository;
use DOMDocument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;

class BlogXmlFeed extends ActionAbstract
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param callable|null          $next
     *
     * @return HtmlResponse
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $cache = $this->get('cache');

        $item = $cache->getItem('xml-feed');
        $feed = $item->get();
        if ($item->isMiss()) {
            $item->lock();

            $feed = $this->generateXmlFeed();

            $item->set($feed);
        }
        $feed = $this->generateXmlFeed();
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
        $selfLink->setAttribute('href', $this->generateUrl('feed.xml', [], true));
        $selfLink->setAttribute('rel', 'self');
        $feed->appendChild($selfLink);

        $alternateLink = $xml->createElement('link');
        $alternateLink->setAttribute('type', 'text/html');
        $alternateLink->setAttribute('href', $this->generateUrl('home', [], true));
        $alternateLink->setAttribute('rel', 'alternate');
        $feed->appendChild($alternateLink);

        $id = $xml->createElement('id', $this->generateUrl('home', [], true));
        $feed->appendChild($id);

        $generator = $xml->createElement('generator', 'zend-expressive');
        $generator->setAttribute('uri', 'https://github.com/zendframework/zend-expressive');
        $feed->appendChild($generator);

        $rights = $xml->createElement(
            'rights',
            sprintf('Copyright (c) 2005-%s Geert Eltink. All Rights Reserved.', date('Y'))
        );
        $feed->appendChild($rights);

        $postRepository = $this->get(PostRepository::class);
        $posts = array_slice(array_reverse($postRepository->findAll()), 0, 5);
        /** @var \Domain\Post\Post $post */
        foreach ($posts as $post) {
            $entry = $xml->createElement('entry');

            $entryTitle = $xml->createElement('title', $post->getTitle());
            $entry->appendChild($entryTitle);

            $entryLink = $xml->createElement('link');
            $entryLink->setAttribute('href', $this->generateUrl('blog.post', ['id' => $post->getId()], true));
            $entry->appendChild($entryLink);

            $entryId = $xml->createElement('id', $this->generateUrl('blog.post', ['id' => $post->getId()], true));
            $entry->appendChild($entryId);

            $entryPublished = $xml->createElement('published', $post->getPublished()->format(DateTime::RFC3339));
            $entry->appendChild($entryPublished);

            if ($post->getModified()) {
                $entryUpdated = $xml->createElement('updated', $post->getModified()->format(DateTime::RFC3339));
                $entry->appendChild($entryUpdated);
            }

            $entrySummary = $xml->createElement('summary', $post->getSummary());
            $entry->appendChild($entrySummary);

            if ($post->getContent()) {
                $entryContent = $xml->createElement('content');
                $entry->appendChild($entryContent);
                $entryContentData = $xml->createCDATASection($post->getContent());
                $entryContent->appendChild($entryContentData);
            }

            $entryAuthor = $xml->createElement('author');
            $entry->appendChild($entryAuthor);

            $entryAuthorName = $xml->createElement('name', 'Geert Eltink');
            $entryAuthor->appendChild($entryAuthorName);

            $entryAuthorUri = $xml->createElement('uri', $this->generateUrl('home', [], true));
            $entryAuthor->appendChild($entryAuthorUri);

            $feed->appendChild($entry);
        }

        return $xml->saveXML();
    }
}
