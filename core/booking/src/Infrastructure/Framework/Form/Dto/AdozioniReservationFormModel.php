<?php declare(strict_types=1);


namespace Booking\Infrastructure\Framework\Form\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class AdozioniReservationFormModel
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
     * @Assert\NotBlank (message="Seleziona un file da caricare.")
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Il file selezionato non è un in formato PDF."
     * )
     *
     */
    public string $adozioni;

    public string $notes;

    /**
     * @Assert\IsTrue(message="Acconsenti al trattamento dei tuoi dati personali se desideri continuare.")
     */
    public bool $privacyConfirmed;
}
