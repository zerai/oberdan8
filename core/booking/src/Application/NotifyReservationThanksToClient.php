<?php declare(strict_types=1);

namespace Booking\Application;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

interface NotifyReservationThanksToClient extends ApplicationPort
{
    public function notifyReservationThanksEmailToClient(string $recipient, string $reservationId): TemplatedEmail;
}
