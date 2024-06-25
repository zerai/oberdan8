<?php declare(strict_types=1);

namespace Booking\Application;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

interface NotifyReservationConfirmationToClient extends ApplicationPort
{
    public function notifyReservationConfirmationEmailToClient(string $recipient, array $personData, array $bookData, string $otherInfo = '', string $coupondCode = ''): TemplatedEmail;
}
