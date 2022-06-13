<?php

namespace App\Controller\Admin;

use App\Entity\HomePageBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HomePageBuilderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HomePageBuilder::class;
    }

//    public function configureFields(string $pageName): iterable
//    {
//        return [
//            IdField::new('id'),
//            TextField::new('title'),
//            TextEditorField::new('description'),
//        ];
//    }
}
