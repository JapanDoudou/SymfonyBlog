<?php

namespace App\Tests\Factory;

use App\Contracts\Manager\ArticleManagerInterface;
use App\Entity\User;
use App\Factory\Entity\ArticleFactory;
use App\Repository\UserRepository;
use App\Tests\AbstractTestCase;

class ArticleFactoryTest extends AbstractTestCase
{
	public ArticleFactory $factory;
	/**
	 * @var ArticleManagerInterface
	 */
	private ArticleManagerInterface $manager;

	private User $user;

	/**
	 * @throws \Exception
	 */
	public function setup(
	): void {
		AbstractTestCase::setUp();
		$this->factory = self::getContainer()->get(ArticleFactory::class);
		$this->manager = self::getContainer()->get(ArticleManagerInterface::class);
		$userRepository = self::getContainer()->get(UserRepository::class);
		$this->user = $userRepository->findAll()[0];
	}

	/**
	 * @testdox Test createOrUpdate on Factory and on Manager
	 */
    public function testCreateOrUpdate(): void
    {
		$article = $this->factory->createOrUpdate(null,"Salut c'est cool", "Contenu test", null, $this->user);
		$this->manager->createOrUpdate($article);
        $this->assertNotNull($article->getId());
    }
}
