<?php declare(strict_types=1);

namespace Booking\Application\Mailer;

use Booking\Application\ApplicationPort;

interface ReservationConfirmationToClient extends ApplicationPort
{
    public function sendReservationConfirmationEmailToClient(string $recipient, array $personData, array $bookData);
}
