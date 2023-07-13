<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @name \App\Repository\AbstractRepository
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
	public const ENTITY_CLASS_NAME = "";
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, static::ENTITY_CLASS_NAME);
	}
}