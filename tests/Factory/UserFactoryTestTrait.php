<?php

namespace App\Tests\Factory;

use App\Contracts\Manager\UserManagerInterface;
use App\Factory\Entity\UserFactory;
use App\Tests\AbstractTestCase;
use App\Tests\Traits\UserTestTrait;

/**
 * @name \App\Tests\Factory\UserFactoryTestTrait
 */
class UserFactoryTestTrait extends AbstractTestCase
{
	use UserTestTrait;

	/**
	 * @var UserFactory
	 */
	private UserFactory $factory;
	/**
	 * @var UserManagerInterface
	 */
	private UserManagerInterface $manager;

	/**
	 * @throws \Exception
	 */
	public function setup(
	): void {
		AbstractTestCase::setUp();
		$this->factory = self::getContainer()->get(UserFactory::class);
		$this->manager = self::getContainer()->get(UserManagerInterface::class);
	}

	public function testCreateOrUpdate(): void
	{
		$userData = $this->getBaseUserDataArray('user_factory', true);
		$user = $this->factory->createOrUpdate(
			null,
			$userData['displayName'],
			$userData['email'],
			$userData['roles'],
			$userData['password']
		);
		$this->manager->createOrUpdate($user);
		$this->assertUserCreated($userData);
	}
}