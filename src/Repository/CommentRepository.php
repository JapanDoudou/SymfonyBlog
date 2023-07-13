<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Helper\DoctrineHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends AbstractRepository
{
    public const ENTITY_CLASS_NAME = Comment::class;

    public function add(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Comment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param QueryBuilder $query
     * @param int $articleId
     * @param string $comAlias
     * @return QueryBuilder
     */
    public static function addArticleIdConstraint(
        QueryBuilder $query,
        int $articleId,
        string $comAlias = DoctrineHelper::ALIAS_COMMENT
    ): QueryBuilder {
        return self::addArticleIdWhere($query, $articleId, $comAlias);
    }

    /**
     * @param QueryBuilder $query
     * @param int $articleId
     * @param string $comAlias
     * @return QueryBuilder
     */
    public static function addArticleIdWhere(
        QueryBuilder $query,
        int $articleId,
        string $comAlias = DoctrineHelper::ALIAS_COMMENT
    ): QueryBuilder {
        $query->andWhere("$comAlias.article = :id")
            ->setParameter('id', $articleId);
        return $query;
    }
}
