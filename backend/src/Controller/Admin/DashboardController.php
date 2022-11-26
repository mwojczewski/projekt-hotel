<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Entity\Room;
use App\Entity\RoomPhoto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(RoomCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Hotel');
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::linktoCrud('Pokoje', 'fas fa-person-shelter', Room::class);
        yield MenuItem::linktoCrud('Zdjęcia', 'fas fa-camera', RoomPhoto::class);
        yield MenuItem::linktoCrud('Rezerwacje', 'fas fa-book', Reservation::class);
    }
}
