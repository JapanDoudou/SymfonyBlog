<?php

declare(strict_types=1);

namespace App\Pagination;

use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;
use Doctrine\ORM\Tools\Pagination\CountWalker;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use function count;

final class PaginatorFactory
{
	final public const PAGE_SIZE = 10;

	public function __construct(
		private readonly DoctrineQueryBuilder $queryBuilder
	) {
	}

	public function paginate(int $page = 1): Pagerfanta
	{
		$query = $this->queryBuilder->getQuery();
		/** @var array<string, mixed> $joinDqlParts */
		$joinDqlParts = $this->queryBuilder->getDQLPart('join');
		if (0 === count($joinDqlParts))
		{
			$query->setHint(CountWalker::HINT_DISTINCT, false);
		}
		/** @var array<string, mixed> $havingDqlParts */
		$havingDqlParts = $this->queryBuilder->getDQLPart('having');
		$useOutputWalkers = count($havingDqlParts ?: []) > 0;
		return (new Pagerfanta(new QueryAdapter(
			$query, true, $useOutputWalkers
		)))->setCurrentPage($page)
			->setMaxPerPage(self::PAGE_SIZE);
	}
}
