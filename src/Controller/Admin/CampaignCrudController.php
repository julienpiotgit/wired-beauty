<?php

namespace App\Controller\Admin;

use App\Entity\Campaign;
use App\Form\SessionType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class CampaignCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Campaign::class;
    }


    public function configureFields(string $pageName): iterable
    {
            $name = TextField::new('name');
            $description = TextField::new('description');
            $dateStart = DateField::new('date_start');
            $dateEnd = DateField::new('date_end');
            $file = TextField::new('file');
            $file_edit = ImageField::new('file')->setBasePath('uploads/')->setUploadDir('public/uploads');
//            $sessionEdit = AssociationField::new('sessions');
            $session = CollectionField::new('sessions')->setEntryIsComplex(true)->setEntryType(SessionType::class);
            $status = IntegerField::new('status')->setProperty('name');
        if (CRUD::PAGE_INDEX === $pageName){
            return [$name,$description, $dateStart, $dateEnd, $file, $session, $status];
        }elseif (CRUD::PAGE_EDIT === $pageName){
            return [$name,$description, $dateStart, $dateEnd, $file_edit, $session];
        }elseif (CRUD::PAGE_NEW === $pageName){
            return [$name,$description, $dateStart, $dateEnd, $file_edit, $session];
        }
        return [$name,$description, $dateStart, $dateEnd, $file_edit, $session];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(CRUD::PAGE_INDEX, ACTION::DETAIL);
    }
}
