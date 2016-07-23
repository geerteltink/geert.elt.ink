<?php

namespace App\Domain\Post;

use DateTime;

class Post
{
    private $id;

    private $title;

    private $summary;

    private $content;

    private $tags = [];

    private $published;

    private $modified;

    public function __construct(
        $id,
        $title,
        $summary = null,
        $content = null,
        array $tags = [],
        $published = 'now',
        $modified = null
    ) {
        $this->id      = $id;
        $this->title   = $title;
        $this->summary = $summary;
        $this->content = $content;
        $this->tags    = $tags;

        if (is_numeric($published)) {
            $this->published = (new DateTime())->setTimestamp($published);
        } else {
            $this->published = new DateTime($published);
        }

        if (is_numeric($modified)) {
            $this->modified = (new DateTime())->setTimestamp($modified);
        } elseif ($modified !== false || $modified !== null) {
            $this->modified = new DateTime($modified);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getPublished()
    {
        return $this->published;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function getTags()
    {
        return $this->tags;
    }
}
