<?php declare(strict_types=1);


namespace App\Tests\Functional\Backoffice\Reservation;

use App\Factory\ReservationFactory;
use App\Tests\Functional\SecurityWebtestCase;
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
}
