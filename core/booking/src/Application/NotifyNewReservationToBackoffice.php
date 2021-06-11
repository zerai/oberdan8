<?php declare(strict_types=1);

namespace Booking\Application;

interface NotifyNewReservationToBackoffice extends ApplicationPort
{
    public function notifyNewReservationToBackoffice(array $personData, array $bookData, array $systemData = []);
}
