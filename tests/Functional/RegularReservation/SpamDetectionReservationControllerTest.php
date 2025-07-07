<?php declare(strict_types=1);

namespace App\Tests\Functional\RegularReservation;

use App\Tests\Support\Fixtures\ReservationStaticFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class SpamDetectionReservationControllerTest extends WebTestCase
{
    private const TARGET_PAGE_WITH_FORM = '/reservation';

    /**
     * @test
     * @dataProvider invalidLastNameDataProvider
     */
    public function detect_spam_in_last_name(string $lastNameValue = '', string $formErrorMessage = ''): void
    {
        $client = static::createClient();
        $client->followRedirects();

        /** @var RateLimiterFactory $limiter */
        $limiter = $client->getContainer()->get('limiter.reservation_forms');

        $formRateLimiter = $limiter->create('127.0.0.1');

        $formRateLimiter->reset();

        //$csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $client->request(
            Request::METHOD_POST,
            self::TARGET_PAGE_WITH_FORM,
            [
                'reservation' => [
                    'person' => [
                        "last_name" => $lastNameValue,
                        "first_name" => ReservationStaticFixture::FIRST_NAME,
                        "email" => ReservationStaticFixture::EMAIL,
                        "phone" => ReservationStaticFixture::PHONE,
                        "city" => ReservationStaticFixture::CITY,
                    ],
                    'classe' => ReservationStaticFixture::CLASSE,
                    'books' => [
                        [
                            "isbn" => ReservationStaticFixture::BOOK_ONE_ISBN,
                            "title" => ReservationStaticFixture::BOOK_ONE_TITLE,
                            "author" => ReservationStaticFixture::BOOK_ONE_AUTHOR,
                            "volume" => ReservationStaticFixture::BOOK_ONE_VOLUME,
                        ],
                        [
                            "isbn" => ReservationStaticFixture::BOOK_TWO_ISBN,
                            "title" => ReservationStaticFixture::BOOK_TWO_TITLE,
                            "author" => ReservationStaticFixture::BOOK_TWO_AUTHOR,
                            "volume" => ReservationStaticFixture::BOOK_TWO_VOLUME,
                        ],                    ],
                    "otherInfo" => "Vorrei sapere di che anno è la vostra edizione",
                    "coupondCode" => ReservationStaticFixture::COUPOND_CODE,
                    "privacyConfirmed" => "1",
                    "submit" => "",
                    //"_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        //self::assertResponseRedirects(self::REDIRECT_AFTER_SUBMIT);
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Oberdan - banco 8 - prenotazioni');
        //self::assertStringNotContainsString('Invia un\'altra prenotazione', $client->getResponse()->getContent());
        self::assertStringContainsString($formErrorMessage, $client->getResponse()->getContent());
    }

    /**
     * @test
     * @dataProvider invalidFirstNameDataProvider
     */
    public function detect_spam_in_first_name(string $firstNameValue = '', string $formErrorMessage = ''): void
    {
        $client = static::createClient();
        $client->followRedirects();

        /** @var RateLimiterFactory $limiter */
        $limiter = $client->getContainer()->get('limiter.reservation_forms');

        $formRateLimiter = $limiter->create('127.0.0.1');

        $formRateLimiter->reset();

        //$csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $client->request(
            Request::METHOD_POST,
            self::TARGET_PAGE_WITH_FORM,
            [
                'reservation' => [
                    'person' => [
                        "last_name" => ReservationStaticFixture::LAST_NAME,
                        "first_name" => $firstNameValue,
                        "email" => ReservationStaticFixture::EMAIL,
                        "phone" => ReservationStaticFixture::PHONE,
                        "city" => ReservationStaticFixture::CITY,
                    ],
                    'classe' => ReservationStaticFixture::CLASSE,
                    'books' => [
                        [
                            "isbn" => ReservationStaticFixture::BOOK_ONE_ISBN,
                            "title" => ReservationStaticFixture::BOOK_ONE_TITLE,
                            "author" => ReservationStaticFixture::BOOK_ONE_AUTHOR,
                            "volume" => ReservationStaticFixture::BOOK_ONE_VOLUME,
                        ],
                        [
                            "isbn" => ReservationStaticFixture::BOOK_TWO_ISBN,
                            "title" => ReservationStaticFixture::BOOK_TWO_TITLE,
                            "author" => ReservationStaticFixture::BOOK_TWO_AUTHOR,
                            "volume" => ReservationStaticFixture::BOOK_TWO_VOLUME,
                        ],                    ],
                    "otherInfo" => "Vorrei sapere di che anno è la vostra edizione",
                    "coupondCode" => ReservationStaticFixture::COUPOND_CODE,
                    "privacyConfirmed" => "1",
                    "submit" => "",
                    //"_token" => $csrfToken->getValue(),
                ],
            ],
            [],
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
        $client->followRedirects();

        /** @var RateLimiterFactory $limiter */
        $limiter = $client->getContainer()->get('limiter.reservation_forms');

        $formRateLimiter = $limiter->create('127.0.0.1');

        $formRateLimiter->reset();

        //$csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $client->request(
            Request::METHOD_POST,
            self::TARGET_PAGE_WITH_FORM,
            [
                'reservation' => [
                    'person' => [
                        "last_name" => ReservationStaticFixture::LAST_NAME,
                        "first_name" => ReservationStaticFixture::FIRST_NAME,
                        "email" => ReservationStaticFixture::EMAIL,
                        "phone" => ReservationStaticFixture::PHONE,
                        "city" => ReservationStaticFixture::CITY,
                    ],
                    'classe' => ReservationStaticFixture::CLASSE,
                    'books' => [
                        [
                            "isbn" => ReservationStaticFixture::BOOK_ONE_ISBN,
                            "title" => ReservationStaticFixture::BOOK_ONE_TITLE,
                            "author" => ReservationStaticFixture::BOOK_ONE_AUTHOR,
                            "volume" => ReservationStaticFixture::BOOK_ONE_VOLUME,
                        ],
                        [
                            "isbn" => ReservationStaticFixture::BOOK_TWO_ISBN,
                            "title" => ReservationStaticFixture::BOOK_TWO_TITLE,
                            "author" => ReservationStaticFixture::BOOK_TWO_AUTHOR,
                            "volume" => ReservationStaticFixture::BOOK_TWO_VOLUME,
                        ],                    ],
                    "otherInfo" => $notesValue,
                    "coupondCode" => ReservationStaticFixture::COUPOND_CODE,
                    "privacyConfirmed" => "1",
                    "submit" => "",
                    //"_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Oberdan - banco 8 - prenotazioni');
        self::assertStringNotContainsString('Invia un\'altra prenotazione', $client->getResponse()->getContent());
        self::assertStringContainsString($formErrorMessage, $client->getResponse()->getContent());
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
