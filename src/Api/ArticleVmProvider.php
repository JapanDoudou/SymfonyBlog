<?php
/**
 * Copyright (C) 2023 Proximis
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
namespace App\Api;

use ApiPlatform\State\ProviderInterface;
use App\Contracts\Manager\ArticleManagerInterface;

/**
 * @name \App\Api\ArticleVmProvider
 */
class ArticleVmProvider implements ProviderInterface
{
	public function __construct(private readonly ArticleManagerInterface $articleManager)
	{
	}

	public function provide(\ApiPlatform\Metadata\Operation $operation, array $uriVariables = [], array $context = []): object|array|null
	{
		return $this->articleManager->findOneById($uriVariables['id']);
	}
}