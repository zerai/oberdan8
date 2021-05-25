<?php declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WalkingSkeletonTest extends WebTestCase
{
    /** @test */
    public function homepageShouldBeAccessibile(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        self::assertPageTitleSame('Oberdan - banco 8 - prenotazioni', $message = 'Unexpected title in homepage');
    }

    /** @test */
    public function regularReservationPageShouldBeAccessibile(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/reservation');

        $this->assertResponseIsSuccessful();
        self::assertPageTitleSame('Oberdan - banco 8 - prenotazioni', $message = 'Unexpected title in homepage');
    }

    /** @test */
    public function adozioniReservationPageShouldBeAccessibile(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/reservation/adozioni');

        $this->assertResponseIsSuccessful();
        self::assertPageTitleSame('Oberdan - banco 8 - prenotazioni', $message = 'Unexpected title in homepage');
    }
}
