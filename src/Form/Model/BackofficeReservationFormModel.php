<?php declare(strict_types=1);

namespace App\Form\Model;

use App\Form\Dto\ClientDto;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @psalm-suppress MissingConstructor
 */
class BackofficeReservationFormModel
{
    /**
     * @Assert\Valid
     */
    public ClientDto $person;

    public string $classe = '';

    /**
     * @Assert\Valid
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "Inserire almeno un libro.",
     * )
     */
    public $books;

    //public string $otherInfo;

//    /**
//     * @Assert\IsTrue(message="Acconsenti al trattamento dei tuoi dati personali se desideri continuare.")
//     */
//    public bool $privacyConfirmed;
}
