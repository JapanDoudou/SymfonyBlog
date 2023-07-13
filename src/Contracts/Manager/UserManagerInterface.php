<?php

namespace App\Contracts\Manager;

use App\Entity\User;

interface UserManagerInterface
{
	public function createOrUpdate(User $user, bool $flush = true): void;

	public function findOneById(int $id): User;
}