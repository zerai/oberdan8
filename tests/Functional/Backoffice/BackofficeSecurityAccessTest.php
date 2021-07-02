<?php declare(strict_types=1);


namespace App\Tests\Functional\Backoffice;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BackofficeSecurityAccessTest extends WebTestCase
{
    /**
     * @test
     * @dataProvider  securedAreasUrlprovider
     */
    public function accessToSecuredAreasShouldBeProtected(string $url): void
    {
        $client = static::createClient();

        $client->request('GET', $url);

        self::assertTrue(
            $client->getResponse()->isRedirect('/admin/login')
        );
    }

    public function securedAreasUrlprovider(): array
    {
        return [
            ['/admin'],
            ['/admin/dashboard'],
            ['/admin/info-box/'],
            ['/admin/info-box/manager'],
            ['/admin/info-box/new'],
            ['/admin/info-box/222/edit'],
            ['/admin/my-account'],
            ['/admin/my-account/change-password'],
            ['/admin/prenotazioni'],
            ['/admin/info-box'],
        ];
    }
}
