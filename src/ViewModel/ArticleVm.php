<?php

namespace App\ViewModel;

use App\Helper\ArticleHelper;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class ArticleVm
{
    private int $id;
    private string $slug;
    private string $title;
    private DateTimeInterface $createdAt;
    private string $content;
    private Collection $tags;
    private string $auteur;
    private Collection $comments;

    public function __construct(
        int $id,
        bool $isShortContext,
        string $slug,
        string $title,
        DateTimeInterface $createdAt,
        string $content,
        string $auteur
    ) {
        $this->id = $id;
        $this->slug = $slug;
        $this->title = $title;
        $this->createdAt = $createdAt;
        $this->content = $isShortContext === false ? $content : $this->convertToShort($content);
        $this->auteur = $auteur;
		$this->tags = new ArrayCollection();
		$this->comments = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getAuteur(): string
    {
        return $this->auteur;
    }

    /**
     * @param Collection $tags
     * @return ArticleVm
     */
    public function setTags(Collection $tags): ArticleVm
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @param Collection $comments
     * @return ArticleVm
     */
    public function setComments(Collection $comments): ArticleVm
    {
        $this->comments = $comments;
        return $this;
    }


    /**
     * @return Collection
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param string $content
     * @return string
     */
    private function convertToShort(string $content): string
    {
        return ArticleHelper::convertToShort($content, 100);
    }
}
