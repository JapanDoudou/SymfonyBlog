<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use App\Factory\Entity\ArticleFactory;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
	/**
	 * @var \App\Factory\Entity\ArticleFactory
	 */
	private ArticleFactory $articleFactory;

	public function __construct(ArticleFactory $articleFactory)
    {
		$this->articleFactory = $articleFactory;
	}

	/**
	 * @throws \Exception
	 */
    public function loadData(ObjectManager $manager): void
    {
        /** @var User $user */
		$this->createMany(Article::class, self::NUMBER_OF_ARTICLES, self::ARTICLE_REF, function (Article $article, int $i) {
			$user = $this->getReference(self::USER_REF);
			$charNumber = random_int(700, 5000);
			$this->articleFactory->createOrUpdate(
				$article,
				"Article #$i : " . $this->faker->word,
				$this->faker->realText($charNumber),
				DateTimeImmutable::createFromMutable($this->faker->dateTime),
				$user
			);
		});
		$manager->flush();

	}

    public function getDependencies()
    {
        return array(
            UserFixtures::class
        );
    }
}
