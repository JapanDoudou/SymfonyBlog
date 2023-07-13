<?php

namespace App\Exception\Manager;

use Doctrine\ORM\EntityRepository;
use InvalidArgumentException;
use Throwable;

/**
 * @name \App\Exception\Manager\ManagerException
 */
class ManagerException extends InvalidArgumentException
{
 public function __construct(
	 string $class,
	 string $repoClass,
	 EntityRepository $currentRepo,
	 ?Throwable $previous = null
 ) {
	 $currentRepoClass = get_class($currentRepo);
	 $message = "The repository class for $class must be $repoClass and given $currentRepoClass!" .
		 "Maybe look the 'repositoryClass' declaration on $class ?";
	 parent::__construct($message, 0, $previous);
 }
}