<?php declare(strict_types=1);

namespace Booking\Adapter\HttpDriver;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservationResultsController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        return $this->render('@booking/reservation-results-page.html.twig', [
        ]);
    }
}
