<?php

namespace App\Trait\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @name \App\Trait\Entity\SlugTrait
 */
trait SlugTrait
{
	#[ORM\Column(type: 'string', length: 255)]
	#[Groups(['list', 'item'])]
	protected ?string $slug;

	public function getSlug(): ?string
	{
		return $this->slug;
	}

	public function setSlug(string $slug): self
	{
		$this->slug = $slug;

		return $this;
	}
}