<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Admin\Form\Model;

use Booking\Adapter\Web\Admin\Form\Dto\ClientDto;
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
     * Assert\Count(
     *      min = 1,
     *      minMessage = "Inserire almeno un libro.",
     * )
     */
    public $books;

    public string $generalNotes;

    public string $status;

    public ?string $packageId;
}
