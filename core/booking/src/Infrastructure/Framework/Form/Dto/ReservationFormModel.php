<?php declare(strict_types=1);


namespace Booking\Infrastructure\Framework\Form\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ReservationFormModel
{
    /**
     * @Assert\Valid
     */
    public ClientDto $person;

    /**
     * @Assert\NotNull(message="Seleziona un' opzione.")
     */
    public string $classe;

    public $books;

    public string $notes;

    /**
     * @Assert\IsTrue(message="Acconsenti al trattamento dei tuoi dati personali se desideri continuare.")
     */
    public bool $privacyConfirmed;
}
