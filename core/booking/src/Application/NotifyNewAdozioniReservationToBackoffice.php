<?php declare(strict_types=1);

namespace Booking\Application;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

interface NotifyNewAdozioniReservationToBackoffice extends ApplicationPort
{
    public function notifyNewAdozioniReservationToBackoffice(array $personData, array $filesData, array $systemData = [], string $otherInfo = ''): TemplatedEmail;
}
