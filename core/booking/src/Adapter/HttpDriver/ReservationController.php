<?php declare(strict_types=1);

namespace Booking\Adapter\HttpDriver;

use Booking\Infrastructure\Framework\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(ReservationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

//            $this->application->updateSession(
//                new UpdateSession($sessionId, $formData['description'], $formData['urlForCall'])
//            );

            $this->addFlash('success', 'Prenotazine avvenuta con successo.');

            return $this->redirectToRoute('reservation_result');
        }

        return $this->render('@booking/reservation-page.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
