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
    public function shouldEditTheReservationLastName(): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        $lastName = ReservationStaticFixture::LAST_NAME;

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $this->client->submitForm('Invia', [
            'backoffice_reservation[person][last_name]' => $lastName,
        ]);

        self::assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString());

        self::assertResponseIsSuccessful();

        self::assertGreaterThan(
            0,
            $crawler->filter("html div.card-person:contains(\"${lastName}\")")->count()
        );
    }

    /** @test */
    public function shouldEditTheReservationFirstName(): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        $firstName = ReservationStaticFixture::FIRST_NAME;

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $this->client->submitForm('Invia', [
            'backoffice_reservation[person][first_name]' => $firstName,
        ]);

        self::assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString());

        self::assertResponseIsSuccessful();

        self::assertGreaterThan(
            0,
            $crawler->filter("html div.card-person:contains(\"${firstName}\")")->count()
        );
    }

    /** @test */
    public function shouldEditTheReservationEmail(): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        $email = ReservationStaticFixture::EMAIL;

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $this->client->submitForm('Invia', [
            'backoffice_reservation[person][email]' => $email,
        ]);

        self::assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString());

        self::assertResponseIsSuccessful();

        self::assertGreaterThan(
            0,
            $crawler->filter("html div.card-person:contains(\"${email}\")")->count()
        );
    }

    /** @test */
    public function shouldEditTheReservationPhone(): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        $phone = ReservationStaticFixture::PHONE;

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $this->client->submitForm('Invia', [
            'backoffice_reservation[person][phone]' => $phone,
        ]);

        self::assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString());

        self::assertResponseIsSuccessful();

        self::assertGreaterThan(
            0,
            $crawler->filter("html div.card-person:contains(\"${phone}\")")->count()
        );
    }

    /** @test */
    public function shouldEditTheReservationCity(): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        $city = ReservationStaticFixture::CITY;

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $this->client->submitForm('Invia', [
            'backoffice_reservation[person][city]' => $city,
        ]);

        self::assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString());

        self::assertResponseIsSuccessful();

        self::assertGreaterThan(
            0,
            $crawler->filter("html div.card-person:contains(\"${city}\")")->count()
        );
    }

    /** @test */
    public function shouldEditTheReservationClasse(): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        $classe = 'terza';

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $this->client->submitForm('Invia', [
            'backoffice_reservation[classe]' => $classe,
        ]);

        self::assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString());

        self::assertResponseIsSuccessful();

        self::assertGreaterThan(
            0,
            $crawler->filter("html div.card-person:contains(\"${classe}\")")->count()
        );
    }

    /**
     * @test
     */
    public function shouldNotChangeConfirmationStatusWhenEditOtherFields(): void
    {
        /** @var Reservation $reservation */ //$reservation = ReservationFactory::createOne()->object();

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
        $reservationRepository = static::$kernel->getContainer()->get(ReservationRepository::class);

        $reservationFromDb = $reservationRepository->withId($reservation->getId());

        self::assertEquals($expectedReservationConfirmationDate, $reservationFromDb->getSaleDetail()->getConfirmationStatus()->confirmedAt());
    }

    /**
     * @test
     * @testWith ["InProgress", "In lavorazione"]
     *          ["Pending", "In sospeso"]
     *          ["Rejected", "Rifiutato"]
     *          ["Confirmed", "Confermato"]
     *          ["Sale", "Vendita"]
     *          ["PickedUp", "Ritirato"]
     *          ["Blacklist", "Blacklist"]
     *          ["Shipped", "Spedito"]
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
            'backoffice_reservation[generalNotes]' => 'A new notes.',
        ]);

        self::assertResponseIsSuccessful();

        $crawler = $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString());

        self::assertResponseIsSuccessful();

        self::assertGreaterThan(
            0,
            $crawler->filter('html div.card-general-notes:contains("A new notes.")')->count()
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
            $crawler->filter('html div.card-package-id:contains("B-23")')->count()
        );
    }
}
