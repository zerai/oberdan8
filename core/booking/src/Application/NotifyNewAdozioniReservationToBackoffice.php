<?php declare(strict_types=1);

namespace Booking\Application;

interface NotifyNewAdozioniReservationToBackoffice extends ApplicationPort
{
    public function notifyNewAdozioniReservationToBackoffice(array $personData, array $filesData, array $systemData = [], string $otherInfo = '');
}
