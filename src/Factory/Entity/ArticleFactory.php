<?php

namespace App\Factory\Entity;

use App\Entity\Article;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @name \App\Factory\Entity\ArticleFactory
 */
class ArticleFactory
{
	/**
	 * @var \Symfony\Component\String\Slugger\SluggerInterface
	 */
	private SluggerInterface $slugger;

	public function __construct(SluggerInterface $slugger)
	{
		$this->slugger = $slugger;
	}

	public function createOrUpdate(
		?Article $article,
		$title,
		$content,
		?DateTimeImmutable
		$createdAt,
		User $user
	): Article {
		if ($article === null)
		{
			$article = new Article();
		} else {
			$article->setUpdatedAt(new DateTimeImmutable());
		}
		$slug = $this->slugger->slug($title);
		$article->setTitle($title)
			->setSlug($slug)
			->setContent($content)
			->setIsPublished(true)
			->setUser($user);
		if ($createdAt)
		{
			$article->setCreatedAt($createdAt);
		}
		return $article;
	}
}