<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }


	public function configureFields(string $pageName): iterable
	{
		yield TextField::new('username', 'comment.username');
		yield TextField::new('content', 'comment.content');
		yield AssociationField::new('article', 'article.comments');
	}

}
