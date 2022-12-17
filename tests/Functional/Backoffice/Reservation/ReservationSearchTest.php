<?php declare(strict_types=1);


namespace App\Tests\Functional\Backoffice\Reservation;

use App\Factory\ReservationFactory;
use App\Factory\ReservationSaleDetailFactory;
use App\Tests\Functional\SecurityWebtestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ReservationSearchTest extends SecurityWebtestCase
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

        $crawler = $this->client->request('GET', '/admin/prenotazioni/');

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());

        self::assertPageTitleSame('Prenotazioni Administration - Oberdan 8');

        self::assertSelectorTextContains('h1', 'Prenotazioni');

        //self::assertSelectorTextContains('form', 'Cerca');
    }

    /** @test */
    public function shouldReturnNoResultFound(): void
    {
        $reservation = ReservationFactory::new()->withConfirmedStatus()->create(
            [
                'SaleDetail' => ReservationSaleDetailFactory::new()->withConfirmed1DayAgo()->withPackageId('555'),
            ]
        );

        $this->logInAsAdmin();

        $this->client->followRedirects(true);

        $crawler = $this->client->request('GET', '/admin/prenotazioni/');

        self::assertResponseIsSuccessful();

        self::assertPageTitleSame('Prenotazioni Administration - Oberdan 8');

        $this->client->submitForm(
            'backoffice_reservation_search_submit',
            [
                'q' => 'not-exist-package-id',
            ],
            'GET'
        );

        self::assertResponseIsSuccessful();

        self::assertStringContainsString("Nessun risultato trovato.", $this->client->getResponse()->getContent(), "my message");
    }

    /** @test */
    public function searchByPackageIdShouldReturnOneRecord(): void
    {
        //TODO remove tempName (mettere colonna packageId in table)
        $packageId = '555';
        $tempName = 'target-result';

        ReservationFactory::new([
            'LastName' => $tempName,
        ])
            ->withConfirmedStatus()
            ->create(
                [
                    'SaleDetail' => ReservationSaleDetailFactory::new()->withConfirmed1DayAgo()->withPackageId($packageId),
                ]
            );

        $this->logInAsAdmin();

        $this->client->followRedirects(true);

        $crawler = $this->client->request('GET', '/admin/prenotazioni/');

        self::assertResponseIsSuccessful();

        self::assertPageTitleSame('Prenotazioni Administration - Oberdan 8');

        $this->client->submitForm(
            'backoffice_reservation_search_submit',
            [
                'q' => $packageId,
            ],
            'GET'
        );

        self::assertResponseIsSuccessful();
        self::assertStringContainsString($tempName, $this->client->getResponse()->getContent(), "Search by packageId expect one result");
    }

    /** @test */
    public function searchByPackageIdShouldReturnTwoRecord(): void
    {
        //TODO remove tempName (mettere colonna packageId in table)
        $packageId = '501';
        $tempName = 'target-result';
        $secondPackageId = '502';
        $secondTempName = 'second-target-result';

        ReservationFactory::new([
            'LastName' => $tempName,
        ])
            ->withConfirmedStatus()
            ->create(
                [
                    'SaleDetail' => ReservationSaleDetailFactory::new()->withConfirmed1DayAgo()->withPackageId($packageId),
                ]
            );

        ReservationFactory::new([
            'LastName' => $secondTempName,
        ])
            ->withConfirmedStatus()
            ->create(
                [
                    'SaleDetail' => ReservationSaleDetailFactory::new()->withConfirmed1DayAgo()->withPackageId($secondPackageId),
                ]
            );

        $this->logInAsAdmin();

        $this->client->followRedirects(true);

        $crawler = $this->client->request('GET', '/admin/prenotazioni/');

        self::assertResponseIsSuccessful();

        self::assertPageTitleSame('Prenotazioni Administration - Oberdan 8');

        $this->client->submitForm(
            'backoffice_reservation_search_submit',
            [
                'q' => '50',
            ],
            'GET'
        );

        self::assertResponseIsSuccessful();
        self::assertStringContainsString($tempName, $this->client->getResponse()->getContent(), "Search by packageId expect two result");
        self::assertStringContainsString($secondTempName, $this->client->getResponse()->getContent(), "Search by packageId expect two result");
    }

    /** @test */
    public function shouldNotFindRecordsWhenSearchByReservationStatus(): void
    {
        //TODO remove tempName (mettere colonna packageId in table)
        $this->logInAsAdmin();

        $this->client->followRedirects(true);

        $crawler = $this->client->request('GET', '/admin/prenotazioni/');

        self::assertResponseIsSuccessful();

        self::assertPageTitleSame('Prenotazioni Administration - Oberdan 8');

        $this->client->submitForm(
            'backoffice_reservation_search_submit',
            [
                'q' => '',
                'status' => '',
            ],
            'GET'
        );

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('Nessun risultato trovato.', $this->client->getResponse()->getContent());
    }

    /** @test */
    public function shouldSearchByReservationStatus(): void
    {
        //        //TODO remove tempName (mettere colonna packageId in table)

        ReservationFactory::createMany(
            5,
            [
                'saleDetail' => ReservationSaleDetailFactory::new()->withRejectedStatus(),
            ]
        );

        $this->logInAsAdmin();

        $this->client->followRedirects(true);

        $crawler = $this->client->request('GET', '/admin/prenotazioni/');

        self::assertResponseIsSuccessful();

        self::assertPageTitleSame('Prenotazioni Administration - Oberdan 8');

        $this->client->submitForm(
            'backoffice_reservation_search_submit',
            [
                'q' => '',
                'status' => 'Rejected',
            ],
            'GET'
        );

        self::assertResponseIsSuccessful();
        self::assertEquals(
            5,
            $crawler->filterXPath('//td//a/i[@class="fa fa-eye"]')->count()
        );
    }
}
