<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Admin;

use Booking\Application\Domain\UseCase\ExportCustomerInterface;
use Booking\Infrastructure\Exporter\ExportDataHttpResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/export")
 */
class BackofficeExportCustomerController extends AbstractController
{
    /**
     * @Route("/", name="backoffice_data_export", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('backoffice/export/index.html.twig', []);
    }

    /**
     * @Route("/customer/all", name="backoffice_export_customer_all", methods={"GET"})
     */
    public function exportAllCustomer(ExportCustomerInterface $exporter, ExportDataHttpResponseFactory $responseFactory): Response
    {
        return $responseFactory::createFromExportedFile($exporter->exportAllCustomer());
    }
}
