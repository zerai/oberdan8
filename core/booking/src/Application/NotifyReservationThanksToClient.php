<?php declare(strict_types=1);

namespace Booking\Application;

interface NotifyReservationThanksToClient extends ApplicationPort
{
    public function notifyReservationThanksEmailToClient(string $recipient, string $reservationId);
}
