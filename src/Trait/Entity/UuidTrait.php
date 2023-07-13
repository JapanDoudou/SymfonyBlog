<?php

namespace App\Trait\Entity;

use Ramsey\Uuid\Doctrine\UuidGenerator;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

/**
 * @name \App\Trait\Entity\UuidTrait
 */
trait UuidTrait
{
	#[ORM\Column(type: "uuid", unique: true, nullable: false)]
	#[ORM\CustomIdGenerator(class: UuidGenerator::class)]
	protected UuidInterface|string $uuid;

	private function generateUuid(): void
	{
		if ($this->id !== null)
		{
			return;
		}
		$this->uuid = Uuid::uuid4();
	}
}