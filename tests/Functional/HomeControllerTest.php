<?php declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    /** @test */
    public function homepageShouldBeAccessibile(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        self::assertResponseIsSuccessful();
        self::assertPageTitleSame('Oberdan - banco 8 - prenotazioni', $message = 'Unexpected title in homepage');

        // check call to action text
        self::assertSelectorTextContains('h1', 'Prenota ora!');
    }

    /** @test */
    public function shouldContainALinkToReservationForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        self::assertResponseIsSuccessful();

        $link = $crawler->selectLink('Compila il modulo')->link();
        $client->click($link);

        self::assertResponseIsSuccessful();
    }

    /** @test */
    public function shouldContainALinkToAdozioniReservationForm(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        self::assertResponseIsSuccessful();

        $link = $crawler->selectLink('Carica il file delle adozioni')->link();
        $client->click($link);

        self::assertResponseIsSuccessful();
    }
}
