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
        //self::markTestSkipped('Fail on CI, not in local. investigate');
        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/user/new');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $crawler = $this->client->submitForm('Salva', [
            'backoffice_user[email]' => 'anewuser@example.com',
            'backoffice_user[plainPassword]' => 'demodemo',
            'backoffice_user[active]' => 1,
        ]);

        self::assertResponseIsSuccessful();

        self::assertStringContainsString('anewuser@example.com', $this->client->getResponse()->getContent());
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
