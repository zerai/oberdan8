<?php declare(strict_types=1);


namespace Booking\Application\Domain\Model;

use Booking\Infrastructure\Framework\Form\Dto\BookDto;
use Ramsey\Uuid\UuidInterface;

interface ReservationFactory
{
    public function createRegularReservation(
        UuidInterface $id,
        string $firstName,
        string $lastName,
        string $email,
        string $phone,
        string $city,
        string $classe,
        BookDto ...$books
    ): Reservation;
}
