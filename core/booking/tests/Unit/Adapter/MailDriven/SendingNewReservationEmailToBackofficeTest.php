<?php declare(strict_types=1);

namespace Booking\Tests\Unit\Adapter\MailDriven;

use Booking\Adapter\MailDriven\BookingMailer;
use Booking\Infrastructure\BackofficeEmailRetriever;
use Booking\Infrastructure\BookingEmailSender;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;

/**
 * @covers \Booking\Adapter\MailDriven\BookingMailer
 */
class SendingNewReservationEmailToBackofficeTest extends TestCase
{
    private const MAIL_FROM = 'info@oberdan8.it';

    private const MAIL_FROM_SHOW_AS = 'Oberdan 8';

    private const BACKOFFICE_RETRIEVER_MAIL = 'info@oberdan8.it';

    private const BACKOFFICE_RETRIEVER_AS = 'Gestione prenotazioni';

    private const RESERVATION_CONFIRMATION_EMAIL_SUBJECT = 'Oberdan 8: Prenotazione ricevuta';

    private const RESERVATION_THANKS_EMAIL_SUBJECT = 'Oberdan 8 Ti ringraziamo per averci scelto';

    private const FIRST_NAME = 'irrelevant';

    private const LAST_NAME = 'irrelevant';

    private const CONTACT_EMAIL = 'irrelevant@example.com';

    private const PHONE = 'irrelevant';

    private const CITY = 'irrelevant';

    private const CLASSE = 'irrelevant';

    private const OTHER_INFO = 'irrelevant';

    /** @var MailerInterface & MockObject */
    private $mailer;

    private BookingMailer $bookingMailer;

    protected function setUp(): void
    {
        $this->mailer = $this->createMock(MailerInterface::class);
        $sender = new BookingEmailSender(self::MAIL_FROM, self::MAIL_FROM_SHOW_AS);
        $backofficeRetriever = new BackofficeEmailRetriever(self::BACKOFFICE_RETRIEVER_MAIL, self::BACKOFFICE_RETRIEVER_AS);
        $this->bookingMailer = new BookingMailer(
            $this->mailer,
            $sender,
            $backofficeRetriever
        );
    }

    /** @test */
    public function shouldSendNewReservationEmailToBackoffice(): void
    {
        $this->mailer->expects(self::once())
            ->method('send');

        $sendedEmail = $this->bookingMailer->notifyNewReservationToBackoffice($this->getPersonData(), [], [], self::OTHER_INFO);

        $expectedSubject = sprintf('Nuova Prenotazione da %s %s', $this->getPersonData()['lastName'], $this->getPersonData()['firstName']);
        self::assertEquals($expectedSubject, $sendedEmail->getSubject());

        $recipientAddress = $sendedEmail->getTo();
        self::assertSame(self::BACKOFFICE_RETRIEVER_MAIL, $recipientAddress[0]->getAddress());
        self::assertSame(self::CONTACT_EMAIL, $sendedEmail->getReplyTo()[0]->getAddress());
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
