<?php

namespace App\Controller\Admin;

use App\Entity\WoW\DungeonGroup;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DungeonGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DungeonGroup::class;
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
