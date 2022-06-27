<?php

namespace App\Controller\Admin;

use App\Controller\CustomerController;
use App\Entity\Application;
use App\Entity\Campaign;
use App\Entity\PageBuilder;
use App\Entity\PageSection;
use App\Entity\Session;
use App\Entity\Status;
use App\Entity\User;
use App\Repository\ApplicationRepository;
use App\Repository\ProductRepository;
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
    private ProductRepository $productRepository;
    private ApplicationRepository $applicationRepository;

    public function __construct(EntityManagerInterface $entityManager, ProductRepository $productRepository, ApplicationRepository $applicationRepository)
    {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
        $this->applicationRepository = $applicationRepository;
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $allCurrentCampaign = $this->entityManager->getRepository(Campaign::class)->findBy([
                'status' => 8
            ]);
            /* STOCKER LES NOUVELLES REQUEST DANS $numberNewRequest */
            $numberNewRequest = 3;
            return $this->render('admin/admin_dashboard.html.twig', [
                'allCurrentCampaign' => $allCurrentCampaign,
                'numberNewRequest' => $numberNewRequest,
            ]);


        } elseif ($this->isGranted('ROLE_CUSTOMER')) {

        } elseif ($this->isGranted('ROLE_TESTER')) {
            $allCampaigns = $this->entityManager->getRepository(Campaign::class)->findAll();
            $statusSoon = $this->entityManager->getRepository(Status::class)->findOneBy(["name" => "soon"]);
            $statusOngoing = $this->entityManager->getRepository(Status::class)->findOneBy(["name" => "ongoing"]);
            $statusFinish = $this->entityManager->getRepository(Status::class)->findOneBy(["name" => "finish"]);

            $allCampaignSoon = $this->entityManager->getRepository(Campaign::class)->findBy([
                'status' => $statusSoon
            ]);

            $allCampaignOngoing = $this->entityManager->getRepository(Campaign::class)->findBy([
                'status' => $statusOngoing
            ]);

            $allCampaignFinish = $this->entityManager->getRepository(Campaign::class)->findBy([
                'status' => $statusFinish
            ]);

            $currentUser = $this->getUser();
            $application = $this->applicationRepository->findCampaigns($currentUser);
            $detailsCampaign = $this->productRepository->findDetailsCampaign();


            return $this->render('tester/list_campaign.html.twig', [
                'allCampaigns' => $allCampaigns,
                'allCampaignSoon' => $allCampaignSoon,
                'allCampaignOngoing' => $allCampaignOngoing,
                'allCampaignFinish' => $allCampaignFinish,
                'details' => $detailsCampaign,
                'applications' => $application
            ]);

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
            yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
            yield MenuItem::linkToCrud('Campaign', 'fa fa-list', Campaign::class);
            yield MenuItem::linkToCrud('Application', 'fas fa-book', Application::class);
            yield MenuItem::subMenu('Page Builder', 'fa fa-cog')->setSubItems([
                MenuItem::linkToCrud('Pages', 'fa fa-globe', PageBuilder::class),
                MenuItem::linkToCrud('Sections', 'fa fa-list-alt', PageSection::class)
            ]);

        } elseif ($this->isGranted('ROLE_TESTER')) {
            yield MenuItem::subMenu('Campaign', 'fa fa-list')->setSubItems([
                MenuItem::linkToRoute('List campaign', 'fa fa-file', 'list_campaign_tester'),
                MenuItem::linkToRoute('My campaign', 'fa fa-file', 'my_campaign')
            ]);
        }elseif ($this->isGranted('ROLE_CUSTOMER')) {
            yield MenuItem::subMenu('Campaign', 'fa fa-list-ol')->setSubItems([
                MenuItem::linkToRoute('Lists campaigns', 'fa fa-list', 'all_campaign_customer'),
                MenuItem::linkToRoute('Stats Graphs', 'fa fa-chart-pie', 'my_campaign_customer'),
                MenuItem::linkToRoute('Contact creation campaign', 'fa fa-address-book', 'contact_campaign')
            ]);
        }
    }

            public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('css/admin.css')
            ->addJsFile('js/admin.js');

    }
}
