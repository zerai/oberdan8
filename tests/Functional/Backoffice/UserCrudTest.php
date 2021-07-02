<?php declare(strict_types=1);


namespace App\Tests\Functional\Backoffice;

use App\Tests\Functional\SecurityWebtestCase;

class UserCrudTest extends SecurityWebtestCase
{
    /** @test */
    public function userManagerPageShouldBeAccessible(): void
    {
        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/user/');

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    /** @test */
    public function shouldBeAbleToCreateAUser(): void
    {
        self::markTestIncomplete();
        //$this->logInAsAdmin();
    }

    /** @test */
    public function shouldBeAbleToEditAUser(): void
    {
        self::markTestIncomplete();
        //$this->logInAsAdmin();
    }

    /** @test */
    public function shouldBeAbleToDeleteAUser(): void
    {
        self::markTestIncomplete();
        //$this->logInAsAdmin();
    }
}
