<?php declare(strict_types=1);


namespace Booking\Tests\Unit\Domain\ConfirmationStatus;

use Booking\Application\Domain\Model\ConfirmationStatus\ConfirmationStatus;
use Booking\Application\Domain\Model\ConfirmationStatus\ExtensionTime;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Booking\Application\Domain\Model\ConfirmationStatus\ConfirmationStatus
 */
class ConfirmationStatusTest extends TestCase
{
    /** @test */
    public function canBeCreated(): void
    {
        $sut = ConfirmationStatus::fromArray(
            [
                'confirmedAt' => '2021-07-08T09:35:11.004408+02:00',
                'extensionTime' => false,
                //'expired' => false,
            ]
        );

        self::assertFalse($sut->extensionTime()->value());
        //self::assertFalse($sut->expired()->value());
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
        //self::assertFalse($sut->expired()->value());
    }

    /** @test */
    public function canCalculateNegativeExpiration(): void
    {
        $date = new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome'));
        $date = $date->modify("- 5 days"); //->modify('- 2 days');

        $sut = ConfirmationStatus::create(
            $date
        );

        $sut->isExpired();

        self::assertFalse($sut->isExpired());
    }

    /** @test */
    public function canCalculatePositiveExpiration(): void
    {
        $date = new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome'));
        $date = $date->modify("- 8 days"); //->modify('- 2 days');

        $sut = ConfirmationStatus::create(
            $date
        );

        $sut->isExpired();

        self::assertTrue($sut->isExpired());
    }

    /** @test */
    public function canCalculateNegativeExpirationWithExtensionTime(): void
    {
        $date = new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome'));
        $date = $date->modify("- 8 days"); //->modify('- 2 days');

        $sut = ConfirmationStatus::create(
            $date
        );

        $sut = $sut->withExtensionTime(new ExtensionTime(true));

        $sut->isExpired();

        self::assertFalse($sut->isExpired());
    }

    /** @test */
    public function canCalculatePositiveExpirationWithExtensionTime(): void
    {
        $date = new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome'));
        $date = $date->modify("- 14 days"); //->modify('- 2 days');

        $sut = ConfirmationStatus::create(
            $date
        );

        $sut = $sut->withExtensionTime(new ExtensionTime(true));

        $sut->isExpired();

        self::assertTrue($sut->isExpired());
    }

    /** @test */
    public function shouldShowExpirationDate(): void
    {
        $date = new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome'));

        $expectedExpirationDate = $date->modify("+ 7 days");

        $sut = ConfirmationStatus::create(
            $date
        );

        self::assertEquals($expectedExpirationDate, $sut->expireAt());
    }

    /** @test */
    public function shouldShowExpirationDateWhenExtensionTimeIsActive(): void
    {
        $date = new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome'));

        $expectedExpirationDate = $date->modify("+ 14 days");

        $sut = ConfirmationStatus::create(
            $date
        );

        $sut = $sut->withExtensionTime(ExtensionTime::true());

        self::assertTrue($sut->extensionTime()->value());
        self::assertEquals($expectedExpirationDate, $sut->expireAt());
    }
}
