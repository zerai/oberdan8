<?php declare(strict_types=1);


namespace Booking\Adapter\MailDriven;

use Booking\Application\NotifyAdozioniReservationConfirmationToClient;
use Booking\Application\NotifyNewAdozioniReservationToBackoffice;
use Booking\Application\NotifyNewReservationToBackoffice;
use Booking\Application\NotifyReservationConfirmationToClient;
use Booking\Application\NotifyReservationThanksToClient;
use Booking\Infrastructure\BackofficeEmailRetriever;
use Booking\Infrastructure\BookingEmailSender;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class BookingMailer implements NotifyReservationConfirmationToClient, NotifyNewReservationToBackoffice, NotifyAdozioniReservationConfirmationToClient, NotifyNewAdozioniReservationToBackoffice, NotifyReservationThanksToClient
{
    private const RESERVATION_CONFIRMATION_EMAIL_SUBJECT = 'Oberdan 8: Prenotazione ricevuta';

    private const RESERVATION_THANKS_EMAIL_SUBJECT = 'Oberdan 8 Ti ringraziamo per averci scelto';

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

    public function notifyReservationConfirmationEmailToClient(string $recipient, array $personData, array $bookData, string $otherInfo = '', string $coupondCode = ''): TemplatedEmail
    {
        $replyTo = new Address($this->backofficeEmailRetriever->address(), $this->backofficeEmailRetriever->name());

        $email = (new TemplatedEmail())
            ->from(new Address($this->sender->address(), $this->sender->name()))
            ->to(new Address($recipient))
            ->replyTo($replyTo)
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
                'coupondCode' => $coupondCode,
                'bookList' => $bookData,
            ])
        ;

        $this->mailer->send($email);

        return $email;
    }

    public function notifyNewReservationToBackoffice(array $personData, array $bookData, array $systemData = [], string $otherInfo = '', string $coupondCode = ''): TemplatedEmail
    {
        $replyTo = new Address((string) $personData['contact_email'], \sprintf('%s %s', (string) $personData['lastName'], (string) $personData['firstName']));
        $email = (new TemplatedEmail())
            ->from(new Address($this->sender->address(), $this->sender->name()))
            ->to(new Address($this->backofficeEmailRetriever->address(), $this->backofficeEmailRetriever->name()))
            ->replyTo($replyTo)
            // TODO AGGIUNGERE RIF.ID
            // TODO AGGIUNGER DATA DELLA PRENOTAZIONE
            ->subject(
                \sprintf('Nuova Prenotazione da %s %s', (string) $personData['lastName'], (string) $personData['firstName'])
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
                'coupondCode' => $coupondCode,
                'bookList' => $bookData,
            ])
        ;

        $this->mailer->send($email);

        return $email;
    }

    public function notifyAdozioniReservationConfirmationEmailToClient(string $recipient, array $personData, array $filesData = [], string $otherInfo = '', string $coupondCode = ''): TemplatedEmail
    {
        $replyTo = new Address($this->backofficeEmailRetriever->address(), $this->backofficeEmailRetriever->name());

        $email = (new TemplatedEmail())
            ->from(new Address($this->sender->address(), $this->sender->name()))
            ->to(new Address($recipient))
            ->replyTo($replyTo)
            ->subject(self::RESERVATION_CONFIRMATION_EMAIL_SUBJECT)
            ->htmlTemplate('@booking/email/for-clients/adozioni-reservation-confirmation.html.twig')
            ->context([
                'firstName' => $personData['firstName'],
                'lastName' => $personData['lastName'],
                'contact_email' => $personData['contact_email'],
                'phone' => $personData['phone'],
                'city' => $personData['city'],
                'classe' => $personData['classe'],
                'coupondCode' => $coupondCode,
                'otherInfo' => $otherInfo,
                'fileList' => $filesData,
            ])
        ;

        $this->mailer->send($email);

        return $email;
    }

    public function notifyNewAdozioniReservationToBackoffice(array $personData, array $filesData, array $systemData = [], string $otherInfo = '', string $coupondCode = ''): TemplatedEmail
    {
        $replyTo = new Address((string) $personData['contact_email'], \sprintf('%s %s', (string) $personData['lastName'], (string) $personData['firstName']));
        $email = (new TemplatedEmail())
            ->from(new Address($this->sender->address(), $this->sender->name()))
            ->to(new Address($this->backofficeEmailRetriever->address(), $this->backofficeEmailRetriever->name()))
            ->replyTo($replyTo)
            // TODO AGGIUNGERE RIF.ID
            // TODO AGGIUNGER DATA DELLA PRENOTAZIONE
            ->subject(
                \sprintf('Nuova Prenotazione da %s %s', (string) $personData['lastName'], (string) $personData['firstName'])
            )
            ->textTemplate('@booking/email/for-backoffice/new-reservation/new-adozioni-reservation.txt.twig')
            ->context([
                'firstName' => $personData['firstName'],
                'lastName' => $personData['lastName'],
                'contact_email' => $personData['contact_email'],
                'phone' => $personData['phone'],
                'city' => $personData['city'],
                'classe' => $personData['classe'],
                'coupondCode' => $coupondCode,
                'otherInfo' => $otherInfo,
                'fileList' => $filesData,
            ])
        ;

        //file stuff
        /** @var File $file */
        foreach ($filesData as $file) {
            $email->attachFromPath($file->getRealPath());
        }

        $this->mailer->send($email);

        return $email;
    }

    public function notifyReservationThanksEmailToClient(string $recipient, string $reservationId): TemplatedEmail
    {
        $replyTo = new Address($this->backofficeEmailRetriever->address(), $this->backofficeEmailRetriever->name());

        $email = (new TemplatedEmail())
            ->from(new Address($this->sender->address(), $this->sender->name()))
            ->to(new Address($recipient))
            ->replyTo($replyTo)
            ->subject(self::RESERVATION_THANKS_EMAIL_SUBJECT)
            ->htmlTemplate('@booking/email/for-clients/reservation-thanks.html.twig')
            ->context([
            ])
        ;

        $this->mailer->send($email);

        return $email;
    }
}
