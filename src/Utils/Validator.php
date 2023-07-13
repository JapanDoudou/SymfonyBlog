<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Utils;

use App\Enum\User\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use function Symfony\Component\String\u;

/**
 * This class is used to provide an example of integrating simple classes as
 * services into a Symfony application.
 * See https://symfony.com/doc/current/service_container.html#creating-configuring-services-in-the-container.
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
final class Validator
{
	public function __construct(
		private readonly EntityManagerInterface $_em
	)
	{
	}

	public function isValueInDatabase(string $class, string $criteria, string $value): bool
	{
		return $this->_em->getRepository($class)->count([$criteria => $value]) > 0;
	}

	public function validateDisplayName(?string $username): string
	{
		if (empty($username)) {
			throw new InvalidArgumentException('The username can not be empty.');
		}

		if (1 !== preg_match('/^[a-z_]+$/', $username)) {
			throw new InvalidArgumentException('The username must contain only lowercase latin characters and underscores.');
		}

		return $username;
	}

	public function validatePassword(?string $plainPassword): string
	{
		if (empty($plainPassword)) {
			throw new InvalidArgumentException('The password can not be empty.');
		}

		if (u($plainPassword)->trim()->length() < 6) {
			throw new InvalidArgumentException('The password must be at least 6 characters long.');
		}

		return $plainPassword;
	}

	public function validateEmail(?string $email): string
	{
		if (empty($email)) {
			throw new InvalidArgumentException('The email can not be empty.');
		}

		if (null === u($email)->indexOf('@')) {
			throw new InvalidArgumentException("The email should look like a real email. $email given");
		}

		return $email;
	}

	public function validateRoles(array $roles): array
	{
		foreach ($roles as $role)
		{
			Role::exist($role);
		}
		return $roles;
	}
}
