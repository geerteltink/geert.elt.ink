<?php

namespace App\Domain\Post;

interface PostRepositoryInterface
{
    /**
     * @param $id
     *
     * @return Post|null
     */
    public function find($id);

    /**
     * @return array|Post[]
     */
    public function findAll();
}
