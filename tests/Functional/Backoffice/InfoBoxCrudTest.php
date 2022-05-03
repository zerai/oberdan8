<?php declare(strict_types=1);


namespace App\Tests\Functional\Backoffice;

use App\Entity\InfoBox;
use App\Factory\InfoBoxFactory;
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
        self::markTestIncomplete('info box risulta modificato ma la pagina non ritorna i nuovi dati.');

        /** @var InfoBox $infoBox */
        $infoBox = InfoBoxFactory::createOne()->object();

        $this->logInAsAdmin();
        $this->client->followRedirects(true);

        $this->client->request('GET', '/admin/info-box/' . $infoBox->getId()->toString() . '/edit');

        self::assertResponseIsSuccessful();

        self::assertStringContainsString('test infobox', $this->client->getResponse()->getContent());

        $crawler = $this->client->submitForm('Aggiorna', [
            'info_box[title]' => 'new title',
            'info_box[body]' => 'new body',
            'info_box[active]' => 1,
            'info_box[boxType]' => 'info',
        ]);

        self::assertResponseIsSuccessful();

        self::assertStringContainsString('new title', $this->client->getResponse()->getContent());
        self::assertStringContainsString('new body', $this->client->getResponse()->getContent());
    }

    /** @test */
    public function shouldBeAbleToDeleteAnInfoBox(): void
    {
        self::markTestIncomplete();
        //$this->logInAsAdmin();
    }
}
