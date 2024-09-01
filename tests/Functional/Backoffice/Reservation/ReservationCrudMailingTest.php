<?php declare(strict_types=1);


namespace App\Tests\Functional\Backoffice\Reservation;

use App\Factory\ReservationFactory;
use App\Tests\Functional\SecurityWebtestCase;
use Booking\Adapter\Persistence\ReservationRepository;
use Booking\Application\Domain\Model\Reservation;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ReservationCrudMailingTest extends SecurityWebtestCase
{
    use ResetDatabase;

    use Factories;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function shouldSendThanksMailWhenMovedToStatusPikedUp(string $pickedUpStatus = 'PickedUp'): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $crawler = $this->client->submitForm('Invia', [
            'backoffice_reservation[status]' => $pickedUpStatus,
        ]);

        self::assertResponseIsSuccessful();

        self::assertStringContainsString('La mail di ringraziamento è stata inviata', $this->client->getResponse()->getContent());

        self::assertGreaterThan(
            0,
            $crawler->filter("html div.card-status-prenotazione:contains('Ritirato')")->count()
        );
    }

    /** @test */
    public function shouldNotSendThanksMailWhenMovedToStatusPikedUp(string $pickedUpStatus = 'PickedUp'): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        /** @var ReservationRepository $reservationRepository */
        $reservationRepository = static::$kernel->getContainer()->get(ReservationRepository::class);

        $reservation->setEmail('');
        $reservationRepository->save($reservation);

        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $crawler = $this->client->submitForm('Invia', [
            'backoffice_reservation[status]' => $pickedUpStatus,
        ]);

        self::assertResponseIsSuccessful();

        self::assertStringNotContainsString('La mail di ringraziamento è stata inviata', $this->client->getResponse()->getContent());

        self::assertStringContainsString('Impossibile invio della mail di ringraziamento, questa prenotazione non ha un indirizzo email.', $this->client->getResponse()->getContent());

        self::assertGreaterThan(
            0,
            $crawler->filter("html div.card-status-prenotazione:contains('Ritirato')")->count()
        );
    }
}
