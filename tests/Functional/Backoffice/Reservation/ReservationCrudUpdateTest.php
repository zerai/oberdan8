<?php declare(strict_types=1);


namespace App\Tests\Functional\Backoffice\Reservation;

use App\Factory\BookFactory;
use App\Factory\ReservationFactory;
use App\Factory\ReservationSaleDetailFactory;
use App\Tests\Functional\SecurityWebtestCase;
use App\Tests\Support\Fixtures\ReservationStaticFixture;
use Booking\Adapter\Persistance\ReservationRepository;
use Booking\Application\Domain\Model\Reservation;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ReservationCrudUpdateTest extends SecurityWebtestCase
{
    private const REDIRECT_AFTER_SUBMIT = '/admin/prenotazioni/';

    use ResetDatabase;
    use Factories;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function xxxEdit(): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        $this->logInAsAdmin();

        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $crawler = $this->client->request(
            'POST',
            '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit',
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
                    "status" => "NewArrival",
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects(self::REDIRECT_AFTER_SUBMIT . $reservation->getId()->toString());

        $lastName = ReservationStaticFixture::LAST_NAME;
        self::assertGreaterThan(
            0,
            $crawler->filter("#card-person:contains(\"${lastName}\")")->count()
            //$crawler->filter("#card-person:contains(${lastName})")->count()
        );
    }

    /** @test */
    public function shouldEditTheReservationLastName(): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $this->client->submitForm('Invia', [
            'backoffice_reservation[person][last_name]' => ReservationStaticFixture::LAST_NAME,
            'backoffice_reservation[classe]' => 'prima',
            //'backoffice_reservation[books][0][title]' => 'foooo'
        ]);

        self::assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString());

        self::assertResponseIsSuccessful();

        //echo $this->client->getResponse()->getContent();

        $lastName = ReservationStaticFixture::LAST_NAME;
        self::assertGreaterThan(
            0,
            $crawler->filter("html div.card-status-prenotazione:contains(\"${lastName}\")")->count()
        //$crawler->filter("#card-person:contains(${lastName})")->count()
        );
    }

    /** @test */
    public function shouldEditTheReservationFirstName(): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $this->client->submitForm('Invia', [
            'backoffice_reservation[person][first_name]' => ReservationStaticFixture::FIRST_NAME,
            'backoffice_reservation[classe]' => 'prima',
        ]);

        self::assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString());

        self::assertResponseIsSuccessful();

        //echo $this->client->getResponse()->getContent();

        self::assertGreaterThan(
            0,
            $crawler->filter('#card-person:contains("Cognome")')->count()
        );
    }

    /**
     * @test
     */
    public function shouldNotChangeConfirmationStatusWhenEditOtherFields(): void
    {
        /** @var Reservation $reservation *///$reservation = ReservationFactory::createOne()->object();

        $reservation = ReservationFactory::createOne([
            'books' => BookFactory::new()->many(5),
            'saleDetail' => ReservationSaleDetailFactory::new()->withConfirmed1DayAgo()->create(),
        ])->object();

        // /** @var ReservationRepository $reservationRepository */
//        $reservationRepository = static::$kernel->getContainer()->get('Booking\Adapter\Persistance\ReservationRepository');
//        $reservationFromDb = $reservationRepository->withId($reservation->getId());
//        self::assertEquals($reservation->getSaleDetail()->getConfirmationStatus()->confirmedAt(), $reservationFromDb->getSaleDetail()->getConfirmationStatus()->confirmedAt());

        $expectedReservationConfirmationDate = $reservation->getSaleDetail()->getConfirmationStatus()->confirmedAt();

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $this->client->submitForm('Invia', [
            'backoffice_reservation[packageId]' => 'B-23',
        ]);

        self::assertResponseIsSuccessful();

        /** @var ReservationRepository $reservationRepository */
        $reservationRepository = static::$kernel->getContainer()->get('Booking\Adapter\Persistance\ReservationRepository');

        $reservationFromDb = $reservationRepository->withId($reservation->getId());

        self::assertEquals($expectedReservationConfirmationDate, $reservationFromDb->getSaleDetail()->getConfirmationStatus()->confirmedAt());
    }

    /**
     * @test
     * @dataProvider changeReservationStatusDataProvider
     */
    public function shouldEditTheReservationStatusForAReservation(string $newStatus, string $expectedStatusInPage): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $crawler = $this->client->submitForm('Invia', [
            'backoffice_reservation[status]' => $newStatus,
        ]);

        self::assertResponseIsSuccessful();

        self::assertGreaterThan(
            0,
            $crawler->filter("html div.card-status-prenotazione:contains(\"${expectedStatusInPage}\")")->count()
        );
    }

    public function changeReservationStatusDataProvider(): \Generator
    {
        return [
            yield 'InProgress' => ['InProgress', 'In lavorazione'],
            yield 'Pending' => ['Pending', 'In sospeso'],
            yield 'Rejected' => ['Rejected', 'Rifiutato'],
            yield 'Confirmed' => ['Confirmed', 'Confermato'],
            yield 'Sale' => ['Sale', 'Vendita'],
            yield 'PickedUp' => ['PickedUp', 'Ritirato'],
            yield 'Blacklist' => ['Blacklist', 'Blacklist'],
        ];
    }

    /** @test */
    public function shouldEditTheReservationNotesForAReservation(): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $this->client->submitForm('Invia', [
            'backoffice_reservation[notes]' => 'A new notes.',
        ]);

        self::assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString());

        self::assertResponseIsSuccessful();

        self::assertGreaterThan(
            0,
            $crawler->filter('html div.card:contains("A new notes.")')->count()
        );
    }

    /** @test */
    public function shouldEditTheReservationPackageIdForAReservation(): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $this->client->submitForm('Invia', [
            'backoffice_reservation[packageId]' => 'B-23',
        ]);

        self::assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString());

        self::assertResponseIsSuccessful();

        self::assertGreaterThan(
            0,
            $crawler->filter('html div.card:contains("B-23")')->count()
        );
    }
}
