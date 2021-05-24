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
    }

    /** @test */
    public function regularReservationPageShouldBeAccessibile(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/reservation');

        $this->assertResponseIsSuccessful();
    }
}
