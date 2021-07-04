<?php declare(strict_types=1);


namespace Booking\Application\Domain\Model;

use Booking\Adapter\Persistance\ReservationSeleDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=ReservationSeleDetailRepository::class)
 */
class ReservationSeleDetail
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $ReservationPackageId;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $GeneralNotes;

    /**
     * @ORM\Column(type="reservation_status")
     */
    private ReservationStatus $status;

    public function getId()
    {
        return $this->id;
    }

    public function getReservationPackageId(): ?string
    {
        return $this->ReservationPackageId;
    }

    public function setReservationPackageId(string $ReservationPackageId): self
    {
        $this->ReservationPackageId = $ReservationPackageId;

        return $this;
    }

    public function getGeneralNotes(): ?string
    {
        return $this->GeneralNotes;
    }

    public function setGeneralNotes(string $GeneralNotes): self
    {
        $this->GeneralNotes = $GeneralNotes;

        return $this;
    }

    public function getStatus(): ReservationStatus
    {
        return $this->status;
    }

    public function setStatus(ReservationStatus $status): self
    {
        $this->status = $status;

        return $this;
    }
}
