<?php declare(strict_types=1);

namespace Booking\Adapter\HttpDriver;

use Booking\Infrastructure\Framework\Form\Dto\ReservationFormModel;
use Booking\Infrastructure\Framework\Form\ReservationType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ReservationController extends AbstractController
{
    public function __invoke(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ReservationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ReservationFormModel $formData */
            $formData = $form->getData();

//            $this->application->updateSession(
//                new UpdateSession($sessionId, $formData['description'], $formData['urlForCall'])
//            );


            //dd($formData);
            $email = (new TemplatedEmail())
                ->from('prenotazioni@8viadeilibrai.it')
                ->to($formData->person->email)
                ->subject('Nuova Prenotazione')
//                ->text('
//                Dati prenotazione \n
//                \n
//                Cognome: ' . $formData->person->lastName . '
//                ')
                ->htmlTemplate('@booking/email/welcome.html.twig')
                ->context([
                    'firstName' => $formData->person->getFirstName(),
                    'lastName' => $formData->person->getLastName(),
                    'contact_email' => $formData->person->getEmail(),
                    'phone' => $formData->person->getPhone(),
                ])
            ;

            $mailer->send($email);

            $this->addFlash('success', 'Prenotazine avvenuta con successo.');

            //dd($formData);

            return $this->redirectToRoute('reservation_result');
        }

        return $this->render('@booking/reservation-page.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
