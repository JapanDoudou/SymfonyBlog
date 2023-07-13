<?php

namespace App\Tests\Manager;

use App\Contracts\Manager\ArticleManagerInterface;
use App\DataFixtures\AbstractBaseFixtures;
use App\Manager\ArticleManager;
use App\Pagination\PaginatorFactory;
use App\Repository\TagRepository;
use App\Tests\AbstractTestCase;

/**
 * @name \App\Tests\Manager\ArticleManagerTest
 */
class ArticleManagerTest extends AbstractTestCase
{
	/**
	 * @var ArticleManager
	 */
	private ArticleManager $manager;

	public function setUp(): void
	{
		parent::setUp();
		$this->manager = self::getContainer()->get(ArticleManagerInterface::class);
	}

	/**
	 * @testdox Assert Manager instance is of good class
	 */
	public function testRepositoryInstance()
	{
		self::assertInstanceOf(ArticleManager::class, $this->manager);
	}

	public function testAllArticlesVmPaginated()
	{
		$articlePaginated = $this->manager->getAllArticlesVmsPaginated();
		self::assertSame(AbstractBaseFixtures::NUMBER_OF_ARTICLES, $articlePaginated->getNbResults());
		self::assertCount(PaginatorFactory::PAGE_SIZE, $articlePaginated->getCurrentPageResults());
	}

	public function testGetArticlesByTagPaginated()
	{
		$tag = self::getContainer()->get(TagRepository::class)->findOneBySlug('test');
		$articlesByTag = $this->manager->getArticlesVmByTagPaginated($tag, 1);
		self::assertSame(AbstractBaseFixtures::TAG_ARTICLES_TEST_COUNT, $articlesByTag->getNbResults());
		self::assertCount(PaginatorFactory::PAGE_SIZE, $articlesByTag->getCurrentPageResults());
	}
}