<?php

namespace App\Trait\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @name \App\Trait\Entity\TimestampableTrait
 */
trait TimestampableTrait
{
	#[ORM\Column(type: 'datetime_immutable')]
	#[Groups(['list', 'item'])]
	protected ?DateTimeImmutable $createdAt;

	#[ORM\Column(type: 'datetime_immutable', nullable: true)]
	#[Groups(['list', 'item'])]
	protected ?DateTimeImmutable $updatedAt;

	public function getCreatedAt(): ?DateTimeImmutable
	{
		return $this->createdAt;
	}

	public function setCreatedAt(DateTimeImmutable $createdAt): self
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	public function getUpdatedAt(): ?DateTimeImmutable
	{
		return $this->updatedAt;
	}
	public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
	{
		$this->updatedAt = $updatedAt;
		return $this;
	}

	private function initTime(): void
	{
		$this->createdAt = new DateTimeImmutable();
	}
}