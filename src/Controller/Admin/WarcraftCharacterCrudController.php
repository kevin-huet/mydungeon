<?php

namespace App\Controller\Admin;

use App\Entity\WoW\WarcraftCharacter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class WarcraftCharacterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WarcraftCharacter::class;
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
