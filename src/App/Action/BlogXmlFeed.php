<?php

namespace App\Action;

use Domain\Post\PostRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Router;
use Zend\Feed\Writer\Feed;

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

        $response->getBody()->write($feed);

        return $response->withHeader('Content-Type', 'application/atom+xml');
    }

    public function generateXmlFeed()
    {
        $feed = new Feed();
        $feed->setTitle('xtreamwayz');
        $feed->setLink($this->generateUrl('home', [], true));
        $feed->setFeedLink($this->generateUrl('feed.xml', [], true), 'atom');
        $feed->addAuthor(
            [
                'name' => 'Geert Eltink',
                'uri'  => 'https://xtreamwayz.com',
            ]
        );
        $feed->setDateModified(time());
        $feed->setCopyright(sprintf('Copyright (c) 2005-%s Geert Eltink. All Rights Reserved.', date('Y')));
        $feed->setDescription('A web developer\'s playground, notes and thoughts.');
        $feed->setId($this->generateUrl('home', [], true));

        $postRepository = $this->get(PostRepository::class);
        $posts = array_slice(array_reverse($postRepository->findAll()), 0, 5);
        /** @var \Domain\Post\Post $post */
        foreach ($posts as $post) {
            $entry = $feed->createEntry();
            $entry->setTitle($post->getTitle());
            $entry->setLink($this->generateUrl('blog.post', ['id' => $post->getId()], true));
            $entry->setId($this->generateUrl('blog.post', ['id' => $post->getId()], true));
            $entry->setDateCreated($post->getPublished());
            if ($post->getModified()) {
                $entry->setDateModified($post->getModified());
            }
            $entry->setDescription($post->getSummary());
            $entry->setContent($post->getContent());
            $entry->addAuthor(
                [
                    'name' => 'Geert Eltink',
                    'uri'  => 'https://xtreamwayz.com',
                ]
            );
            $feed->addEntry($entry);
        }

        return $feed->export('atom');
    }
}
