<?php declare(strict_types=1);


namespace Booking\Tests\Unit\Domain;

use Booking\Application\Domain\Model\ReservationSaleDetail;
use Booking\Application\Domain\Model\ReservationStatus;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Booking\Application\Domain\Model\ReservationSaleDetail
 */
class ReservationSaleDetailTest extends TestCase
{
    /** @test */
    public function confirmationStatusIsNullAsDefault(): void
    {
        $sut = new ReservationSaleDetail();

        self::assertNull($sut->getConfirmationStatus());
    }

    /** @test */
    public function shouldAssignANewConfirmationStatusWhenReservationStatusIsConfirmed(): void
    {
        $sut = new ReservationSaleDetail();

        $sut->setStatus(ReservationStatus::Confirmed());

        self::assertNotNull($sut->getConfirmationStatus());
    }

    /** @test */
    public function shouldNotHaveExtensionTimeWhenCreated(): void
    {
        $sut = new ReservationSaleDetail();

        $sut->setStatus(ReservationStatus::Confirmed());

        self::assertFalse($sut->getConfirmationStatus()->extensionTime()->value());
    }

    /**
     * @test
     * @dataProvider  reservationStatusDifferentFromConfirmedProvider
     */
    public function shouldResetTheConfirmationStatusWhenReservationStatusIsDifferentFromConfirmed(string $newReservationStatus): void
    {
        $sut = new ReservationSaleDetail();

        $sut->setStatus(ReservationStatus::Confirmed());

        //assign new reservation status

        $sut->setStatus(ReservationStatus::fromName($newReservationStatus));

        self::assertNull($sut->getConfirmationStatus());
    }

    public function reservationStatusDifferentFromConfirmedProvider(): Generator
    {
        return [
            yield 'new arrival' => ['NewArrival'],
            yield 'InProgress' => ['InProgress'],
            yield 'Pending' => ['Pending'],
            yield 'Rejected' => ['Rejected'],
            yield 'Sale' => ['Sale'],
            yield 'PickedUp' => ['PickedUp'],
            yield 'Blacklist' => ['Blacklist'],
        ];
    }
}
