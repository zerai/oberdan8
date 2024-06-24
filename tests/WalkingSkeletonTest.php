<?php declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class WalkingSkeletonTest extends WebTestCase
{
    public function regularReservationPageShouldBeAccessibile(): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/reservation');

        $this->assertResponseIsSuccessful();
        self::assertPageTitleSame('Oberdan - banco 8 - prenotazioni', $message = 'Unexpected title in homepage');
    }

    /** @test */
    public function adozioniReservationPageShouldBeAccessibile(): void
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/reservation/adozioni');

        $this->assertResponseIsSuccessful();
        self::assertPageTitleSame('Oberdan - banco 8 - prenotazioni', $message = 'Unexpected title in homepage');
    }
}
