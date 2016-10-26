<?php

declare(strict_types = 1);

namespace App\Http\Action;

use App\Domain\Post\PostRepositoryInterface;
use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Feed\Writer\Feed;
use Zend\Stratigility\MiddlewareInterface;

class BlogXmlFeedAction implements MiddlewareInterface
{
    private $cache;

    private $postRepository;

    private $urlHelper;

    private $serverUrlHelper;

    public function __construct(
        Cache $cache,
        PostRepositoryInterface $postRepository,
        UrlHelper $urlHelper,
        ServerUrlHelper $serverUrlHelper
    ) {
        $this->cache           = $cache;
        $this->postRepository  = $postRepository;
        $this->urlHelper       = $urlHelper;
        $this->serverUrlHelper = $serverUrlHelper;
    }

    /**
     * @param Request       $request
     * @param Response      $response
     * @param callable|null $next
     *
     * @return Response
     *
     * @throws \InvalidArgumentException
     */
    public function __invoke(Request $request, Response $response, callable $next = null): Response
    {
        if ($this->cache->contains('blog:xml-feed')) {
            $feed = $this->cache->fetch('blog:xml-feed');
        } else {
            $feed = $this->generateXmlFeed();
            $this->cache->save('blog:xml-feed', $feed);
        }

        $response->getBody()->write($feed);

        return $response->withHeader('Content-Type', 'application/atom+xml')
            ->withHeader('Cache-Control', ['public', 'max-age=3600']);
    }

    public function generateXmlFeed()
    {
        $feed = new Feed();
        $feed->setTitle('xtreamwayz');
        $feed->setLink($this->generateUrl('home', [], true));
        $feed->setFeedLink($this->generateUrl('feed.xml', [], true), 'atom');
        $feed->addAuthor([
            'name' => 'Geert Eltink',
            'uri'  => 'https://xtreamwayz.com',
        ]);
        $feed->setDateModified(time());
        $feed->setCopyright(sprintf('Copyright (c) 2005-%s Geert Eltink. All Rights Reserved.', date('Y')));
        $feed->setDescription('A web developer\'s playground, notes and thoughts.');
        $feed->setId($this->generateUrl('home', [], true));

        $posts = array_slice(array_reverse($this->postRepository->findAll()), 0, 5);
        /** @var \App\Domain\Post\Post $post */
        foreach ($posts as $post) {
            $entry = $feed->createEntry();
            $entry->setTitle($post->getTitle());
            $entry->setLink($this->generateUrl('blog.post', ['id' => $post->getId()], true));
            $entry->setId($this->generateUrl('blog.post', ['id' => $post->getId()], true));
            $entry->setDateCreated($post->getPublished());
            if ($post->getModified()) {
                $entry->setDateModified($post->getModified());
            } else {
                $entry->setDateModified($post->getPublished());
            }
            $entry->setDescription($post->getSummary());
            $entry->setContent($post->getContent());
            $entry->addAuthor([
                'name' => 'Geert Eltink',
                'uri'  => 'https://xtreamwayz.com',
            ]);
            $feed->addEntry($entry);
        }

        return $feed->export('atom');
    }

    /**
     * @param string $route
     * @param array  $params
     * @param bool   $absoluteUrl
     *
     * @return string
     */
    public function generateUrl($route = null, array $params = [], $absoluteUrl = false)
    {
        $url = $this->urlHelper->generate($route, $params);

        if ($absoluteUrl !== true) {
            return $url;
        }

        return $this->generateServerUrl($url);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function generateServerUrl($path = null)
    {
        return $this->serverUrlHelper->generate($path);
    }
}
