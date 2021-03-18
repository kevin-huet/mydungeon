<?php

namespace App\Controller\Admin;

use App\Entity\WoW\GameInfo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class GameInfoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GameInfo::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
