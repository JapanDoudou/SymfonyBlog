<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Tag;
use App\Factory\Entity\TagFactory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class TagFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
	public const TAG_FIXTURES = [
		0 => [
			'intitule' => 'test',
			'slug' => 'test',
			'color' => '#592169'
		],
		1 => [
			'intitule' => 'lorem',
			'slug' => 'lorem',
			'color' => '#d45dca'
		],
		2 => [
			'intitule' => 'chat',
			'slug' => 'chat',
			'color' => '#e61ae0'
		],
		3 => [
			'intitule' => 'chien',
			'slug' => 'chien',
			'color' => '#9b3c42'
		],
		4 => [
			'intitule' => 'kuro',
			'slug' => 'kuro',
			'color' => '#ffffff'
		]
	];
	public function __construct(
		private readonly SluggerInterface $slugger,
		private readonly TagFactory $tagFactory
	)
	{
	}

	public function loadData(ObjectManager $manager): void
	{
		$this->createMany(
			Tag::class,
			self::NUMBER_OF_TAGS,
			self::TAG_REF,
			function(Tag $tag, int $i){
				if ($i <= 4)
				{
					$intitule = self::TAG_FIXTURES[$i]['intitule'];
					$slug = self::TAG_FIXTURES[$i]['slug'];
					$color = self::TAG_FIXTURES[$i]['color'];
				} else {
					$intitule = $this->faker->word;
					$slug = $this->slugger->slug($intitule);
					$color = $this->faker->hexColor;
				}
				//Tag Factory
				$this->tagFactory->createOrUpdate($intitule,$slug,$color, $tag);
				if ($i === 0) {
					$numberOfArticles = self::TAG_ARTICLES_TEST_COUNT;
				} else {
					$numberOfArticles = random_int(1, self::NUMBER_OF_ARTICLES);
				}
				for ($ii = 0; $ii < $numberOfArticles; $ii++) {
					/** @var Article $article */
					$article = $this->getReference(self::ARTICLE_REF . $ii);
					$tag->addArticle($article);
				}
		});
		$manager->flush();
	}

	public function getDependencies()
	{
		return array(
			ArticleFixtures::class
		);
	}
}
