<?php declare(strict_types=1);

namespace App\Tests\Functional\AdozioniReservation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class SpamDetectionOnAdozioniReservationControllerTest extends WebTestCase
{
    private const TARGET_PAGE_WITH_FORM = '/reservation/adozioni';

    private const FIRST_NAME = 'Carlo';

    private const LAST_NAME = 'Rossi';

    private const EMAIL = 'carlorossi@example.it';

    private const PHONE = '+39 392 111111';

    private const CITY = 'Roma';

    private const CLASSE = 'prima';

    private const COUPOND_CODE = 'ABCDEF';

    private const PDF_FILE_1 = 'RMPC00500D_3A-NT-LI01-UNDEF.pdf';

    /**
     * @test
     * @dataProvider invalidLastNameDataProvider
     */
    public function detect_spam_in_last_name(string $lastNameValue = '', string $formErrorMessage = ''): void
    {
        $client = static::createClient();

        /** @var RateLimiterFactory $limiter */
        $limiter = $client->getContainer()->get('limiter.reservation_forms');

        $formRateLimiter = $limiter->create('127.0.0.1');

        $formRateLimiter->reset();


        //$csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('adozioni_reservation');

        $this->prepareFileFixture(self::PDF_FILE_1);

        $pdfFile = new UploadedFile(__DIR__ . '/RMPC00500D_3A-NT-LI01-UNDEF.pdf', 'RMPC00500D_3A-NT-LI01-UNDEF.pdf', 'application/pdf', null, true);

        $client->request(
            Request::METHOD_POST,
            self::TARGET_PAGE_WITH_FORM,
            [
                'adozioni_reservation' => [
                    'person' => [
                        "last_name" => $lastNameValue,
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
                    //"_token" => $csrfToken->getValue(),
                ],
            ],
            // UPLOADED FILES
            [
                'adozioni_reservation' => [
                    'adozioni' => $pdfFile,
                ],
            ],
        );

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Oberdan - banco 8 - prenotazioni');
        self::assertStringNotContainsString('Invia un\'altra prenotazione', $client->getResponse()->getContent());
        self::assertStringContainsString($formErrorMessage, $client->getResponse()->getContent());
    }

    /**
     * @test
     * @dataProvider invalidFirstNameDataProvider
     */
    public function detect_spam_in_first_name(string $firstNameValue = '', string $formErrorMessage = ''): void
    {
        $client = static::createClient();

        /** @var RateLimiterFactory $limiter */
        $limiter = $client->getContainer()->get('limiter.reservation_forms');

        $formRateLimiter = $limiter->create('127.0.0.1');

        $formRateLimiter->reset();


        //$csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('adozioni_reservation');

        $this->prepareFileFixture(self::PDF_FILE_1);

        $pdfFile = new UploadedFile(__DIR__ . '/RMPC00500D_3A-NT-LI01-UNDEF.pdf', 'RMPC00500D_3A-NT-LI01-UNDEF.pdf', 'application/pdf', null, true);

        $client->request(
            Request::METHOD_POST,
            self::TARGET_PAGE_WITH_FORM,
            [
                'adozioni_reservation' => [
                    'person' => [
                        "last_name" => self::LAST_NAME,
                        "first_name" => $firstNameValue,
                        "email" => self::EMAIL,
                        "phone" => self::PHONE,
                        "city" => self::CITY,
                    ],
                    'classe' => self::CLASSE,
                    'coupondCode' => self::COUPOND_CODE,
                    "otherInfo" => "Vorrei sapere di che anno è la vostra edizione.",
                    "privacyConfirmed" => "1",
                    "submit" => "",
                    //"_token" => $csrfToken->getValue(),
                ],
            ],
            // UPLOADED FILES
            [
                'adozioni_reservation' => [
                    'adozioni' => $pdfFile,
                ],
            ],
        );

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Oberdan - banco 8 - prenotazioni');
        self::assertStringNotContainsString('Invia un\'altra prenotazione', $client->getResponse()->getContent());
        self::assertStringContainsString($formErrorMessage, $client->getResponse()->getContent());
    }

    /**
     * @test
     * @dataProvider invalidNotesDataProvider
     */
    public function detect_spam_in_notes(string $notesValue = '', string $formErrorMessage = ''): void
    {
        $client = static::createClient();

        /** @var RateLimiterFactory $limiter */
        $limiter = $client->getContainer()->get('limiter.reservation_forms');

        $formRateLimiter = $limiter->create('127.0.0.1');

        $formRateLimiter->reset();


        //$csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('adozioni_reservation');

        $this->prepareFileFixture(self::PDF_FILE_1);

        $pdfFile = new UploadedFile(__DIR__ . '/RMPC00500D_3A-NT-LI01-UNDEF.pdf', 'RMPC00500D_3A-NT-LI01-UNDEF.pdf', 'application/pdf', null, true);

        $client->request(
            Request::METHOD_POST,
            self::TARGET_PAGE_WITH_FORM,
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
                    "otherInfo" => $notesValue,
                    "privacyConfirmed" => "1",
                    "submit" => "",
                    //"_token" => $csrfToken->getValue(),
                ],
            ],
            // UPLOADED FILES
            [
                'adozioni_reservation' => [
                    'adozioni' => $pdfFile,
                ],
            ],
        );

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Oberdan - banco 8 - prenotazioni');
        self::assertStringNotContainsString('Invia un\'altra prenotazione', $client->getResponse()->getContent());
        self::assertStringContainsString($formErrorMessage, $client->getResponse()->getContent());
    }

    private function prepareFileFixture(string $fileName): void
    {
        $imagePath = __DIR__ . "/FileFixtures/" . $fileName;
        $newPath = __DIR__ . '/' . $fileName;

        copy($imagePath, $newPath);
    }

    public function invalidFirstNameDataProvider(): array
    {
        return [
            [
                'testo irrilevante 34',
                'Il nome non può contenere numeri',
            ],
            [
                'testo irrilevante *',
                'Il nome non può contenere simboli',
            ],
            [
                'testo irrilevante *** altro testo',
                'Il nome non può contenere simboli',
            ],
            [
                'testo irrilevante http:// con link',
                'Il nome non può contenere simboli',
            ],
            [
                'testo irrilevante http://droneservisleri.com/index.php?k248e8',
                'Il nome non può contenere simboli',
            ],
        ];
    }

    public function invalidLastNameDataProvider(): array
    {
        return [
            [
                'testo irrilevante 34',
                'Il cognome non può contenere numeri',
            ],
            [
                'testo irrilevante *',
                'Il cognome non può contenere simboli',
            ],
            [
                'testo irrilevante *** altro testo',
                'Il cognome non può contenere simboli',
            ],
            [
                'testo irrilevante http:// con link',
                'Il cognome non può contenere simboli',
            ],
            [
                'testo irrilevante http://droneservisleri.com/index.php?k248e8',
                'Il cognome non può contenere simboli',
            ],
        ];
    }

    public function invalidNotesDataProvider(): array
    {
        return [
            //            [
            //                'testo irrilevante 34',
            //                'Il cognome non può contenere numeri',
            //            ],
            [
                'testo irrilevante *',
                'Il campo altre informazioni non può contenere simboli',
            ],
            [
                'testo irrilevante *** altro testo',
                'Il campo altre informazioni non può contenere simboli',
            ],
            [
                'testo irrilevante http:// con link',
                'Il campo altre informazioni non può contenere simboli',
            ],
            [
                'testo irrilevante http://droneservisleri.com/index.php?k248e8',
                'Il campo altre informazioni non può contenere simboli',
            ],
        ];
    }
}
