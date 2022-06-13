<?php

namespace App\Controller\Admin;

use App\Entity\PageBuilder;
use App\Form\PageSectionType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class PageBuilderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PageBuilder::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'name',
            'title',
            CollectionField::new('sections')
                ->setEntryIsComplex(true)
                ->setEntryType(PageSectionType::class)
        ];
    }
}
