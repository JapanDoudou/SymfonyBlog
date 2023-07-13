<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Tag;
use App\Entity\User;
use App\Helper\DoctrineHelper;
use App\Pagination\PaginatorFactory;
use App\ViewModel\ArticleVm;
use App\ViewModel\CommentVm;
use App\ViewModel\TagVm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\PagerfantaInterface;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends AbstractRepository
{
	public const ENTITY_CLASS_NAME = Article::class;

	/**
	 * @param bool $isShortContext
	 * @param int $page
	 * @return PagerfantaInterface
	 */
	public function getAllArticlesVmsPaginated(bool $isShortContext = false, int $page = 1): PagerfantaInterface
	{
		$aAlias = DoctrineHelper::ALIAS_ARTICLE;
		$query = $this->_em->createQueryBuilder()
			->from(Article::class, $aAlias);
		self::addArticleVMSelect($query, $aAlias, $isShortContext);
		self::addDefaultConstraint($query, false, $aAlias);
		return (new PaginatorFactory($query))->paginate($page);
	}

	/**
	 * @param \App\Entity\Tag $tag
	 * @param bool $isShortContext
	 * @param int $page
	 * @return PagerfantaInterface
	 */
	public function getAllArticlesVmsByTagPaginated(Tag $tag, bool $isShortContext, int $page = 1): PagerfantaInterface
	{
		$aAlias = DoctrineHelper::ALIAS_ARTICLE;
		$query = $this->_em->createQueryBuilder()
			->from(Article::class, $aAlias);
		self::addArticleVMSelect($query, $aAlias, $isShortContext);
		self::addDefaultConstraint($query, false, $aAlias);
		$this->addTagConstraint($query, $tag, $aAlias);
		return (new PaginatorFactory($query))->paginate($page);
	}

	/**
	 * @param QueryBuilder $query
	 * @param int $articleId
	 * @param string $aAlias
	 * @return QueryBuilder
	 */
	public static function addArticleIdWhere(
		QueryBuilder $query,
		int $articleId,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE
	): QueryBuilder
	{
		$query->andWhere("$aAlias.id = :id")
			->setParameter('id', $articleId);
		return $query;
	}

	/**
	 * @param bool $isShortContext
	 * @return Collection
	 */

	/**
	 * @param QueryBuilder $query
	 * @param string $aAlias
	 * @param bool $isShortContext
	 * @return QueryBuilder
	 */
	private static function addArticleVMSelect(
		QueryBuilder $query,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE,
		bool $isShortContext = false
	): QueryBuilder {
		$userAlias = DoctrineHelper::ALIAS_USER;
		$vm = ArticleVm::class;
		$query->select("NEW $vm(" .
			"$aAlias.id, " .
			"(" . (int)$isShortContext . "), " .
			"$aAlias.slug, " .
			"$aAlias.title, " .
			"$aAlias.createdAt, " .
			"$aAlias.content, " .
			"$userAlias.displayName" .
			")");
		self::addUserConstraint($query, null, $aAlias, $userAlias);
		return $query;
	}

	/**
	 * @param QueryBuilder $query
	 * @param User|null $user
	 * @param string $aAlias
	 * @param string $userAlias
	 * @return QueryBuilder
	 */
	public static function addUserConstraint(
		QueryBuilder $query,
		User $user = null,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE,
		string $userAlias = DoctrineHelper::ALIAS_USER
	): QueryBuilder
	{
		if (!DoctrineHelper::hasAlias($query, $userAlias))
		{
			self::addUserJoin($query, $aAlias, $userAlias, Join::INNER_JOIN);
		}
		if ($user !== null)
		{
			self::addUserWhere($query, $user, $aAlias);
		}
		return $query;
	}

	/**
	 * @param QueryBuilder $query
	 * @param string $aAlias
	 * @param string $userAlias
	 * @param string $joinType
	 * @return QueryBuilder
	 */
	public static function addUserJoin(
		QueryBuilder $query,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE,
		string $userAlias = DoctrineHelper::ALIAS_USER,
		string $joinType = Join::LEFT_JOIN
	): QueryBuilder
	{
		$relation = "$aAlias.user";
		$joinType = $joinType === Join::LEFT_JOIN ? 'leftjoin' : 'innerjoin';
		$query->$joinType($relation, $userAlias);
		return $query;
	}

	/**
	 * @param QueryBuilder $query
	 * @param User $user
	 * @param string $aAlias
	 * @return QueryBuilder
	 */
	public static function addUserWhere(
		QueryBuilder $query,
		User $user,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE,
	): QueryBuilder
	{
		$query->andWhere("$aAlias.user = :user")
			->setParameter('user', $user);
		return $query;
	}

	/**
	 * @param QueryBuilder $query
	 * @param bool $isSingleArticleContext
	 * @param string $aAlias
	 * @return QueryBuilder
	 */
	public static function addDefaultConstraint(
		QueryBuilder $query,
		bool $isSingleArticleContext = false,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE
	): QueryBuilder
	{
		self::addIsPublishedWhere($query, true, $aAlias);
		if ($isSingleArticleContext === false)
		{
			self::addOrderWhere($query);
		}
		return $query;
	}

	/**
	 * @param QueryBuilder $query
	 * @param bool $isPublished
	 * @param string $aAlias
	 * @return QueryBuilder
	 */
	public static function addIsPublishedWhere(
		QueryBuilder $query,
		bool $isPublished = true,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE
	): QueryBuilder
	{
		$query->andWhere("$aAlias.isPublished = " . (int)$isPublished);
		return $query;
	}

	/**
	 * @param QueryBuilder $query
	 * @param string $field
	 * @param string $order
	 * @param string $aAlias
	 * @return QueryBuilder
	 */
	public static function addOrderWhere(
		QueryBuilder $query,
		string $field = "createdAt",
		string $order = DoctrineHelper::ORDER_DESC,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE
	): QueryBuilder
	{
		$query->addOrderBy("$aAlias.$field", $order);
		return $query;
	}

	/**
	 * Allow you to make
	 * $query
	 *    ->innerJoin("$aAlias.tags", $tagAlias)
	 *    ->where("$tagAlias.id = :tag_id")
	 *    ->setParameter('tag_id', $tag->getId());
	 * @param QueryBuilder $query
	 * @param \App\Entity\Tag|null $tag
	 * @param string $aAlias
	 * @param string $tagAlias
	 * @return QueryBuilder
	 */
	public function addTagConstraint(
		QueryBuilder $query,
		Tag $tag = null,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE,
		string $tagAlias = DoctrineHelper::ALIAS_TAG
	): QueryBuilder
	{
		if (!DoctrineHelper::hasAlias($query, $tagAlias))
		{
			self::addTagJoin($query, $aAlias, $tagAlias, Join::INNER_JOIN);
		}
		if ($tag !== null)
		{
			self::addTagWhere($query, $tag, $tagAlias);
		}
		return $query;
	}

	/**
	 * @param QueryBuilder $query
	 * @param string $aAlias
	 * @param string $tAlias
	 * @param string $joinType
	 * @return QueryBuilder
	 */
	public static function addTagJoin(
		QueryBuilder $query,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE,
		string $tAlias = DoctrineHelper::ALIAS_TAG,
		string $joinType = Join::LEFT_JOIN
	): QueryBuilder
	{
		$joinType = $joinType === Join::LEFT_JOIN ? 'leftJoin' : 'innerJoin';
		$query->$joinType("$aAlias.tags", $tAlias);
		return $query;
	}

	/**
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @param \App\Entity\Tag $tag
	 * @param string $tagAlias
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public static function addTagWhere(
		QueryBuilder $qb,
		Tag $tag,
		string $tagAlias = DoctrineHelper::ALIAS_TAG
	): QueryBuilder
	{
		$tagId = 'tag_id';
		$qb->where("$tagAlias.id = :$tagId");
		$qb->setParameter($tagId, $tag->getId());
		return $qb;
	}

	/**
	 * @param string $slug
	 * @return ArticleVm|null
	 * @throws NonUniqueResultException
	 */
	public function getArticleVmBySlug(string $slug): ?ArticleVm
	{
		$aAlias = DoctrineHelper::ALIAS_ARTICLE;
		$query = $this->_em->createQueryBuilder()
			->from(Article::class, $aAlias);
		self::addArticleVMSelect($query, $aAlias);
		self::addDefaultConstraint($query, true, $aAlias);
		self::addSlugConstraint($query, $slug, $aAlias);
		$query->setMaxResults(1);
		return $query->getQuery()->getOneOrNullResult();
	}

	/**
	 * @param QueryBuilder $query
	 * @param string $slug
	 * @param string $aAlias
	 * @return QueryBuilder
	 */
	public static function addSlugConstraint(
		QueryBuilder $query,
		string $slug,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE
	): QueryBuilder
	{
		return self::addSlugWhere($query, $slug, $aAlias);
	}

	/**
	 * @param QueryBuilder $query
	 * @param string $slug
	 * @param string $aAlias
	 * @return QueryBuilder
	 */
	public static function addIdConstraint(
		QueryBuilder $query,
		int $id,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE
	): QueryBuilder
	{
		$query->andWhere("$aAlias.id = :id")
			->setParameter('id', $id);
		return $query;
	}

	/**
	 * @param QueryBuilder $query
	 * @param string $slug
	 * @param string $aAlias
	 * @return QueryBuilder
	 */
	public static function addSlugWhere(
		QueryBuilder $query,
		string $slug,
		string $aAlias = DoctrineHelper::ALIAS_ARTICLE
	): QueryBuilder
	{
		$query->andWhere("$aAlias.slug = :slug")
			->setParameter('slug', $slug);
		return $query;
	}

	/**
	 * @param int $articleId
	 * @return ArrayCollection|TagVm
	 */
	public function findAllTagsByArticleId(int $articleId): ArrayCollection|array
	{
		$tAlias = DoctrineHelper::ALIAS_TAG;
		$vm = TagVm::class;
		$query = $this->_em->createQueryBuilder();
		$query->select("NEW $vm(" .
			"$tAlias.slug, " .
			"$tAlias.intitule, " .
			"$tAlias.color" .
			")")
			->from(Tag::class, $tAlias);
		TagRepository::addArticleIdConstraint($query, $articleId, $tAlias);
		return new ArrayCollection($query->getQuery()->getResult());
	}

	/**
	 * @param int $articleId
	 * @return ArrayCollection|CommentVm[]
	 */
	public function findAllCommentByArticleId(int $articleId): ArrayCollection|array
	{
		$comAlias = DoctrineHelper::ALIAS_COMMENT;
		$vm = CommentVm::class;
		$query = $this->_em->createQueryBuilder();
		$query->select("NEW $vm(" .
			"$comAlias.username, " .
			"$comAlias.content, " .
			"$comAlias.createdAt " .
			")")
			->from(Comment::class, $comAlias);
		CommentRepository::addArticleIdConstraint($query, $articleId, $comAlias);
		return new ArrayCollection($query->getQuery()->getResult());
	}

	public function findOneById(int $id)
	{
		$aAlias = DoctrineHelper::ALIAS_ARTICLE;
		$query = $this->_em->createQueryBuilder()
			->from(Article::class, $aAlias);
		self::addArticleVMSelect($query, $aAlias);
		self::addDefaultConstraint($query, true, $aAlias);
		self::addIdConstraint($query, $id, $aAlias);
		$query->setMaxResults(1);
		return $query->getQuery()->getOneOrNullResult();
	}

}
