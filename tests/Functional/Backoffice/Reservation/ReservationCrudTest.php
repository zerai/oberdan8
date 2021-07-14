<?php declare(strict_types=1);


namespace App\Tests\Functional\Backoffice\Reservation;

use App\Factory\ReservationFactory;
use App\Tests\Functional\SecurityWebtestCase;
use Booking\Application\Domain\Model\Reservation;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ReservationCrudTest extends SecurityWebtestCase
{
    use ResetDatabase;
    use Factories;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function reservationManagerPageShouldBeAccessible(): void
    {
        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/');

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertPageTitleSame('Prenotazioni Administration - Oberdan 8');

        self::assertSelectorTextContains('h1', 'Prenotazioni');
    }

    /** @test */
    public function shouldSeeNoRecordMessageInIndexPage(): void
    {
        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/');

        self::assertResponseIsSuccessful();

        self::assertSelectorTextContains('table', 'Nessun risultato trovato.');
    }

    /** @test */
    public function shouldSeeARecordInIndexPage(): void
    {
        self::markTestSkipped('uuid non più presente in table (truncate uuid), usare altro dato');
        $aReservation = ReservationFactory::createOne();

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/');

        self::assertResponseIsSuccessful();

        self::assertSelectorTextContains('table', $aReservation->getId()->toString());
    }

    /** @test */
    public function shouldSeeMultipleRecordInIndexPage(): void
    {
        ReservationFactory::createMany(5);

        $this->logInAsAdmin();

        $crawler = $this->client->request('GET', '/admin/prenotazioni/');

        self::assertResponseIsSuccessful();

        $this->assertEquals(
            5,
            $crawler->filterXPath('//td//a/i[@class="fa fa-eye"]')->count()
        );
    }

    /** @test */
    public function shouldSeeAPaginatorInIndexPage(): void
    {
        ReservationFactory::createMany(25);

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/');

        self::assertResponseIsSuccessful();

        self::assertSelectorExists('.pagination');
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
