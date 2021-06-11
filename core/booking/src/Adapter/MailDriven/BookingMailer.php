<?php declare(strict_types=1);


namespace Booking\Adapter\MailDriven;

use Booking\Application\Mailer\ReservationConfirmationToClient;
use Booking\Infrastructure\BackofficeEmailRetriever;
use Booking\Infrastructure\BookingEmailSender;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class BookingMailer implements ReservationConfirmationToClient
{
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

    public function sendReservationConfirmationEmailToClient(string $recipient, array $personData, array $bookData): TemplatedEmail
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->sender->address(), $this->sender->name()))
            ->to(new Address($recipient))
            ->subject('Oberdan-8 Prenotazione ricevuta!')
            ->htmlTemplate('@booking/email/for-clients/reservation-confirmation.html.twig')
            ->context([
                'firstName' => $personData['firstName'],
                'lastName' => $personData['lastName'],
                'contact_email' => $personData['contact_email'],
                'phone' => $personData['phone'],
                'city' => $personData['city'],
                'classe' => $personData['classe'],
                'bookList' => $bookData,
            ])
        ;

        $this->mailer->send($email);

        return $email;
    }
}
