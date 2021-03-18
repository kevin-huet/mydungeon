<?php

namespace App\Controller\Admin;

use App\Entity\WoW\Expansion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ExpansionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Expansion::class;
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
