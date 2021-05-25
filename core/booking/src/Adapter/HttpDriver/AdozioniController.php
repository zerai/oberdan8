<?php declare(strict_types=1);

namespace Booking\Adapter\HttpDriver;

use Booking\Infrastructure\Framework\Form\AdozioniReservationType;
use LeanpubBookClub\Application\UpdateSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdozioniController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(AdozioniReservationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

//            $this->application->updateSession(
//                new UpdateSession($sessionId, $formData['description'], $formData['urlForCall'])
//            );

            return $this->redirectToRoute('home_oberdan');
        }

        return $this->render('@booking/Static/reservation-adozioni.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
