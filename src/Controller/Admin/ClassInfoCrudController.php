<?php

namespace App\Controller\Admin;

use App\Entity\ClassInfo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ClassInfoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ClassInfo::class;
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
