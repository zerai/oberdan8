<?php declare(strict_types=1);


namespace Booking\Adapter\Web\Free\Form\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-suppress MissingConstructor
 */
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

    public string $coupondCode;

    /**
     * @Assert\NotBlank (message="Seleziona un file da caricare.")
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"application/pdf", "application/x-pdf", "image/jpeg"},
     *     mimeTypesMessage = "Il file selezionato non è un PDF o un JPEG."
     * )
     *
     */
    public string $adozioni;

    /**
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"application/pdf", "application/x-pdf", "image/jpeg"},
     *     mimeTypesMessage = "Il file selezionato non è un PDF o un JPEG."
     * )
     *
     */
    public string $adozioni2;

    /**
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"application/pdf", "application/x-pdf", "image/jpeg"},
     *     mimeTypesMessage = "Il file selezionato non è un PDF o un JPEG."
     * )
     *
     */
    public string $adozioni3;

    public string $otherInfo;

    /**
     * @Assert\IsTrue(message="Acconsenti al trattamento dei tuoi dati personali se desideri continuare.")
     */
    public bool $privacyConfirmed;
}
