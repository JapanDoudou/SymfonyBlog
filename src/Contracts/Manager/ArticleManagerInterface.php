<?php

namespace App\Contracts\Manager;

use App\Entity\Article;
use App\Entity\Tag;
use App\ViewModel\ArticleVm;
use Pagerfanta\PagerfantaInterface;

interface ArticleManagerInterface
{
	/**
	 * @param Article $article
	 * @param bool $flush
	 * @return void
	 */
	public function createOrUpdate(Article $article, bool $flush = true): void;

	/**
	 * @param int $page
	 * @return \Pagerfanta\PagerfantaInterface|ArticleVm[]
	 */
	public function getAllArticlesVmsPaginated(int $page = 1): PagerfantaInterface|array;
	/**
	 * @param string $slug
	 * @return ArticleVm|null
	 */
	public function getArticleVmBySlug(string $slug): ?ArticleVm;

	/**
	 * @param Tag $tag
	 * @param int $page
	 * @return \Pagerfanta\PagerfantaInterface
	 */
	public function getArticlesVmByTagPaginated(Tag $tag, int $page): PagerfantaInterface;

	public function findOneById(int $id): ArticleVm;
}
