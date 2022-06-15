<?php

namespace App\Controller\Admin;

use App\Entity\Application;
use App\Entity\Status;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use Doctrine\Persistence\ManagerRegistry;

class ApplicationCrudController extends AbstractCrudController
{
    private $adminUrlGenerator;
    private $doctrine;

    public function __construct(AdminUrlGenerator $adminUrlGenerator, ManagerRegistry $doctrine) {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->doctrine = $doctrine;
    }


    public static function getEntityFqcn(): string
    {
        return Application::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $user = AssociationField::new('user');
        $session = AssociationField::new('session')->setLabel('campaign')->setCrudController(CampaignCrudController::class);
        $status = AssociationField::new('status');


        if (CRUD::PAGE_INDEX === $pageName){
            return [$user, $session, $status];
        }
        return [$user, $session, $status];
    }

    public function configureActions(Actions $actions): Actions
    {

        $buttonAccepted = Action::new('acceptedApplication', 'Accept Application', 'fa fa-check')
            ->linkToCrudAction('acceptedApplication')
            ->displayIf(static function($entity) {
                return $entity->getStatus() == "pending";
            });

        $buttonRefused = Action::new('refusedApplication', 'Refuse Application', 'fa fa-ban')
            ->linkToCrudAction('refusedApplication')
            ->displayIf(static function($entity) {
                return $entity->getStatus() == "pending";
            });

        return $actions->remove(CRUD::PAGE_INDEX, ACTION::EDIT)
            ->remove(CRUD::PAGE_INDEX, ACTION::DELETE)
            ->add(CRUD::PAGE_INDEX, $buttonAccepted)
            ->add(CRUD::PAGE_INDEX, $buttonRefused);
    }

    public function configureFilters(Filters $filters): Filters
    {

        $pending = $this->doctrine->getRepository(Status::class)->findOneBy(['name' => 'pending']);
        $accepted = $this->doctrine->getRepository(Status::class)->findOneBy(['name' => 'accepted']);
        $refused = $this->doctrine->getRepository(Status::class)->findOneBy(['name' => 'refused']);

        return $filters
            ->add(ChoiceFilter::new('status', 'status')->setChoices(['pending' => $pending, 'accepted' => $accepted, 'refused' => $refused]));
    }

    public function acceptedApplication(AdminContext $context, EntityManagerInterface $entityManager, StatusRepository $statusRepository) {
        $application = $context->getEntity()->getInstance();
        $statusAccepted = $statusRepository->findOneBy(["name" => "accepted"]);
        $application->setStatus($statusAccepted);
        $number_tester = $application->getSession()->getCampaign()->getNumberTester();
        $application->getSession()->getCampaign()->setNumberTester($number_tester + 1);

        $entityManager->persist($application);
        $entityManager->flush();
        $url = $this->adminUrlGenerator
            ->setController(ApplicationCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function refusedApplication(AdminContext $context, EntityManagerInterface $entityManager, StatusRepository $statusRepository) {
        $application = $context->getEntity()->getInstance();
        $statusRefused = $statusRepository->findOneBy(["name" => "refused"]);
        $application->setStatus($statusRefused);

        $entityManager->persist($application);
        $entityManager->flush();
        $url = $this->adminUrlGenerator
            ->setController(ApplicationCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }

}
