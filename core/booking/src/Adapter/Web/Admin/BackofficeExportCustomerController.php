<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/export")
 */
class BackofficeExportCustomerController extends AbstractController
{
    /**
     * @Route("/customer", name="backoffice_export_customer", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return $this->render('backoffice/export/index.html.twig', [
            //'status_confirmed' => $confirmedStatus,
        ]);
    }
}
