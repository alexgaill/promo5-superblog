<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[
        Route('/admin', name: 'admin'),
        IsGranted("ROLE_ADMIN")
        ]
    public function index(): Response
    {
        // return parent::index();
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(PostCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Admin Dashboard')
            ->disableDarkMode();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linktoCrud('Catégories', 'fas fa-list', Category::class);
        yield MenuItem::linktoCrud('Articles', 'fas fa-list', Post::class);

        yield MenuItem::section('Utilisateurs');

        yield MenuItem::linktoCrud('Utilisateurs', 'fas fa-user', User::class);
    }
}
