<?php

namespace App\Manager;

use App\Contracts\Manager\TagManagerInterface;
use App\Entity\Tag;
use App\Exception\Manager\ManagerException;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Pagerfanta\Pagerfanta;

final class TagManager implements TagManagerInterface
{
    private TagRepository $tagRepository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $repo = $entityManager->getRepository(Tag::class);
        if (!$repo instanceof TagRepository) {
			throw new ManagerException(
				Tag::class,
				TagRepository::class,
				$repo
			);
        }
        $this->tagRepository = $repo;
    }

    /**
     * @inheritDoc
     */
    public function createOrUpdate(Tag $tag, bool $flush = true): void
    {
        /** @var int|null $id */
        $id = $tag->getId();
        if ($id === null) {
            $this->entityManager->persist($tag);
        }
        if ($flush === true) {
            $this->entityManager->flush();
        }
    }

    /**
     * @inheritDoc
     */
    public function findAllTagsPaginated(int $page = 1): Pagerfanta
    {
        return $this->tagRepository->findAllTags($page);
    }

	/**
	 * {@inheritDoc}
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
    public function findOneBySlug(string $slug): Tag
    {
        $tag = $this->tagRepository->findOneBySlug($slug);
        if ($tag === null) {
            throw new InvalidArgumentException('There is no tag for this slug');
        }
        return $tag;
    }
}
