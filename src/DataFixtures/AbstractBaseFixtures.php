<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class AbstractBaseFixtures extends Fixture
{
    public const NUMBER_OF_ARTICLES = 100;
    public const NUMBER_MAX_OF_COMMENT = 5;
    public const NUMBER_OF_TAGS = 10;
    public const USER_REF = 'user';
    public const ARTICLE_REF = 'article_';
    public const COMMENT_REF = '_comment_';
    public const TAG_REF = 'tag_';
	public const TAG_ARTICLES_TEST_COUNT = 33;
	/**
	 * @var \Doctrine\Persistence\ObjectManager
	 */
	private ObjectManager $manager;
	/**
	 * @var Generator
	 */
	public Generator $faker;

	abstract protected function loadData(ObjectManager $manager);

	public function load(ObjectManager $manager): void
	{
		$this->manager = $manager;
		$this->faker = Factory::create('fr_FR');
		$this->loadData($manager);
	}

	protected function createMany(string $className, int $count, string $reference, callable $factory)
	{
		for ($i = 0; $i < $count; $i++) {
			$entity = new $className();
			$factory($entity, $i);

			$this->manager->persist($entity);
			// store for usage later as App\Entity\ClassName_#COUNT#
			$this->addReference($reference . $i, $entity);
		}
	}
}
