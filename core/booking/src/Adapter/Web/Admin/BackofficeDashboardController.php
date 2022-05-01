<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackofficeDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="backoffice_dashboard")
     */
    public function index(): Response
    {
        return $this->render('backoffice/dashboard/index.html.twig', []);
    }
}
