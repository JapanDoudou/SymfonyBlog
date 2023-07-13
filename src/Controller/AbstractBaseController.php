<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @name \App\Controller\AbstractBaseController
 */
class AbstractBaseController extends  AbstractController
{
	protected function getRequestPage(Request $request): int
	{
		return $request->get('page', 1);
	}
}