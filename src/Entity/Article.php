<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ArticleRepository;
use App\Trait\Entity\IdTrait;
use App\Trait\Entity\SlugTrait;
use App\Trait\Entity\TimestampableTrait;
use App\Trait\Entity\UuidTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[UniqueEntity('slug')]
class Article
{
	use IdTrait;
	use SlugTrait;
	use TimestampableTrait;
	use UuidTrait;

	#[ORM\Column(type: 'string', length: 255)]
	#[Groups(['article:list', 'article:item'])]
	private $title;

	#[ORM\Column(type: 'text')]
	#[Groups(['article:list', 'article:item'])]
	private $content;

	#[ORM\Column(type: 'boolean')]
	#[Groups(['article:list', 'article:item'])]
	private $isPublished;

	#[ORM\OneToMany(mappedBy: 'article', targetEntity: Comment::class, orphanRemoval: true)]
	private $comments;

	#[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'articles')]
	#[Groups(['article:list', 'article:item'])]
	private $tags;

	#[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'articles')]
	#[ORM\JoinColumn(nullable: false)]
	#[Groups(['article:list', 'article:item'])]
	private $user;

	public function __construct()
	{
		$this->comments = new ArrayCollection();
		$this->tags = new ArrayCollection();
		$this->initTime();
		$this->generateUuid();
	}

	public function __toString(): string
	{
		return (string)$this->title;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;

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

	public function isIsPublished(): ?bool
	{
		return $this->isPublished;
	}

	public function setIsPublished(bool $isPublished): self
	{
		$this->isPublished = $isPublished;

		return $this;
	}

	/**
	 * @return Collection<int, Comment>
	 */
	public function getComments(): Collection
	{
		return $this->comments;
	}

	public function addComment(Comment $comment): self
	{
		if (!$this->comments->contains($comment))
		{
			$this->comments[] = $comment;
			$comment->setArticle($this);
		}

		return $this;
	}

	public function removeComment(Comment $comment): self
	{
		if ($this->comments->removeElement($comment))
		{
			// set the owning side to null (unless already changed)
			if ($comment->getArticle() === $this)
			{
				$comment->setArticle(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, Tag>
	 */
	public function getTags(): Collection
	{
		return $this->tags;
	}

	public function addTag(Tag $tag): self
	{
		if (!$this->tags->contains($tag))
		{
			$this->tags[] = $tag;
			$tag->addArticle($this);
		}

		return $this;
	}

	public function removeTag(Tag $tag): self
	{
		if ($this->tags->removeElement($tag))
		{
			$tag->removeArticle($this);
		}

		return $this;
	}

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): self
	{
		$this->user = $user;

		return $this;
	}
}
