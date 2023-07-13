<?php

namespace App\Trait\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @name \App\Trait\Entity\IdTrait
 */
trait IdTrait
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: 'integer')]
	#[Groups(['list','item'])]
	protected $id;

	public function getId(): ?int
	{
		return $this->id;
	}
}