<?php

namespace App\Manager;

use App\Contracts\Manager\UserManagerInterface;
use App\Entity\User;
use App\Exception\Manager\ManagerException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @name \App\Manager\UserManager
 */
final class UserManager implements UserManagerInterface
{
    private UserRepository $userRepository;

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
        $repo = $entityManager->getRepository(User::class);
        if (!$repo instanceof UserRepository) {
			throw new ManagerException(
				User::class,
				UserRepository::class,
				$repo
			);
        }
        $this->userRepository = $repo;
    }

    public function createOrUpdate(User $user, bool $flush = true): void
    {
        /** @var int|null $id */
        $id = $user->getId();
        if ($id === null) {
            $this->entityManager->persist($user);
        }
        if ($flush === true) {
            $this->entityManager->flush();
        }
    }

	public function findOneById(int $id): User
	{
		return $this->userRepository->findOneBy(array('id' => $id));
	}
}
