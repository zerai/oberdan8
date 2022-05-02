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
        $this->logInAsAdmin();

        $this->client->request('GET', '/admin/info-box/new');

        self::assertResponseIsSuccessful();

        $this->client->followRedirects(true);

        $csrfToken = $this->client->getContainer()->get('security.csrf.token_manager')->getToken('_info_box_token');

        $crawler = $this->client->submitForm('Salva', [
            'info_box[title]' => 'irrelevant title',
            'info_box[body]' => 'irrelevant',
            'info_box[active]' => 1,
            'info_box[boxType]' => 'info',
        ]);

        self::assertResponseIsSuccessful();

        self::assertStringContainsString('irrelevant title', $this->client->getResponse()->getContent());
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
