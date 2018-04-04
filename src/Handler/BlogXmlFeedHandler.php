<?php

declare(strict_types=1);

namespace App\Handler;

use App\Domain\Post\Post;
use App\Domain\Post\PostRepositoryInterface;
use Doctrine\Common\Cache\Cache;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Feed\Writer\Feed;
use function array_reverse;
use function array_slice;
use function date;
use function sprintf;
use function time;

class BlogXmlFeedHandler implements RequestHandlerInterface
{
    /** @var Cache */
    private $cache;

    /** @var PostRepositoryInterface */
    private $postRepository;

    /** @var UrlHelper */
    private $urlHelper;

    /** @var ServerUrlHelper */
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

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $feed = null;

        if ($this->cache->contains('blog:xml-feed')) {
            $feed = $this->cache->fetch('blog:xml-feed');
        }

        if (! $feed) {
            $feed = $this->generateXmlFeed();
            $this->cache->save('blog:xml-feed', $feed);
        }

        $response = new Response();
        $response->getBody()->write($feed);

        return $response
            ->withHeader('Content-Type', 'application/atom+xml')
            ->withHeader('Cache-Control', ['public', 'max-age=3600']);
    }

    public function generateXmlFeed() : string
    {
        $feed = new Feed();
        $feed->setTitle('xtreamwayz');
        $feed->setLink($this->generateUrl('home', [], true));
        $feed->setFeedLink($this->generateUrl('feed', [], true), 'atom');
        $feed->addAuthor([
            'name' => 'Geert Eltink',
            'uri'  => 'https://xtreamwayz.com',
        ]);
        $feed->setDateModified(time());
        $feed->setCopyright(sprintf('Copyright (c) 2005-%s Geert Eltink. All Rights Reserved.', date('Y')));
        $feed->setDescription('A web developer\'s playground, notes and thoughts.');
        $feed->setId($this->generateUrl('home', [], true));

        $posts = array_slice(array_reverse($this->postRepository->findAll()), 0, 5);
        /** @var Post $post */
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

    public function generateUrl(?string $route = null, ?array $params = null, ?bool $absoluteUrl = null) : string
    {
        $url = $this->urlHelper->generate($route, $params ?? []);

        if ($absoluteUrl !== true) {
            return $url;
        }

        return $this->generateServerUrl($url);
    }

    public function generateServerUrl(?string $path = null) : string
    {
        return $this->serverUrlHelper->generate($path);
    }
}
