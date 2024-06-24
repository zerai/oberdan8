<?php declare(strict_types=1);


namespace Booking\Tests\Unit\Domain\ConfirmationStatus;

use Booking\Application\Domain\Model\ConfirmationStatus\ConfirmationStatus;
use Booking\Application\Domain\Model\ConfirmationStatus\ExtensionTime;
use DateTimeImmutable;
use DateTimeZone;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Booking\Application\Domain\Model\ConfirmationStatus\ConfirmationStatus
 */
class ConfirmationStatusTest extends TestCase
{
    /** @test */
    public function canBeCreatedFromArray(): void
    {
        $sut = ConfirmationStatus::fromArray(
            [
                'confirmedAt' => '2021-07-08T09:35:11.004408+02:00',
                'extensionTime' => false,
            ]
        );

        self::assertFalse($sut->extensionTime()->value());
    }

    /** @test */
    public function canBeCreatedWithCreateFactoryMethod(): void
    {
        $aDate = new DateTimeImmutable("now", new DateTimeZone('Europe/Rome'));

        //dd($date->format('Y-m-d\TH:i:s.uP'));

        $sut = ConfirmationStatus::create(
            $aDate
        );

        self::assertFalse($sut->extensionTime()->value());
    }

    /**
     * @test
     * @dataProvider  todayMinus7DaysDataProvider
     */
    public function shouldNotBeExpired_whenUnder7Days(DateTimeImmutable $aDate): void
    {
        $sut = ConfirmationStatus::create(
            $aDate
        );

        $sut->isExpired();

        self::assertFalse($sut->isExpired());
    }

    public function todayMinus7DaysDataProvider(): Generator
    {
        yield ' - 1 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 1 days")];
        yield ' - 2 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 2 days")];
        yield ' - 3 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 3 days")];
        yield ' - 4 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 4 days")];
        yield ' - 5 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 5 days")];
        yield ' - 6 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 6 days")];
        yield ' - 7 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 7 days")];
    }

    /** @test */
    public function shouldNotBeExpired_whenEqual7Days(): void
    {
        $date = new DateTimeImmutable("now", new DateTimeZone('Europe/Rome'));
        $aDate = $date->modify("- 7 days");

        $sut = ConfirmationStatus::create(
            $aDate
        );

        $sut->isExpired();

        self::assertFalse($sut->isExpired());
    }

    /** @test */
    public function shouldBeExpired_after7days(): void
    {
        $date = new DateTimeImmutable("now", new DateTimeZone('Europe/Rome'));
        $aDate = $date->modify("- 8 days");

        $sut = ConfirmationStatus::create(
            $aDate
        );

        $sut->isExpired();

        self::assertTrue($sut->isExpired());
    }

    /**
     * @test
     * @dataProvider todayMinus14DaysDataProvider
     */
    public function shouldNotBeExpired_whenUnder14Days_andExtensionTimeIsActive(DateTimeImmutable $aDate): void
    {
        //$date = new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome'));
        //$aDate = $date->modify("- 8 days");

        $sut = ConfirmationStatus::create(
            $aDate
        );

        $sut = $sut->withExtensionTime(new ExtensionTime(true));

        $sut->isExpired();

        self::assertFalse($sut->isExpired());
    }

    public function todayMinus14DaysDataProvider(): Generator
    {
        yield ' - 1 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 1 days")];
        yield ' - 2 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 2 days")];
        yield ' - 3 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 3 days")];
        yield ' - 4 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 4 days")];
        yield ' - 5 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 5 days")];
        yield ' - 6 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 6 days")];
        yield ' - 7 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 7 days")];
        yield ' - 8 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 8 days")];
        yield ' - 9 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 9 days")];
        yield ' - 10 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 10 days")];
        yield ' - 11 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 11 days")];
        yield ' - 12 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 12 days")];
        yield ' - 13 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 13 days")];
        yield ' - 14 day' => [(new DateTimeImmutable("now", new DateTimeZone('Europe/Rome')))->modify("- 14 days")];
    }

    /** @test */
    public function shouldNotBeExpired_whenEqual14Days_andExtensionTimeIsActive(): void
    {
        $date = new DateTimeImmutable("now", new DateTimeZone('Europe/Rome'));
        $aDate = $date->modify("- 14 days");

        $sut = ConfirmationStatus::create(
            $aDate
        );

        $sut = $sut->withExtensionTime(new ExtensionTime(true));

        $sut->isExpired();

        self::assertFalse($sut->isExpired());
    }

    /** @test */
    public function shouldBeExpired_after14days(): void
    {
        $date = new DateTimeImmutable("now", new DateTimeZone('Europe/Rome'));
        $aDate = $date->modify("- 15 days");

        $sut = ConfirmationStatus::create(
            $aDate
        );

        $sut = $sut->withExtensionTime(new ExtensionTime(true));

        $sut->isExpired();

        self::assertTrue($sut->isExpired());
    }

    /** @test */
    public function shouldShowExpirationDate(): void
    {
        $date = new DateTimeImmutable("now", new DateTimeZone('Europe/Rome'));

        $expectedExpirationDate = $date->modify("+ 7 days");

        $sut = ConfirmationStatus::create(
            $date
        );

        self::assertEquals($expectedExpirationDate, $sut->expireAt());
    }

    /** @test */
    public function shouldShowExpirationDateWhenExtensionTimeIsActive(): void
    {
        $date = new DateTimeImmutable("now", new DateTimeZone('Europe/Rome'));

        $expectedExpirationDate = $date->modify("+ 14 days");

        $sut = ConfirmationStatus::create(
            $date
        );

        $sut = $sut->withExtensionTime(ExtensionTime::true());

        self::assertTrue($sut->extensionTime()->value());
        self::assertEquals($expectedExpirationDate, $sut->expireAt());
    }
}
