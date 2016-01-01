<?php

namespace App\Action;

use Domain\Post\PostRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Doctrine\Common\Cache\Cache;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Router;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Feed\Writer\Feed;

class BlogXmlFeedAction
{
    private $template;

    private $cache;

    private $postRepository;

    private $urlHelper;

    private $serverUrlHelper;

    public function __construct(
        TemplateRendererInterface $template,
        Cache $cache,
        PostRepository $postRepository,
        UrlHelper $urlHelper,
        ServerUrlHelper $serverUrlHelper
    ) {
        $this->template = $template;
        $this->cache = $cache;
        $this->postRepository = $postRepository;
        $this->urlHelper = $urlHelper;
        $this->serverUrlHelper = $serverUrlHelper;
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
        if ($this->cache->contains('blog.xml.feed')) {
            $feed = $this->cache->fetch('blog.xml.feed');
        } else {
            $feed = $this->generateXmlFeed();
            $this->cache->save('blog.xml.feed', $feed);
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

        $posts = array_slice(array_reverse($this->postRepository->findAll()), 0, 5);
        /** @var \Domain\Post\Post $post */
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

    public function generateUrl($route = null, array $params = [], $absoluteUrl = false)
    {
        $url = $this->urlHelper->generate($route, $params);

        if ($absoluteUrl !== true) {
            return $url;
        }

        return $this->generateServerUrl($url);
    }

    public function generateServerUrl($path = null)
    {
        return $this->serverUrlHelper->generate($path);
    }
}
