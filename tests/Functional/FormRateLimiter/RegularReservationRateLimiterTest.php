<?php declare(strict_types=1);

/**
 * This file is part of the medicalmundi/marketplace-engine
 *
 * @copyright (c) 2024 MedicalMundi
 *
 * This software consists of voluntary contributions made by many individuals
 * {@link https://github.com/medicalmundi/marketplace-engine/graphs/contributors developer} and is licensed under the MIT license.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * @license https://github.com/MedicalMundi/marketplace-engine/blob/main/LICENSE MIT
 */

namespace App\Tests\Functional\FormRateLimiter;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class RegularReservationRateLimiterTest extends WebTestCase
{
    /**
     * @var KernelBrowser|null
     */
    protected $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function test_throttlingIsNotActiveOnRegularReservationFormOnGetRequest(): void
    {
        $this->client->request(Request::METHOD_GET, '/reservation');
        $this->client->request(Request::METHOD_GET, '/reservation');
        $this->client->request(Request::METHOD_GET, '/reservation');
        $this->client->request(Request::METHOD_GET, '/reservation');

        self::assertEquals(200, (int) $this->client->getResponse()->getStatusCode());
        self::assertStringNotContainsString(
            'Hai superato il numero massimo di invii consentiti. Riprova tra 60 minuti',
            (string) $this->client->getResponse()->getContent(),
            'test error message'
        );
    }

    public function test_throttlingIsActiveOnRegularReservationFormOnPostRequest(): void
    {
        $this->client->request(Request::METHOD_POST, '/reservation');
        $this->client->request(Request::METHOD_POST, '/reservation');
        $this->client->request(Request::METHOD_POST, '/reservation');
        $this->client->request(Request::METHOD_POST, '/reservation');

        self::assertEquals(200, (int) $this->client->getResponse()->getStatusCode());
        self::assertStringContainsString(
            'Hai superato il numero massimo di invii consentiti. Riprova tra 60 minuti',
            (string) $this->client->getResponse()->getContent(),
            'test error message'
        );
    }
}
