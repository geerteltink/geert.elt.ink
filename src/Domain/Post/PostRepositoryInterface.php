<?php

declare(strict_types=1);

namespace App\Domain\Post;

interface PostRepositoryInterface
{
    public function find(string $id) : ?Post;

    /**
     * @return array|Post[]
     */
    public function findAll() : array;
}
