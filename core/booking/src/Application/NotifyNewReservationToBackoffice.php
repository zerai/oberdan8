<?php declare(strict_types=1);

namespace Booking\Application;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

interface NotifyNewReservationToBackoffice extends ApplicationPort
{
    public function notifyNewReservationToBackoffice(array $personData, array $bookData, array $systemData = [], string $otherInfo = '', string $coupondCode = ''): TemplatedEmail;
}
