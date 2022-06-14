<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
//            $firstname = TextField::new('firstname');
//            $name = TextField::new('name');
//            $email = TextField::new('email');
//            $roles = TextField::new('roles');
//            $age = IntegerField::new('age');
//            $height = IntegerField::new('height');
//            $weight = IntegerField::new('weight');
//            $postal_code = TextField::new('postal_code');
//            $city = TextField::new('city');
//            $longitude = TextField::new('longitude');
//            $latitude = TextField::new('latitude');
        if ($this->isGranted('ROLE_ADMIN')) {
            return [
                TextField::new('firstname'),
                TextField::new('name'),
                EmailField::new('email'),
//            ArrayField::new('roles'),
                IntegerField::new('age'),
                IntegerField::new('height'),
                IntegerField::new('weight'),
                IntegerField::new('postal_code'),
                TextField::new('city'),
                IntegerField::new('longitude'),
                IntegerField::new('latitude'),
            ];
        }elseif ($this->isGranted('ROLE_USER')) {
            return [
                TextField::new('firstname'),
                TextField::new('name'),
                EmailField::new('email'),
            ];
        }
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(CRUD::PAGE_INDEX, ACTION::DETAIL);
    }

}
