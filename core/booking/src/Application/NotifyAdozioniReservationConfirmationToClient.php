<?php declare(strict_types=1);

namespace Booking\Application;

interface NotifyAdozioniReservationConfirmationToClient extends ApplicationPort
{
    public function notifyAdozioniReservationConfirmationEmailToClient(string $recipient, array $personData, array $filesData = [], string $otherInfo = '');
}
