<?php declare(strict_types=1);


namespace App\Tests\Functional\Backoffice;

use App\Tests\Functional\SecurityWebtestCase;

class ReservationCrudTest extends SecurityWebtestCase
{
    /** @test */
    public function reservationManagerPageShouldBeAccessible(): void
    {
        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/prenotazioni/');

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
