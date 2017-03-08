<?php

declare(strict_types = 1);

namespace App\Domain\Post\Adapter;

use App\Domain\Post\Post;
use App\Domain\Post\PostRepositoryInterface;
use Michelf\MarkdownExtra as MarkdownParser;
use Symfony\Component\Yaml\Parser as YamlParser;
use Zend\Stdlib\Glob;

class FilePostRepository implements PostRepositoryInterface
{
    /**
     * @var YamlParser
     */
    private $yamlParser;

    /**
     * @var MarkdownParser
     */
    private $markdownParser;

    private $regex;

    public function __construct(YamlParser $yamlParser = null, MarkdownParser $markdownParser = null)
    {
        $this->yamlParser     = $yamlParser ?? new YamlParser();
        $this->markdownParser = $markdownParser ?? new MarkdownParser();

        $this->markdownParser->code_class_prefix = 'language-';
        $this->regex                             = '~^('
            . implode('|', array_map('preg_quote', ['---'])) # $matches[1] start separator
            . "){1}[\r\n|\n]*(.*?)[\r\n|\n]+("               # $matches[2] between separators
            . implode('|', array_map('preg_quote', ['---'])) # $matches[3] end separator
            . "){1}[\r\n|\n]*(.*)$~s";                       # $matches[4] document content
    }

    /**
     * @inheritdoc
     */
    public function find($id)
    {
        $file = sprintf('data/posts/%s.md', $id);
        if (! is_file($file)) {
            return null;
        }

        return $this->parse(file_get_contents($file));
    }

    /**
     * @inheritdoc
     */
    public function findAll(): array
    {
        $posts = [];
        foreach (Glob::glob('data/posts/*.md', Glob::GLOB_BRACE) as $file) {
            $posts[] = $this->parse(file_get_contents($file));
        }

        return $posts;
    }

    private function parse($str): Post
    {
        if (! preg_match($this->regex, $str, $matches) === 1) {
            throw new \DomainException('Invalid markdown format');
        }

        $meta = trim($matches[2]) !== '' ? $this->yamlParser->parse(trim($matches[2])) : null;
        $str  = ltrim($matches[4]);

        return new Post(
            $meta['id'],
            $meta['title'],
            $meta['summary'],
            $this->markdownParser->transform($str),
            $meta['tags'],
            $meta['published'],
            $meta['modified']
        );
    }
}
