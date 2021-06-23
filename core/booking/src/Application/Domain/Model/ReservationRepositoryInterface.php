<?php declare(strict_types=1);

namespace Booking\Application\Domain\Model;

use Booking\Application\ApplicationPort;

/**
 * @codeCoverageIgnore
 */
interface ReservationRepositoryInterface extends ApplicationPort
{
    public function save(Reservation $reservation): void;

    public function delete(Reservation $reservation): void;
}
