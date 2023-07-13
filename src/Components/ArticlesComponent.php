<?php

namespace App\Components;

use App\Contracts\Manager\ArticleManagerInterface;
use Doctrine\Common\Collections\Collection;
use Pagerfanta\PagerfantaInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('articles')]
class ArticlesComponent
{
	public Collection $articles;

	public function __construct(
		private readonly ArticleManagerInterface $articleManager
	) {}

	public function getArticles(Request $request): PagerfantaInterface {
		return $this->articleManager->getAllArticlesVmsPaginated($request->get('page', 1));
	}
}
