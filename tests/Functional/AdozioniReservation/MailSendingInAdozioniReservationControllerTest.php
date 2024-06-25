<?php declare(strict_types=1);

namespace App\Tests\Functional\AdozioniReservation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class MailSendingInAdozioniReservationControllerTest extends WebTestCase
{
    private const BACKOFFICE_EMAIL_ADDRESS = 'Gestione prenotazioni <info@oberdan8.it>';

    private const FIRST_NAME = 'Carlo';

    private const LAST_NAME = 'Rossi';

    private const EMAIL = 'carlorossi@example.it';

    private const PHONE = '+39 392 111111';

    private const CITY = 'Roma';

    private const CLASSE = 'prima';

    private const COUPOND_CODE = 'ABCDEF';

    private const NOTES = 'Avrei una certa urgenza di ricevere una vostra risposta';

    private const PDF_FILE_1 = 'RMPC00500D_3A-NT-LI01-UNDEF.pdf';

    /** @test */
    public function afterFormSubmit_shouldSendTwoEmail(): void
    {
        $client = static::createClient();

        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('adozioni_reservation');

        $this->prepareFileFixture(self::PDF_FILE_1);

        $pdfFile = new UploadedFile(__DIR__ . '/RMPC00500D_3A-NT-LI01-UNDEF.pdf', 'RMPC00500D_3A-NT-LI01-UNDEF.pdf', 'application/pdf', null, true);

        $client->request(
            Request::METHOD_POST,
            '/reservation/adozioni',
            [
                'adozioni_reservation' => [
                    'person' => [
                        "last_name" => self::LAST_NAME,
                        "first_name" => self::FIRST_NAME,
                        "email" => self::EMAIL,
                        "phone" => self::PHONE,
                        "city" => self::CITY,
                    ],
                    'classe' => self::CLASSE,
                    'coupondCode' => self::COUPOND_CODE,
                    "otherInfo" => "Vorrei sapere di che anno è la vostra edizione.",
                    "privacyConfirmed" => "1",
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            // UPLOADED FILES
            [
                'adozioni_reservation' => [
                    'adozioni' => $pdfFile,
                ],
            ],
        );

        self::assertResponseRedirects('/esito');

        self::assertEmailCount(2);
    }

    /** @test */
    public function afterFormSubmit_shouldSendAReservationConfirmationEmailToClient(): void
    {
        $client = static::createClient();

        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('adozioni_reservation');

        $this->prepareFileFixture(self::PDF_FILE_1);

        $pdfFile = new UploadedFile(__DIR__ . '/RMPC00500D_3A-NT-LI01-UNDEF.pdf', 'RMPC00500D_3A-NT-LI01-UNDEF.pdf', 'application/pdf', null, true);

        $client->request(
            Request::METHOD_POST,
            '/reservation/adozioni',
            [
                'adozioni_reservation' => [
                    'person' => [
                        "last_name" => self::LAST_NAME,
                        "first_name" => self::FIRST_NAME,
                        "email" => self::EMAIL,
                        "phone" => self::PHONE,
                        "city" => self::CITY,
                    ],
                    'classe' => self::CLASSE,
                    'coupondCode' => self::COUPOND_CODE,
                    "otherInfo" => "Vorrei sapere di che anno è la vostra edizione.",
                    "privacyConfirmed" => "1",
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            // UPLOADED FILES
            [
                'adozioni_reservation' => [
                    'adozioni' => $pdfFile,
                ],
            ],
        );

        self::assertResponseRedirects('/esito');

        self::assertEmailCount(2);

        $email = self::getMailerMessage(0);
        self::assertEmailHeaderSame($email, 'To', self::EMAIL);
        self::assertEmailTextBodyContains($email, self::LAST_NAME);
        self::assertEmailTextBodyContains($email, self::FIRST_NAME);
        self::assertEmailTextBodyContains($email, self::EMAIL);
        self::assertEmailTextBodyContains($email, self::PHONE);
        self::assertEmailTextBodyContains($email, self::COUPOND_CODE);
        // TODO image attach
        //self::assertEmailAttachmentCount($email, 0);
    }

    /** @test */
    public function afterFormSubmit_shouldSendANewReservationEmailToBackoffice(): void
    {
        $client = static::createClient();

        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('adozioni_reservation');

        $this->prepareFileFixture(self::PDF_FILE_1);

        $pdfFile = new UploadedFile(__DIR__ . '/RMPC00500D_3A-NT-LI01-UNDEF.pdf', 'RMPC00500D_3A-NT-LI01-UNDEF.pdf', 'application/pdf', null, true);

        $client->request(
            Request::METHOD_POST,
            '/reservation/adozioni',
            [
                'adozioni_reservation' => [
                    'person' => [
                        "last_name" => self::LAST_NAME,
                        "first_name" => self::FIRST_NAME,
                        "email" => self::EMAIL,
                        "phone" => self::PHONE,
                        "city" => self::CITY,
                    ],
                    'classe' => self::CLASSE,
                    'coupondCode' => self::COUPOND_CODE,
                    "otherInfo" => "Vorrei sapere di che anno è la vostra edizione.",
                    "privacyConfirmed" => "1",
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            // UPLOADED FILES
            [
                'adozioni_reservation' => [
                    'adozioni' => $pdfFile,
                ],
            ],
        );

        self::assertResponseRedirects('/esito');

        self::assertEmailCount(2);

        $email = self::getMailerMessage(1);
        self::assertEmailHeaderSame($email, 'To', self::BACKOFFICE_EMAIL_ADDRESS);
        self::assertEmailTextBodyContains($email, self::LAST_NAME);
        self::assertEmailTextBodyContains($email, self::FIRST_NAME);
        self::assertEmailTextBodyContains($email, self::EMAIL);
        self::assertEmailTextBodyContains($email, self::PHONE);
        self::assertEmailTextBodyContains($email, self::COUPOND_CODE);
        self::assertEmailAttachmentCount($email, 1);
    }

    private function prepareFileFixture(string $fileName): void
    {
        $imagePath = __DIR__ . "/FileFixtures/" . $fileName;
        $newPath = __DIR__ . '/' . $fileName;

        copy($imagePath, $newPath);
    }
}
