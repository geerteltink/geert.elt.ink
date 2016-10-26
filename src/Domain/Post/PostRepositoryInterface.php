<?php

declare(strict_types = 1);

namespace App\Domain\Post;

interface PostRepositoryInterface
{
    /**
     * @param $id
     *
     * @return Post|null
     */
    public function find($id): Post;

    /**
     * @return array|Post[]
     */
    public function findAll(): array;
}
