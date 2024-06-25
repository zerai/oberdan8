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
class SendingNewAdozioniReservationConfirmationToClientTest extends TestCase
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

    private const COUPOND_CODE = 'irrelevant';

    private const OTHER_INFO = 'irrelevant';

    private const PDF_FILE_NAME_1 = 'pdf-filename-1.pdf';

    private const PDF_FILE_NAME_2 = 'pdf-filename-2.pdf';

    private const IMAGE_FILE_NAME_1 = 'image-filename-1.pdf';

    private const IMAGE_FILE_NAME_2 = 'image-filename-2.pdf';

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
    public function shouldSendAdozioniReservationConfirmationEmailToClient(): void
    {
        $this->mailer->expects(self::once())
            ->method('send');

        $fileList = [
            self::PDF_FILE_NAME_1,
            self::PDF_FILE_NAME_2,
        ];

        $sendedEmail = $this->bookingMailer->notifyAdozioniReservationConfirmationEmailToClient(
            'example@example.com',
            $this->getPersonData(),
            $fileList,
            self::OTHER_INFO,
            self::COUPOND_CODE
        );

        self::assertSame(self::RESERVATION_CONFIRMATION_EMAIL_SUBJECT, $sendedEmail->getSubject());

        $recipientAddress = $sendedEmail->getTo();
        self::assertSame('example@example.com', $recipientAddress[0]->getAddress());
    }

    /** @test */
    public function email_ShouldHaveOberdanDataAsSender(): void
    {
        $this->mailer->expects(self::once())
            ->method('send');

        $fileList = [
            self::PDF_FILE_NAME_1,
            self::PDF_FILE_NAME_2,
        ];

        $sendedEmail = $this->bookingMailer->notifyAdozioniReservationConfirmationEmailToClient(
            'example@example.com',
            $this->getPersonData(),
            $fileList,
            self::OTHER_INFO
        );

        $senderAddress = $sendedEmail->getFrom();
        self::assertSame(self::MAIL_FROM, $senderAddress[0]->getAddress());
        self::assertSame(self::MAIL_FROM_SHOW_AS, $senderAddress[0]->getName());
    }

    /** @test */
    public function email_shouldHaveOberdanBackofficeDataAsReplyTo(): void
    {
        $this->mailer->expects(self::once())
            ->method('send');

        $fileList = [
            self::PDF_FILE_NAME_1,
            self::PDF_FILE_NAME_2,
        ];

        $sendedEmail = $this->bookingMailer->notifyAdozioniReservationConfirmationEmailToClient(
            'example@example.com',
            $this->getPersonData(),
            $fileList,
            self::OTHER_INFO
        );

        $replyToAddress = $sendedEmail->getReplyTo();
        self::assertNotEmpty($replyToAddress, "'ReplyTo' is missing ");
        self::assertSame(self::BACKOFFICE_RETRIEVER_MAIL, $replyToAddress[0]->getAddress());
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
