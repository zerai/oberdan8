<?php declare(strict_types=1);


namespace App\Tests\Functional\Backoffice;

use App\Tests\Functional\SecurityWebtestCase;

class InfoBoxCrudTest extends SecurityWebtestCase
{
    /** @test */
    public function userManagerPageShouldBeAccessible(): void
    {
        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/info-box/');

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /** @test */
    public function shouldBeAbleToCreateAnInfoBox(): void
    {
        self::markTestIncomplete();
        //$this->logInAsAdmin();
    }

    /** @test */
    public function shouldBeAbleToEditAnInfoBox(): void
    {
        self::markTestIncomplete();
        //$this->logInAsAdmin();
    }

    /** @test */
    public function shouldBeAbleToDeleteAnInfoBox(): void
    {
        self::markTestIncomplete();
        //$this->logInAsAdmin();
    }
}
