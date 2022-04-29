<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\InfoBoxRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=InfoBoxRepository::class)
 */
class InfoBox
{
    public const INFO_BOX_TYPES = [
        'none',
        'info',
        'warning',
        'danger',
    ];

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
    private string $title = '';

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $body = '';

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $active = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $boxType = 'none';

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $defaultBox = false;

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getBoxType(): ?string
    {
        return $this->boxType;
    }

    public function setBoxType(string $boxType): self
    {
        if (! \in_array($boxType, self::INFO_BOX_TYPES)) {
            throw new \InvalidArgumentException('Invalid Message box type');
        }

        $this->boxType = $boxType;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getDefaultBox(): bool
    {
        return $this->defaultBox;
    }

    public function setDefaultBox(bool $defaultBox): self
    {
        $this->defaultBox = $defaultBox;

        return $this;
    }
}
