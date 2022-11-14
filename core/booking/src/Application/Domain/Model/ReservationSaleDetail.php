<?php declare(strict_types=1);


namespace Booking\Application\Domain\Model;

use Booking\Application\Domain\Model\ConfirmationStatus\ConfirmationStatus;
use Booking\Application\Domain\Model\ConfirmationStatus\ExtensionTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=ReservationSeleDetailRepository::class)
 */
class ReservationSaleDetail
{
    /**
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)*/
    private \Ramsey\Uuid\UuidInterface $id;

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

    //private ?ConfirmationStatus $confirmationStatus = null;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $pvtExtensionTime = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?DateTimeImmutable $pvtConfirmedAt = null;

    /**
     * @return ConfirmationStatus|null
     */
    public function getConfirmationStatus(): ?ConfirmationStatus
    {
        if ($this->getPvtConfirmedAt() === null || $this->getPvtExtensionTime() === null) {
            return null;
        }

        return new ConfirmationStatus(
            $this->getPvtConfirmedAt(),
            new ExtensionTime($this->getPvtExtensionTime())
        );
        //Todo remove use a runtime ConfirmationStatus
        //return $this->confirmationStatus;
    }

    /**
     * @param ConfirmationStatus $confirmationStatus
     */
    public function setConfirmationStatus(ConfirmationStatus $confirmationStatus): void
    {
        // TODO make private method

        $this->setPvtConfirmedAt($confirmationStatus->confirmedAt());
        $this->setPvtExtensionTime($confirmationStatus->extensionTime()->value());
        //$this->confirmationStatus = $confirmationStatus;
    }

    private function resetConfirmationStatus(): void
    {
        $this->pvtConfirmedAt = null;
        $this->pvtExtensionTime = null;
        //$this->confirmationStatus = null;
    }

    public function getId(): UuidInterface
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

    /**
     * @internal
     * @return bool|null
     */
    public function getPvtExtensionTime(): ?bool
    {
        return $this->pvtExtensionTime;
    }

    /**
     * @internal
     * @param bool|null $pvtExtensionTime
     * @return $this
     */
    public function setPvtExtensionTime(?bool $pvtExtensionTime): self
    {
        $this->pvtExtensionTime = $pvtExtensionTime;

        return $this;
    }

    /**
     * @internal
     * @return DateTimeImmutable|null
     */
    public function getPvtConfirmedAt(): ?DateTimeImmutable
    {
        return $this->pvtConfirmedAt;
    }

    /**
     * @internal
     * @param \DateTimeImmutable|null $pvtConfirmedAt
     * @return $this
     */
    public function setPvtConfirmedAt(?DateTimeImmutable $pvtConfirmedAt): self
    {
        $this->pvtConfirmedAt = $pvtConfirmedAt;

        return $this;
    }
}
