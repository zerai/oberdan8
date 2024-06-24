<?php declare(strict_types=1);


namespace App\Tests\Functional\Backoffice;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class BackofficeSecurityAccessTest extends WebTestCase
{
    /**
     * @test
     * @testWith ["/admin"]
     *          ["/admin/dashboard"]
     *          ["/admin/info-box/"]
     *          ["/admin/info-box/manager"]
     *          ["/admin/info-box/new"]
     *          ["/admin/info-box/222/edit"]
     *          ["/admin/my-account"]
     *          ["/admin/my-account/change-password"]
     *          ["/admin/prenotazioni"]
     *          ["/admin/info-box"]
     */
    public function accessToSecuredAreasShouldBeProtected(string $url): void
    {
        $client = static::createClient();

        $client->request(Request::METHOD_GET, $url);

        self::assertTrue(
            $client->getResponse()->isRedirect('/admin/login')
        );
    }
}
