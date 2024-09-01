<?php declare(strict_types=1);


namespace App\Tests\Functional\Backoffice\Reservation;

use App\Factory\ReservationFactory;
use App\Factory\ReservationSaleDetailFactory;
use App\Tests\Functional\SecurityWebtestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ConfirmedReservationSearchTest extends SecurityWebtestCase
{
    use ResetDatabase;

    use Factories;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function reservationManagerPageShouldHaveASearchForm(): void
    {
        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/confermate/');

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertPageTitleSame('Prenotazioni Confermate Administration - Oberdan 8');

        self::assertSelectorTextContains('h1', 'Prenotazioni Confermate');
    }

    /** @test */
    public function shouldReturnNoResultFound(): void
    {
        ReservationFactory::new()->withConfirmedStatus()->create(
            [
                'SaleDetail' => ReservationSaleDetailFactory::new()->withConfirmed1DayAgo()->withPackageId('555'),
            ]
        );

        $this->logInAsAdmin();

        $this->client->followRedirects(true);

        $crawler = $this->client->request('GET', '/admin/prenotazioni/confermate');

        self::assertResponseIsSuccessful();

        self::assertPageTitleSame('Prenotazioni Confermate Administration - Oberdan 8');

        $this->client->submitForm(
            'backoffice_reservation_search_submit',
            [
                'q' => 'not-exist-package-id',
            ],
            'GET'
        );

        self::assertResponseIsSuccessful();

        self::assertStringContainsString("Nessun risultato trovato, non ci sono prenotazioni confermate.", $this->client->getResponse()->getContent(), "my message");
    }

    /** @test */
    public function searchByPackageIdShouldReturnOneRecord(): void
    {
        $packageId = '555';
        $lastname = 'target-result';

        ReservationFactory::new([
            'LastName' => $lastname,
        ])
            ->withConfirmedStatus()
            ->create(
                [
                    'SaleDetail' => ReservationSaleDetailFactory::new()->withConfirmed1DayAgo()->withPackageId($packageId),
                ]
            );

        $this->logInAsAdmin();

        $this->client->followRedirects(true);

        $crawler = $this->client->request('GET', '/admin/prenotazioni/confermate/');

        self::assertResponseIsSuccessful();

        self::assertPageTitleSame('Prenotazioni Confermate Administration - Oberdan 8');

        $this->client->submitForm(
            'backoffice_reservation_search_submit',
            [
                'q' => $packageId,
            ],
            'GET'
        );

        self::assertResponseIsSuccessful();
        self::assertStringContainsString($lastname, $this->client->getResponse()->getContent(), "Search by packageId expect one result");
    }

    /** @test */
    public function searchByPackageIdShouldReturnTwoRecord(): void
    {
        $firstPackageId = '501';
        $firstLastname = 'target-result';
        $secondPackageId = '502';
        $secondLastname = 'second-target-result';

        ReservationFactory::new([
            'LastName' => $firstLastname,
        ])
            ->withConfirmedStatus()
            ->create(
                [
                    'SaleDetail' => ReservationSaleDetailFactory::new()->withConfirmed1DayAgo()->withPackageId($firstPackageId),
                ]
            );

        ReservationFactory::new([
            'LastName' => $secondLastname,
        ])
            ->withConfirmedStatus()
            ->create(
                [
                    'SaleDetail' => ReservationSaleDetailFactory::new()->withConfirmed1DayAgo()->withPackageId($secondPackageId),
                ]
            );

        $this->logInAsAdmin();

        $this->client->followRedirects(true);

        $crawler = $this->client->request('GET', '/admin/prenotazioni/confermate/');

        self::assertResponseIsSuccessful();

        self::assertPageTitleSame('Prenotazioni Confermate Administration - Oberdan 8');

        $this->client->submitForm(
            'backoffice_reservation_search_submit',
            [
                'q' => '50',
            ],
            'GET'
        );

        self::assertResponseIsSuccessful();
        self::assertStringContainsString($firstLastname, $this->client->getResponse()->getContent(), "Search by packageId expect two result");
        self::assertStringContainsString($secondLastname, $this->client->getResponse()->getContent(), "Search by packageId expect two result");
    }
}
