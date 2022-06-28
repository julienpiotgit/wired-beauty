<?php

namespace App\Controller\Admin;

use App\Entity\Campaign;
use App\Entity\Status;
use App\Form\ProductType;
use App\Form\SessionType;
use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class CampaignCrudController extends AbstractCrudController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

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
            $session = CollectionField::new('sessions')->setEntryIsComplex(true)->setEntryType(SessionType::class);
            $product = CollectionField::new('products')->setEntryIsComplex(true)->setEntryType(ProductType::class);
            $status = AssociationField::new('status');

            $number_tester = IntegerField::new('number_tester')->setLabel('Number of testers');
        if (CRUD::PAGE_INDEX === $pageName){
            return [$name,$description, $dateStart, $dateEnd, $session, $number_tester, $status];
        }elseif (CRUD::PAGE_EDIT === $pageName){
            return [$name,$description, $dateStart, $dateEnd, $session];
        }elseif (CRUD::PAGE_NEW === $pageName){
            return [$name,$description, $dateStart, $dateEnd, $file_edit, $session, $product];
        }elseif (CRUD::PAGE_DETAIL === $pageName){
            return [$name,$description,$session, $dateStart, $dateEnd, $number_tester, $status, $file];
        }
        return [$name,$description, $dateStart, $dateEnd, $file_edit, $session];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(CRUD::PAGE_INDEX, ACTION::DETAIL);
    }

    public function configureFilters(Filters $filters): Filters
    {

        $soon = $this->doctrine->getRepository(Status::class)->findOneBy(['name' => 'soon']);
        $ongoing = $this->doctrine->getRepository(Status::class)->findOneBy(['name' => 'ongoing']);
        $finish = $this->doctrine->getRepository(Status::class)->findOneBy(['name' => 'finish']);

        return $filters
            ->add(ChoiceFilter::new('status', 'status')->setChoices(['soon' => $soon, 'ongoing' => $ongoing, 'finish' => $finish]));
    }
}
