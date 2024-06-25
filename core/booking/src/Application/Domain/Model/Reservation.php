<?php declare(strict_types=1);

namespace Booking\Application\Domain\Model;

use Booking\Adapter\Persistance\ReservationRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 * @ORM\Table(name="bkg_reservation")
 */
class Reservation
{
    /**
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)*/
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $LastName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $classe;

    /**
     * @ORM\OneToMany(
     *     targetEntity=Book::class,
     *     mappedBy="reservation",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     */
    private $books;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $registrationDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $otherInformation = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $coupondCode = '';

    /**
     * @ORM\OneToOne(targetEntity=ReservationSaleDetail::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ReservationSaleDetail $saleDetail;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(string $LastName): self
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getClasse(): string
    {
        return $this->classe;
    }

    public function setClasse(string $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (! $this->books->contains($book)) {
            $this->books[] = $book;
            $book->setReservation($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getReservation() === $this) {
                $book->setReservation(null);
            }
        }

        return $this;
    }

    public function getRegistrationDate(): ?DateTimeImmutable
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(DateTimeImmutable $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getOtherInformation(): ?string
    {
        return $this->otherInformation;
    }

    public function setOtherInformation(string $otherInformation): self
    {
        $this->otherInformation = $otherInformation;

        return $this;
    }

    public function getSaleDetail(): ?ReservationSaleDetail
    {
        return $this->saleDetail;
    }

    public function setSaleDetail(ReservationSaleDetail $saleDetail): self
    {
        $this->saleDetail = $saleDetail;

        return $this;
    }

    /**
     * @return string
     */
    public function getCoupondCode(): string
    {
        return $this->coupondCode;
    }

    /**
     * @param string $coupondCode
     */
    public function setCoupondCode(string $coupondCode): void
    {
        $this->coupondCode = $coupondCode;
    }
}
