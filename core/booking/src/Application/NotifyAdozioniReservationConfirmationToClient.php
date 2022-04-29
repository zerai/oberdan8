<?php declare(strict_types=1);

namespace Booking\Application;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

interface NotifyAdozioniReservationConfirmationToClient extends ApplicationPort
{
    public function notifyAdozioniReservationConfirmationEmailToClient(string $recipient, array $personData, array $filesData = [], string $otherInfo = ''): TemplatedEmail;
}
