<?php

namespace App\Controller\Admin;

use App\Entity\PageSection;
use App\Form\PointType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class PageSectionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PageSection::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'location',
            AssociationField::new('pageBuilder', 'Page'),
            'title',
            'description',
            'button',
            ImageField::new('image')->setBasePath('uploads/')->setUploadDir('public/uploads'),
            CollectionField::new('points')
                ->setEntryIsComplex(true)
                ->setEntryType(PointType::class)
        ];
    }
}
