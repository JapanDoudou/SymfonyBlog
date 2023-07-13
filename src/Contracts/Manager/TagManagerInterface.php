<?php

namespace App\Contracts\Manager;

use App\Entity\Tag;
use Pagerfanta\Pagerfanta;

interface TagManagerInterface
{
    /**
     * @param Tag $tag
     * @param bool $flush
     * @return void
     */
    public function createOrUpdate(Tag $tag, bool $flush = true): void;

    /**
     * @param int $page
     * @return Pagerfanta
     */
    public function findAllTagsPaginated(int $page = 1): Pagerfanta;

    /**
     * @param string $slug
     * @return Tag
     */
    public function findOneBySlug(string $slug): Tag;
}
