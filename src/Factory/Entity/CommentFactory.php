<?php

namespace App\Factory\Entity;

use App\Entity\Article;
use App\Entity\Comment;
use DateTimeImmutable;

/**
 * @name \App\Factory\Entity\CommentFactory
 */
class CommentFactory
{
	public function createOrUpdate(
		Article $article,
		string $userName,
		string $content,
		DateTimeImmutable $dateTimeImmutable,
		?Comment $comment = null
	): Comment {
		if ($comment === null)
		{
			$comment = new Comment();
		} else {
			$comment->setUpdatedAt(new DateTimeImmutable());
		}
		$comment->setArticle($article)
			->setUsername($userName)
			->setContent($content)
			->setCreatedAt($dateTimeImmutable);
		return $comment;
	}
}