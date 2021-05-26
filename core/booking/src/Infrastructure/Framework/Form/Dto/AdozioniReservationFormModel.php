<?php declare(strict_types=1);


namespace Booking\Infrastructure\Framework\Form\Dto;

class AdozioniReservationFormModel
{
    public ClientDto $person;

    public string $notes;

    public bool $privacyConfirmed;
}
