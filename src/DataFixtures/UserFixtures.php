<?php

namespace App\DataFixtures;

use App\Contracts\Manager\UserManagerInterface;
use App\Factory\Entity\UserFactory;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends AbstractBaseFixtures
{
	/**
	 * @var \App\Factory\Entity\UserFactory
	 */
	private UserFactory $userFactory;
	/**
	 * @var \App\Contracts\Manager\UserManagerInterface
	 */
	private UserManagerInterface $userManager;

	public function __construct(
		UserFactory $userFactory,
		UserManagerInterface $userManager
	)
	{
		$this->userFactory = $userFactory;
		$this->userManager = $userManager;
	}

	public function loadData(ObjectManager $manager): void
	{
		$user = $this->userFactory->createOrUpdate(
			null,
			"Super Admin",
			"admin@admin.fr",
			['ROLE_ADMIN'],
			"1234admin"
		);
		$this->userManager->createOrUpdate($user);
		$this->addReference(self::USER_REF, $user);
	}
}
