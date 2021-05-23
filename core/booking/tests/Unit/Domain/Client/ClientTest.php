<?php declare(strict_types=1);

namespace Booking\Tests\Unit\Domain\Client;

use Booking\Application\Domain\Model\Booking\Client\Classe;
use Booking\Application\Domain\Model\Booking\Client\Client;
use Booking\Application\Domain\Model\Booking\Client\ClientId;
use Booking\Application\Domain\Model\Booking\Client\Email;
use Booking\Application\Domain\Model\Booking\Client\FirstName;
use Booking\Application\Domain\Model\Booking\Client\LastName;
use Booking\Application\Domain\Model\Booking\Client\Phone;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 * @covers \Booking\Application\Domain\Model\Booking\Client\Client
 * @package Booking\Tests\Unit\Domain\Client
 */
class ClientTest extends TestCase
{
    private const FIRST_NAME = 'irrelevant';

    private const LAST_NAME = 'irrelevant';

    private const EMAIL = 'irrelevant@example.com';

    private const PHONE = '06 34252266';

    /** @test */
    public function canBeCreated(): void
    {
        $sut = new Client(
            ClientId::generate(),
            new FirstName(self::FIRST_NAME),
            new LastName(self::LAST_NAME),
            new Email(self::EMAIL),
            new Phone(self::PHONE),
            Classe::Prima(),
            'Roma'
        );

        self::assertEquals(self::FIRST_NAME, $sut->firstName()->value());
        self::assertEquals(self::LAST_NAME, $sut->lastName()->value());
        self::assertEquals(self::EMAIL, $sut->email()->value());
        self::assertEquals(self::PHONE, $sut->phone()->value());
        self::assertEquals('Roma', $sut->city());
    }

    /** @test */
    public function canBeCreated_withMinimumData(): void
    {
        $sut = new Client(
            ClientId::generate(),
            new FirstName(self::FIRST_NAME),
            new LastName(self::LAST_NAME),
            new Email(self::EMAIL),
            new Phone(self::PHONE),
            Classe::Prima(),
        );

        self::assertEquals(self::FIRST_NAME, $sut->firstName()->value());
        self::assertEquals(self::LAST_NAME, $sut->lastName()->value());
        self::assertEquals(self::EMAIL, $sut->email()->value());
        self::assertEquals(self::PHONE, $sut->phone()->value());
    }
}
