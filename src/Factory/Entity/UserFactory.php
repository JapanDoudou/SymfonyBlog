<?php

namespace App\Factory\Entity;

use App\Entity\User;
use App\Utils\Validator;
use RuntimeException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @name \App\Factory\Entity\UserFactory
 */
class UserFactory
{
	public function __construct(
		private readonly UserPasswordHasherInterface $hasher,
		private readonly Validator $validator
	){
	}

	public function createOrUpdate(
		?User $user,
		string $displayName,
		string $email,
		array $roles,
		string $password
	): User
	{
		$this->validateUserData($displayName, $password, $email, $roles);
		if ($user === null)
		{
			$user = new User();
		}
		$user->setDisplayName($displayName)
			->setEmail($email)
			->setRoles($roles);
		// See https://symfony.com/doc/5.4/security.html#registering-the-user-hashing-passwords
		$hashed = $this->hasher->hashPassword($user, $password);
		$user->setPassword($hashed);
		return $user;
	}

	private function validateUserData(
		string $username,
		string $plainPassword,
		string $email,
		array $roles = []
	): void {
		// first check if a user with the same username already exists.
		$existingUser = $this->validator->isValueInDatabase(User::class, 'displayName', $username);

		if ($existingUser) {
			throw new RuntimeException(sprintf('There is already a user registered with the "%s" username.', $username));
		}

		// validate password and email if is not this input means interactive.
		$this->validator->validatePassword($plainPassword);
		$this->validator->validateEmail($email);
		if (!empty($roles))
		{
			$this->validator->validateRoles($roles);
		}
		// check if a user with the same email already exists.
		$existingEmail =  $this->validator->isValueInDatabase(User::class, 'email', $email);
		if ($existingEmail) {
			throw new RuntimeException(sprintf('There is already a user registered with the "%s" email.', $email));
		}
	}
}