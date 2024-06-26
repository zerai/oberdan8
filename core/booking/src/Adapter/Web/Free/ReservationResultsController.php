<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Free;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/esito", name="app_reservation_result", methods={"GET"})
 */
class ReservationResultsController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('@booking/reservation-results-page.html.twig', [
        ]);
    }
}
