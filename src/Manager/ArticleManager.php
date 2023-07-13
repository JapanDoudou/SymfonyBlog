<?php

namespace App\Manager;

use App\Contracts\Manager\ArticleManagerInterface;
use App\Entity\Article;
use App\Entity\Tag;
use App\Exception\Manager\ManagerException;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use App\ViewModel\ArticleVm;
use Pagerfanta\PagerfantaInterface;

final class ArticleManager implements ArticleManagerInterface
{
	private ArticleRepository $articleRepo;

	public function __construct(private readonly EntityManagerInterface $entityManager)
	{
		$repo = $entityManager->getRepository(Article::class);
		if (!$repo instanceof ArticleRepository) {
			throw new ManagerException(
				Article::class,
				ArticleRepository::class,
				$repo
			);
		}
		$this->articleRepo = $repo;
	}

	/**
	 * @inheritDoc
	 */
	public function createOrUpdate(Article $article, bool $flush = true): void
	{
		/** @var int|null $id */
		$id = $article->getId();
		if ($id === null) {
			$this->entityManager->persist($article);
		}
		if ($flush === true) {
			$this->entityManager->flush();
		}
	}

	/**
	 * @param Article $entity
	 * @param bool $flush
	 * @return void
	 */
	public function remove(Article $entity, bool $flush = false): void
	{
		$this->entityManager->remove($entity);
		if ($flush)
		{
			$this->entityManager->flush();
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getAllArticlesVmsPaginated(int $page = 1): PagerfantaInterface|array
	{
		$articlesVm = $this->articleRepo->getAllArticlesVmsPaginated(true, $page);
		foreach ($articlesVm as $vm)
		{
			$tags = $this->articleRepo->findAllTagsByArticleId($vm->getId());
			$coms = $this->articleRepo->findAllCommentByArticleId($vm->getId());
			$vm->setTags($tags)
				->setComments($coms);
		}
		return $articlesVm;
	}

	/**
	 * @inheritDoc
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function getArticleVmBySlug(string $slug): ?ArticleVm
	{
		$vm = $this->articleRepo->getArticleVmBySlug($slug);
		if ($vm === null) {
			throw new InvalidArgumentException("Slug does not exist");
		}
		$tags = $this->articleRepo->findAllTagsByArticleId($vm->getId());
		$coms = $this->articleRepo->findAllCommentByArticleId($vm->getId());
		$vm->setTags($tags)
			->setComments($coms);
		return $vm;
	}

	/**
	 * @inheritDoc
	 */
	public function getArticlesVmByTagPaginated(Tag $tag, int $page): PagerfantaInterface
	{
		$articlesVm = $this->articleRepo->getAllArticlesVmsByTagPaginated($tag, true, $page);
		foreach ($articlesVm as $vm)
		{
			$tags = $this->articleRepo->findAllTagsByArticleId($vm->getId());
			$coms = $this->articleRepo->findAllCommentByArticleId($vm->getId());
			$vm->setTags($tags)
				->setComments($coms);
		}
		return $articlesVm;
	}

	public function findOneById(int $id): ArticleVm
	{
		return $this->articleRepo->findOneById($id);
	}
}
