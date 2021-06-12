<?php declare(strict_types=1);


namespace Booking\Adapter\MailDriven;

use Booking\Application\NotifyNewReservationToBackoffice;
use Booking\Application\NotifyReservationConfirmationToClient;
use Booking\Infrastructure\BackofficeEmailRetriever;
use Booking\Infrastructure\BookingEmailSender;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class BookingMailer implements NotifyReservationConfirmationToClient, NotifyNewReservationToBackoffice
{
    private const RESERVATION_CONFIRMATION_EMAIL_SUBJECT = 'Oberdan 8: Prenotazione ricevuta';

    private MailerInterface $mailer;

    private BookingEmailSender $sender;

    private BackofficeEmailRetriever $backofficeEmailRetriever;

    /**
     * BookingMailer constructor.
     * @param MailerInterface $mailer
     * @param BookingEmailSender $sender
     * @param BackofficeEmailRetriever $backofficeEmailRetriever
     */
    public function __construct(MailerInterface $mailer, BookingEmailSender $sender, BackofficeEmailRetriever $backofficeEmailRetriever)
    {
        $this->mailer = $mailer;
        $this->sender = $sender;
        $this->backofficeEmailRetriever = $backofficeEmailRetriever;
    }

    public function notifyReservationConfirmationEmailToClient(string $recipient, array $personData, array $bookData, string $otherInfo = ''): TemplatedEmail
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->sender->address(), $this->sender->name()))
            ->to(new Address($recipient))
            ->subject(self::RESERVATION_CONFIRMATION_EMAIL_SUBJECT)
            ->htmlTemplate('@booking/email/for-clients/reservation-confirmation.html.twig')
            ->context([
                'firstName' => $personData['firstName'],
                'lastName' => $personData['lastName'],
                'contact_email' => $personData['contact_email'],
                'phone' => $personData['phone'],
                'city' => $personData['city'],
                'classe' => $personData['classe'],
                'otherInfo' => $otherInfo,
                'bookList' => $bookData,
            ])
        ;

        $this->mailer->send($email);

        return $email;
    }

    public function notifyNewReservationToBackoffice(array $personData, array $bookData, array $systemData = [], string $otherInfo = ''): TemplatedEmail
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->sender->address(), $this->sender->name()))
            ->to(new Address($this->backofficeEmailRetriever->address(), $this->backofficeEmailRetriever->name()))
            // TODO AGGIUNGERE RIF.ID
            // TODO AGGIUNGER DATA DELLA PRENOTAZIONE
            ->subject(
                sprintf('Nuova Prenotazione da %s %s', $personData['lastName'], $personData['firstName'])
            )
            ->textTemplate('@booking/email/for-backoffice/new-reservation/new-reservation.txt.twig')
            ->context([
                'firstName' => $personData['firstName'],
                'lastName' => $personData['lastName'],
                'contact_email' => $personData['contact_email'],
                'phone' => $personData['phone'],
                'city' => $personData['city'],
                'classe' => $personData['classe'],
                'otherInfo' => $otherInfo,
                'bookList' => $bookData,
            ])
        ;

        $this->mailer->send($email);

        return $email;
    }
}
