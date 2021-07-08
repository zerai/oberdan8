<?php declare(strict_types=1);


namespace Booking\Tests\Unit\Domain\ConfirmationStatus;

use Booking\Application\Domain\Model\ConfirmationStatus\ConfirmationStatus;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Booking\Application\Domain\Model\ConfirmationStatus\ConfirmationStatus
 */
class ConfirmationStatusTest extends TestCase
{
    /** @test */
    public function canBeCreated(): void
    {
        $date = new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome'));

        //dd($date->format('Y-m-d\TH:i:s.uP'));

        $sut = ConfirmationStatus::fromArray(
            [
                'confirmedAt' => '2021-07-08T09:35:11.004408+02:00',
                'extensionTime' => false,
                'expired' => false,
            ]
        );

        self::assertFalse($sut->extensionTime()->value());
        self::assertFalse($sut->expired()->value());
    }

    /** @test */
    public function canBeCreatedWithCreateFactoryMethod(): void
    {
        $date = new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome'));

        //dd($date->format('Y-m-d\TH:i:s.uP'));

        $sut = ConfirmationStatus::create(
            $date
        );

        self::assertFalse($sut->extensionTime()->value());
        self::assertFalse($sut->expired()->value());
    }
}
