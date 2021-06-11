<?php declare(strict_types=1);

namespace Booking\Adapter\HttpDriver;

use Booking\Adapter\MailDriven\BookingMailer;
use Booking\Infrastructure\Framework\Form\Dto\ReservationFormModel;
use Booking\Infrastructure\Framework\Form\ReservationType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;

class ReservationController extends AbstractController
{
    public function __invoke(Request $request, MailerInterface $mailer, BookingMailer $bookingMailer): Response
    {
        // original call
        $form = $this->createForm(ReservationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ReservationFormModel $formData */
            $formData = $form->getData();

            // TODO APPLICATION lOGIC
            // 1 PERSIST RESERVATION
            // 1SEND EMAILS

            // send email to client
            $bookingMailer->sendReservationConfirmationEmailToClient(
                $formData->person->getEmail(),
                $this->mapPersonDataToReservationConfirmationEmail($formData),
                $this->mapBookDataToReservationConfirmationEmail($formData)
            );

            // send email to backoffice
            //$emailForBackoffice = $this->createRiepilogoEmailForBackoffice($formData);

            //$mailer->send($emailForBackoffice);

            $this->addFlash('success', 'Prenotazine avvenuta con successo.');

            return $this->redirectToRoute('reservation_result');
        }

        return $this->render('@booking/reservation-page.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function mapPersonDataToReservationConfirmationEmail(ReservationFormModel $formData): array
    {
        return [
            'firstName' => $formData->person->getFirstName(),
            'lastName' => $formData->person->getLastName(),
            'contact_email' => $formData->person->getEmail(),
            'phone' => $formData->person->getPhone(),
            'city' => $formData->person->getCity(),
            'classe' => $formData->classe,
            //'bookList' => $formData->books,
        ];
    }

    private function mapBookDataToReservationConfirmationEmail(ReservationFormModel $formData): array
    {
        return $formData->books;
    }

    private function createRiepilogoEmailForBackoffice(ReservationFormModel $formData): TemplatedEmail
    {
        $email = (new TemplatedEmail())
            //->from('prenotazioni@8viadeilibrai.it')
            ->from('memu.system@medicalmundi.com')
            // TODO USARE MAIL DEL BACKOFFICE
            ->to($formData->person->email)
            // TODO AGGIUNGERE RIF.ID
            ->subject('Nuova Prenotazione')
            ->textTemplate('@booking/email/for-backoffice/new-reservation/new-reservation.txt.twig')
            ->context([
                'firstName' => $formData->person->getFirstName(),
                'lastName' => $formData->person->getLastName(),
                'contact_email' => $formData->person->getEmail(),
                'phone' => $formData->person->getPhone(),
                'city' => $formData->person->getCity(),
                'classe' => $formData->classe,
                'bookList' => $formData->books,
            ])
        ;

        return $email;
    }
}
