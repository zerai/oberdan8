<?php declare(strict_types=1);

namespace Booking\Adapter\HttpDriver;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('Static/reservation.html.twig', [
            'page' => 'reservation',
        ]);
    }
}
