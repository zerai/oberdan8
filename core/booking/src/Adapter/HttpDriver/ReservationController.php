<?php declare(strict_types=1);

namespace Booking\Adapter\HttpDriver;

use Booking\Infrastructure\Framework\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends AbstractController
{
    public function __invoke(): Response
    {
        $form = $this->createForm(ClientType::class);

        return $this->render('Static/reservation.html.twig', [
            'page' => 'reservation',
            'form' => $form->createView(),
        ]);
    }
}
