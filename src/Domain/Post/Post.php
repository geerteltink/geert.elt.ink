<?php

declare(strict_types=1);

namespace App\Domain\Post;

use DateTime;
use function is_numeric;

class Post
{
    /** @var string */
    private $id;

    /** @var string */
    private $title;

    /** @var null|string */
    private $summary;

    /** @var null|string */
    private $content;

    /** @var array */
    private $tags;

    /** @var DateTime */
    private $published;

    /** @var null|DateTime */
    private $modified;

    /**
     * @param null|mixed $published
     * @param null|mixed $modified
     */
    public function __construct(
        string $id,
        string $title,
        ?string $summary = null,
        ?string $content = null,
        ?array $tags = null,
        $published = null,
        $modified = null
    ) {
        $this->id      = $id;
        $this->title   = $title;
        $this->summary = $summary;
        $this->content = $content;
        $this->tags    = $tags ?? [];
        $published     = $published ?? 'now';

        if (is_numeric($published)) {
            $this->published = (new DateTime())->setTimestamp((int) $published);
        } else {
            $this->published = new DateTime($published);
        }

        if (is_numeric($modified)) {
            $this->modified = (new DateTime())->setTimestamp((int) $modified);
        } elseif ($modified !== false && $modified !== null) {
            $this->modified = new DateTime($modified);
        }
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getTitle() : string
    {
        return $this->title;
    }

    public function getSummary() : ?string
    {
        return $this->summary;
    }

    public function getContent() : ?string
    {
        return $this->content;
    }

    public function getPublished() : DateTime
    {
        return $this->published;
    }

    public function getModified() : ?DateTime
    {
        return $this->modified;
    }

    public function getTags() : array
    {
        return $this->tags;
    }
}
