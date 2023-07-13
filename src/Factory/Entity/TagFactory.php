<?php

namespace App\Factory\Entity;

use App\Entity\Tag;

/**
 * @name \App\Factory\Entity\TagFactory
 */
class TagFactory
{
	public function createOrUpdate(
		string $intitule,
		string $slug,
		string $color,
		?Tag $tag = null
	): Tag {
		if ($tag === null)
		{
			$tag = new Tag();
		}
		$tag->setIntitule($intitule)
			->setSlug($slug)
			->setColor($color);
		return $tag;
	}
}