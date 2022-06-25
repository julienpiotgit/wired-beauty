<?php

namespace App\Controller\Admin;

use App\Entity\Status;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ArrayFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
            $firstname = TextField::new('firstname');
            $name = TextField::new('name');
            $email = TextField::new('email');
            $roles = ArrayField::new('roles');
            $age = IntegerField::new('age');
            $height = IntegerField::new('height');
            $weight = IntegerField::new('weight');
            $postal_code = IntegerField::new('postal_code');
            $city = TextField::new('city');
            $longitude = TextField::new('longitude');
            $latitude = TextField::new('latitude');
        if (CRUD::PAGE_INDEX == $pageName) {
            return [$firstname, $name, $email, $roles, $age, $height, $weight, $postal_code, $city];
        }elseif (CRUD::PAGE_EDIT === $pageName){
            return [$firstname,$name, $email, $age, $postal_code, $city];
        }elseif (CRUD::PAGE_NEW === $pageName){
            return [$firstname,$name, $email, $roles, $age, $postal_code, $city];
        }elseif (CRUD::PAGE_DETAIL === $pageName){
            return [$firstname,$name,$email, $roles, $age, $postal_code, $city];
        }
        return [$firstname, $name, $email, $roles, $age, $postal_code, $city];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(CRUD::PAGE_INDEX, ACTION::DETAIL)
            ->remove(CRUD::PAGE_INDEX, ACTION::NEW);
    }

    public function configureFilters(Filters $filters): Filters
    {

        return $filters
            ->add(ArrayFilter::new('roles', 'role')->setChoices(['ROLE_ADMIN' => 'ROLE_ADMIN', 'ROLE_TESTER' => 'ROLE_TESTER', 'ROLE_CUSTOMER' => 'ROLE_CUSTOMER']));
    }

}
