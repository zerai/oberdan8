<?php declare(strict_types=1);

namespace Booking\Tests\Unit\Adapter;

use Booking\Adapter\MailDriven\BookingMailer;
use Booking\Infrastructure\BackofficeEmailRetriever;
use Booking\Infrastructure\BookingEmailSender;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * @covers \Booking\Adapter\MailDriven\BookingMailer
 */
class BookingMailerTest extends TestCase
{
    private const MAIL_FROM = 'info@oberdan8.it';

    private const MAIL_FROM_SHOW_AS = 'Oberdan 8';

    private const BACKOFFICE_RETRIEVER_MAIL = 'info@oberdan8.it';

    private const BACKOFFICE_RETRIEVER_AS = 'Gestione prenotazioni';

    private const RESERVATION_CONFIRMATION_EMAIL_SUBJECT = 'Oberdan 8: Prenotazione ricevuta';

    private const FIRST_NAME = 'irrelevant';

    private const LAST_NAME = 'irrelevant';

    private const CONTACT_EMAIL = 'irrelevant@example.com';

    private const PHONE = 'irrelevant';

    private const CITY = 'irrelevant';

    private const CLASSE = 'irrelevant';

    /** @test */
    public function shouldSendReservationConfirmationEmail(): void
    {
        $sender = new BookingEmailSender(self::MAIL_FROM, self::MAIL_FROM_SHOW_AS);
        $backofficeRetriever = new BackofficeEmailRetriever(self::BACKOFFICE_RETRIEVER_MAIL, self::BACKOFFICE_RETRIEVER_AS);
        $symfonyMailer = $this->createMock(MailerInterface::class);
        $symfonyMailer->expects(self::once())
            ->method('send');
        $bookingMailer = new BookingMailer(
            $symfonyMailer,
            $sender,
            $backofficeRetriever
        );

        $sendedEmail = $bookingMailer->sendReservationConfirmationEmailToClient('example@example.com', $this->getPersonData(), []);

        self::assertSame(self::RESERVATION_CONFIRMATION_EMAIL_SUBJECT, $sendedEmail->getSubject());

        $recipientAddress = $sendedEmail->getTo();
        self::assertInstanceOf(Address::class, $recipientAddress[0]);
        self::assertSame('example@example.com', $recipientAddress[0]->getAddress());
    }

    /** @test */
    public function reservationConfirmationEmail_shouldHaveOberdanDataAsSender(): void
    {
        $sender = new BookingEmailSender(self::MAIL_FROM, self::MAIL_FROM_SHOW_AS);
        $backofficeRetriever = new BackofficeEmailRetriever(self::BACKOFFICE_RETRIEVER_MAIL, self::BACKOFFICE_RETRIEVER_AS);
        $symfonyMailer = $this->createMock(MailerInterface::class);
        $bookingMailer = new BookingMailer(
            $symfonyMailer,
            $sender,
            $backofficeRetriever
        );

        $sendedEmail = $bookingMailer->sendReservationConfirmationEmailToClient('example@example.com', $this->getPersonData(), []);

        $senderAddress = $sendedEmail->getFrom();
        self::assertInstanceOf(Address::class, $senderAddress[0]);
        self::assertSame(self::MAIL_FROM, $senderAddress[0]->getAddress());
        self::assertSame(self::MAIL_FROM_SHOW_AS, $senderAddress[0]->getName());
    }

    /** @test */
    public function shouldSendNewReservationEmailToBackoffice(): void
    {
        $sender = new BookingEmailSender(self::MAIL_FROM, self::MAIL_FROM_SHOW_AS);
        $backofficeRetriever = new BackofficeEmailRetriever(self::BACKOFFICE_RETRIEVER_MAIL, self::BACKOFFICE_RETRIEVER_AS);
        $symfonyMailer = $this->createMock(MailerInterface::class);
        $symfonyMailer->expects(self::once())
            ->method('send');
        $bookingMailer = new BookingMailer(
            $symfonyMailer,
            $sender,
            $backofficeRetriever
        );

        $sendedEmail = $bookingMailer->notifyNewReservationToBackoffice($this->getPersonData(), []);

        $expectedSubject = sprintf('Nuova Prenotazione da %s %s', $this->getPersonData()['lastName'], $this->getPersonData()['firstName']);
        self::assertEquals($expectedSubject, $sendedEmail->getSubject());

        $recipientAddress = $sendedEmail->getTo();
        self::assertInstanceOf(Address::class, $recipientAddress[0]);
        self::assertSame(self::BACKOFFICE_RETRIEVER_MAIL, $recipientAddress[0]->getAddress());
    }

    private function getPersonData(): array
    {
        return [
            'firstName' => self::FIRST_NAME,
            'lastName' => self::LAST_NAME,
            'contact_email' => self::CONTACT_EMAIL,
            'phone' => self::PHONE,
            'city' => self::CITY,
            'classe' => self::CLASSE,
        ];
    }
}
