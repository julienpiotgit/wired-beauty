<?php

namespace App\Controller\Admin;

use App\Entity\Campaign;
use App\Entity\Session;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $allCurrentCampaign = $this->entityManager->getRepository(Campaign::class)->findBy([
                'status' => 2
            ]);
            /* STOCKER LES NOUVELLES REQUEST DANS $numberNewRequest */
            $numberNewRequest = 3;
            return $this->render('admin/admin_dashboard.html.twig', [
                'allCurrentCampaign' => $allCurrentCampaign,
                'numberNewRequest' => $numberNewRequest,
            ]);


        } elseif ($this->isGranted('ROLE_CUSTOMER')) {
            $currentUser = $this->getUser();
            return $this->render('admin/customer_dashboard.html.twig');
        } elseif ($this->isGranted('ROLE_TESTER')) {
            $currentUser = $this->getUser();
            return $this->render('admin/tester_dashboard.html.twig');

        }

        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(CampaignCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('WiredBeauty Admin')
            ->setTitle('<img src="logo.png">');

    }

    public function configureMenuItems(): iterable
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
            yield MenuItem::linkToCrud('Session', 'fa fa-list', Session::class);
            yield MenuItem::linkToCrud('Campaign', 'fa fa-file', Campaign::class);
            yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        } elseif ($this->isGranted('ROLE_USER')) {
            yield MenuItem::linkToCrud('Campaign', 'fa fa-file', Campaign::class);
            yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        }
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('css/admin.css')
            ->addJsFile('js/admin.js');

    }
}
