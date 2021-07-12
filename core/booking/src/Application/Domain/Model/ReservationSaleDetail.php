<?php declare(strict_types=1);


namespace Booking\Application\Domain\Model;

use Booking\Application\Domain\Model\ConfirmationStatus\ConfirmationStatus;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=ReservationSeleDetailRepository::class)
 */
class ReservationSaleDetail
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
    private ?string $ReservationPackageId = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $GeneralNotes = null;

    /**
     * @ORM\Column(type="reservation_status")
     */
    private ReservationStatus $status;

    private ?ConfirmationStatus $confirmationStatus = null;

    /**
     * @return ConfirmationStatus|null
     */
    public function getConfirmationStatus(): ?ConfirmationStatus
    {
        return $this->confirmationStatus;
    }

    /**
     * @param ConfirmationStatus $confirmationStatus
     */
    public function setConfirmationStatus(ConfirmationStatus $confirmationStatus): void
    {
        $this->confirmationStatus = $confirmationStatus;
    }

    private function resetConfirmationStatus(): void
    {
        $this->confirmationStatus = null;
    }

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
        if ($status->name() === 'Confirmed') {
            $newConfirmationStatus = ConfirmationStatus::create(
                new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome'))
            );

            $this->setConfirmationStatus($newConfirmationStatus);
        } else {
            $this->resetConfirmationStatus();
        }

        $this->status = $status;

        return $this;
    }
}
