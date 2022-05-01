<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Free\Form\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-suppress MissingConstructor
 */
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

    /**
     * @Assert\Valid
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "Inserire almeno un libro.",
     * )
     */
    public array $books;

    public string $otherInfo;

    /**
     * @Assert\IsTrue(message="Acconsenti al trattamento dei tuoi dati personali se desideri continuare.")
     */
    public bool $privacyConfirmed;
}
