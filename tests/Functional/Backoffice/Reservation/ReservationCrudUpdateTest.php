<?php declare(strict_types=1);


namespace App\Tests\Functional\Backoffice\Reservation;

use App\Factory\BookFactory;
use App\Factory\ReservationFactory;
use App\Factory\ReservationSaleDetailFactory;
use App\Tests\Functional\SecurityWebtestCase;
use Booking\Adapter\Persistance\ReservationRepository;
use Booking\Application\Domain\Model\Reservation;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ReservationCrudUpdateTest extends SecurityWebtestCase
{
    use ResetDatabase;
    use Factories;

    public function setUp(): void
    {
        parent::setUp();
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
            'backoffice_reservation_edit[packageId]' => 'B-23',
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
            'backoffice_reservation_edit[status]' => $newStatus,
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
            'backoffice_reservation_edit[notes]' => 'A new notes.',
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
            'backoffice_reservation_edit[packageId]' => 'B-23',
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
