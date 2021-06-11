<?php declare(strict_types=1);

namespace Booking\Application;

interface NotifyReservationConfirmationToClient extends ApplicationPort
{
    public function sendReservationConfirmationEmailToClient(string $recipient, array $personData, array $bookData);
}
