<?php

namespace App\Controller\Dev;

use App\Contracts\Manager\TagManagerInterface;
use App\Controller\AbstractBaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DevController extends AbstractBaseController
{
	/**
	 * @return Response
	 */
	#[Route('/read-me/installation', name: 'installation')]
	public function installation(): Response
	{
		return $this->render('home/dev/installation.html.twig');
	}

	/**
	 * @return Response
	 */
	#[Route('/dev/style', name: 'style')]
	public function showStylePage(): Response
	{
		return $this->render('home/dev/style.html.twig', array(
			'display_button' => true
		));
	}

	/**
	 * @param Request $request
	 * @param TagManagerInterface $tagManager
	 * @return Response
	 */
	#[Route('/dev/samples', name: 'samples')]
	public function showSamplePage(
		Request $request,
		TagManagerInterface $tagManager
	): Response {
		$tags = $tagManager->findAllTagsPaginated($this->getRequestPage($request));
		return $this->render('home/dev/samples.html.twig', array(
			'tags' => $tags
		));
	}
}