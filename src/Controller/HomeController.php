<?php

namespace App\Controller;

use App\Contracts\Manager\ArticleManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractBaseController
{
	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param \App\Contracts\Manager\ArticleManagerInterface $manager
	 * @return Response
	 */
	#[Route('/', name: 'homepage')]
	public function index(Request $request, ArticleManagerInterface $manager): Response
	{
		$page = $this->getRequestPage($request);
		$articles = $manager->getAllArticlesVmsPaginated($page);
		return $this->render('home/index.html.twig', array(
			'articles' => $articles
		));
	}
}
