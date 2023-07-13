<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use App\Trait\Entity\IdTrait;
use App\Trait\Entity\TimestampableTrait;
use App\Trait\Entity\UuidTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
	use IdTrait;
	use UuidTrait;
	use TimestampableTrait;

    #[ORM\Column(type: 'string', length: 255)]
    private $username;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private $article;

	public function __construct()
	{
		$this->initTime();
		$this->generateUuid();
	}

	public function __toString(): string
    {
        return (string) $this->username;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
