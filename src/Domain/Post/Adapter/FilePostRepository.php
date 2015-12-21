<?php

namespace Domain\Post\Adapter;

use Domain\Post\Post;
use Domain\Post\PostRepository;
use Mni\FrontYAML\Parser;
use Zend\Stdlib\Glob;

class FilePostRepository implements PostRepository
{
    /**
     * @inheritdoc
     */
    public function find($id)
    {
        $file = sprintf('data/posts/%s.md', $id);
        if (!is_file($file)) {
            return null;
        }

        $parser = new Parser();
        $document = $parser->parse(file_get_contents($file));
        $meta = $document->getYAML();

        $post = new Post(
            $meta['id'],
            $meta['title'],
            $meta['summary'],
            $document->getContent(),
            $meta['tags'],
            $meta['published'],
            $meta['modified']
        );

        return $post;
    }

    /**
     * @inheritdoc
     */
    public function findAll()
    {
        $parser = new Parser();

        $posts = [];
        foreach (Glob::glob('data/posts/*.md', Glob::GLOB_BRACE) as $file) {
            $document = $parser->parse(file_get_contents($file));
            $meta = $document->getYAML();

            $post = new Post(
                $meta['id'],
                $meta['title'],
                $meta['summary'],
                $document->getContent(),
                $meta['tags'],
                $meta['published'],
                $meta['modified']
            );

            $posts[] = $post;
        }

        return $posts;
    }
}
