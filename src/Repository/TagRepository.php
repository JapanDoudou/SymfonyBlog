<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Helper\DoctrineHelper;
use App\Pagination\PaginatorFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Pagerfanta;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]	findAll()
 * @method Tag[]	findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends AbstractRepository
{
	public const ENTITY_CLASS_NAME = Tag::class;

	public function add(Tag $entity, bool $flush = false): void
	{
		$this->getEntityManager()->persist($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	public function remove(Tag $entity, bool $flush = false): void
	{
		$this->getEntityManager()->remove($entity);

		if ($flush) {
			$this->getEntityManager()->flush();
		}
	}

	/**
	 * @param int $page
	 * @return Pagerfanta
	 */
	public function findAllTags(int $page = 1): Pagerfanta
	{
		$tAlias = DoctrineHelper::ALIAS_TAG;
		$query = $this->createQueryBuilder($tAlias);
		//		$query->orderBy("$tAlias.intitule", DoctrineHelper::ORDER_ASC);
		return (new PaginatorFactory($query))->paginate($page);
	}

	/**
	 * @param string $slug
	 * @return Tag|null
	 * @throws NonUniqueResultException
	 */
	public function findOneBySlug(string $slug): ?Tag
	{
		$tAlias = DoctrineHelper::ALIAS_TAG;
		$query = $this->createQueryBuilder($tAlias);
		self::addSlugConstraint($query, $slug, $tAlias);
		$query->setMaxResults(1);
		return $query->getQuery()->getOneOrNullResult();
	}

	/**
	 * @param QueryBuilder $query
	 * @param string $slug
	 * @param string $tAlias
	 * @return QueryBuilder
	 */
	public static function addSlugConstraint(
		QueryBuilder $query,
		string $slug,
		string $tAlias = DoctrineHelper::ALIAS_TAG
	): QueryBuilder {
		return self::addSlugWhere($query, $slug, $tAlias);
	}

	/**
	 * @param QueryBuilder $query
	 * @param string $slug
	 * @param string $tAlias
	 * @return QueryBuilder
	 */
	public static function addSlugWhere(
		QueryBuilder $query,
		string $slug,
		string $tAlias = DoctrineHelper::ALIAS_TAG
	): QueryBuilder {
		$query->andWhere("$tAlias.slug = :slug")
			->setParameter("slug", $slug);
		return $query;
	}

	/**
	 * @param QueryBuilder $query
	 * @param int $articleId
	 * @param string $tAlias
	 * @param string $aAlias
	 * @return QueryBuilder
	 */
	public static function addArticleIdConstraint(
		QueryBuilder $query,
		int $articleId,
		string $tAlias = DoctrineHelper::ALIAS_TAG,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE
	): QueryBuilder {
		if (!DoctrineHelper::hasAlias($query, $aAlias)) {
			self::addArticleJoin($query, $tAlias, $aAlias, Join::INNER_JOIN);
		}
		return ArticleRepository::addArticleIdWhere($query, $articleId, $aAlias);
	}

	/**
	 * @param QueryBuilder $query
	 * @param string $tAlias
	 * @param string $aAlias
	 * @param string $joinType
	 * @return QueryBuilder
	 */
	public static function addArticleJoin(
		QueryBuilder $query,
		string $tAlias = DoctrineHelper::ALIAS_TAG,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE,
		string $joinType = Join::LEFT_JOIN
	): QueryBuilder {
		$relation = "$tAlias.articles";
		$joinType = $joinType === Join::LEFT_JOIN ? 'leftJoin' : 'innerJoin';
		return $query->$joinType($relation, $aAlias);
	}
}
