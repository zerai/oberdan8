<?php declare(strict_types=1);


namespace App\Tests\Functional\Backoffice\Reservation;

use App\Tests\Functional\SecurityWebtestCase;
use App\Tests\Support\Fixtures\ReservationStaticFixture;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ReservationCrudCreateTest extends SecurityWebtestCase
{
    private const REDIRECT_AFTER_SUBMIT = '/admin/prenotazioni/';

    use ResetDatabase;

    use Factories;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function canSendFormWithFullData(): void
    {
        $this->logInAsAdmin();

        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $this->client->request(
            'POST',
            '/admin/prenotazioni/new',
            [
                'backoffice_reservation' => [
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
                    "generalNotes" => "Vorrei sapere di che anno è la vostra edizione.",
                    "packageId" => ReservationStaticFixture::PACKAGE_ID,
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects(self::REDIRECT_AFTER_SUBMIT);
    }

    /** @test */
    public function canSendFormWithoutLastName(): void
    {
        $this->logInAsAdmin();

        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $this->client->request(
            'POST',
            '/admin/prenotazioni/new',
            [
                'backoffice_reservation' => [
                    'person' => [
                        "last_name" => '',
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
                    "generalNotes" => "Vorrei sapere di che anno è la vostra edizione.",
                    "packageId" => ReservationStaticFixture::PACKAGE_ID,
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects(self::REDIRECT_AFTER_SUBMIT);
    }

    /** @test */
    public function canSendFormWithoutFirstName(): void
    {
        $this->logInAsAdmin();

        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $this->client->request(
            'POST',
            '/admin/prenotazioni/new',
            [
                'backoffice_reservation' => [
                    'person' => [
                        "last_name" => ReservationStaticFixture::LAST_NAME,
                        "first_name" => '',
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
                    "generalNotes" => "Vorrei sapere di che anno è la vostra edizione.",
                    "packageId" => ReservationStaticFixture::PACKAGE_ID,
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects(self::REDIRECT_AFTER_SUBMIT);
    }

    /** @test */
    public function canSendFormWithoutEmail(): void
    {
        $this->logInAsAdmin();

        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $this->client->request(
            'POST',
            '/admin/prenotazioni/new',
            [
                'backoffice_reservation' => [
                    'person' => [
                        "last_name" => ReservationStaticFixture::LAST_NAME,
                        "first_name" => ReservationStaticFixture::FIRST_NAME,
                        "email" => '',
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
                    "generalNotes" => "Vorrei sapere di che anno è la vostra edizione.",
                    "packageId" => ReservationStaticFixture::PACKAGE_ID,
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects(self::REDIRECT_AFTER_SUBMIT);
    }

    /** @test */
    public function canSendFormWithoutPhone(): void
    {
        $this->logInAsAdmin();

        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $this->client->request(
            'POST',
            '/admin/prenotazioni/new',
            [
                'backoffice_reservation' => [
                    'person' => [
                        "last_name" => ReservationStaticFixture::LAST_NAME,
                        "first_name" => ReservationStaticFixture::FIRST_NAME,
                        "email" => ReservationStaticFixture::EMAIL,
                        "phone" => '',
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
                    "generalNotes" => "Vorrei sapere di che anno è la vostra edizione.",
                    "packageId" => ReservationStaticFixture::PACKAGE_ID,
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects(self::REDIRECT_AFTER_SUBMIT);
    }

    /** @test */
    public function canSendFormWithoutCity(): void
    {
        $this->logInAsAdmin();

        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $this->client->request(
            'POST',
            '/admin/prenotazioni/new',
            [
                'backoffice_reservation' => [
                    'person' => [
                        "last_name" => ReservationStaticFixture::LAST_NAME,
                        "first_name" => ReservationStaticFixture::FIRST_NAME,
                        "email" => ReservationStaticFixture::EMAIL,
                        "phone" => ReservationStaticFixture::PHONE,
                        "city" => '',
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
                    "generalNotes" => "Vorrei sapere di che anno è la vostra edizione.",
                    "packageId" => ReservationStaticFixture::PACKAGE_ID,
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects(self::REDIRECT_AFTER_SUBMIT);
    }

    /** @test */
    public function canSendFormWithoutClasse(): void
    {
        //self::markTestIncomplete();
        $this->logInAsAdmin();

        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $this->client->request(
            'POST',
            '/admin/prenotazioni/new',
            [
                'backoffice_reservation' => [
                    'person' => [
                        "last_name" => ReservationStaticFixture::LAST_NAME,
                        "first_name" => ReservationStaticFixture::FIRST_NAME,
                        "email" => ReservationStaticFixture::EMAIL,
                        "phone" => ReservationStaticFixture::PHONE,
                        "city" => ReservationStaticFixture::CITY,
                    ],
                    // 'non disponibile' is default in form
                    'classe' => 'non disponibile',
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
                    "generalNotes" => "Vorrei sapere di che anno è la vostra edizione.",
                    "packageId" => ReservationStaticFixture::PACKAGE_ID,
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects(self::REDIRECT_AFTER_SUBMIT);
    }

    /** @test */
    public function canSendFormWithoutGeneralNotes(): void
    {
        $this->logInAsAdmin();

        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $this->client->request(
            'POST',
            '/admin/prenotazioni/new',
            [
                'backoffice_reservation' => [
                    'person' => [
                        "last_name" => ReservationStaticFixture::LAST_NAME,
                        "first_name" => ReservationStaticFixture::FIRST_NAME,
                        "email" => ReservationStaticFixture::EMAIL,
                        "phone" => ReservationStaticFixture::PHONE,
                        "city" => '',
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
                    "generalNotes" => "",
                    "packageId" => ReservationStaticFixture::PACKAGE_ID,
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects(self::REDIRECT_AFTER_SUBMIT);
    }

    /** @test */
    public function canSendFormWithoutPackageId(): void
    {
        $this->logInAsAdmin();

        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $this->client->request(
            'POST',
            '/admin/prenotazioni/new',
            [
                'backoffice_reservation' => [
                    'person' => [
                        "last_name" => ReservationStaticFixture::LAST_NAME,
                        "first_name" => ReservationStaticFixture::FIRST_NAME,
                        "email" => ReservationStaticFixture::EMAIL,
                        "phone" => ReservationStaticFixture::PHONE,
                        "city" => '',
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
                    "generalNotes" => "Vorrei sapere di che anno è la vostra edizione.",
                    "packageId" => "",
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects(self::REDIRECT_AFTER_SUBMIT);
    }
}
