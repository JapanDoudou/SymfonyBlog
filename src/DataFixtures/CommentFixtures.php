<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Comment;
use App\Factory\Entity\CommentFactory;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
	public function __construct(private readonly CommentFactory $commentFactory)
	{
	}

    public function loadData(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::NUMBER_OF_ARTICLES; $i++) {
            $numberOfComment = random_int(0, self::NUMBER_MAX_OF_COMMENT);
            /** @var Article $article */
            $article = $this->getReference(self::ARTICLE_REF . $i, Article::class);
			$ref = self::ARTICLE_REF . $i . self::COMMENT_REF;
			$this->createMany(Comment::class, self::NUMBER_MAX_OF_COMMENT, $ref, function(Comment $comment, int $i) use ($article) {
				$charNumber = random_int(50, 500);
				$this->commentFactory->createOrUpdate(
					$article,
					$this->faker->userName,
					$this->faker->realText($charNumber),
					DateTimeImmutable::createFromMutable($this->faker->dateTime),
					$comment
				);
			});
		}
		$manager->flush();
	}

    public function getDependencies()
    {
        return array(
            ArticleFixtures::class
        );
    }
}
