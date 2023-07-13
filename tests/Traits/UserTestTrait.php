<?php

namespace App\Tests\Traits;

use App\Enum\User\Role;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @name \App\Tests\UserTestTrait
 */
trait UserTestTrait
{
	protected function assertUserCreated(array $data): void
	{
		/** @var UserRepository $repository */
		$repository = self::getContainer()->get(UserRepository::class);

		/** @var UserPasswordHasherInterface $passwordHasher */
		$passwordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);

		$user = $repository->findOneByEmail($data['email']);

		self::assertNotNull($user);
		self::assertSame($data['displayName'], $user->getDisplayName());
		self::assertTrue($passwordHasher->isPasswordValid($user, $data['password']));
		self::assertSame($data['roles'], $user->getRoles());
	}

	protected function getBaseUserDataArray(string $suffix, bool $isAdmin): array
	{
		$admin = $isAdmin ? '_admin' : '';
		$suffix .= $admin;
		$roles = [Role::ROLE_USER->value];
		if ($isAdmin)
		{
			$roles = [Role::ROLE_USER->value, Role::ROLE_ADMIN->value];
		}
		return [
			'displayName' => "t_$suffix",
			'password' => '1234admin',
			'email' => "test_$suffix@test.com",
			'roles' => $roles
		];
	}
}