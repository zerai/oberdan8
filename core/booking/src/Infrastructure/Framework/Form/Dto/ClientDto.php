<?php declare(strict_types=1);

namespace Booking\Infrastructure\Framework\Form\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @codeCoverageIgnore
 */
class ClientDto
{
    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getClasse(): string
    {
        return $this->classe;
    }

    /**
     * @param string $classe
     */
    public function setClasse(string $classe): void
    {
        $this->classe = $classe;
    }

    /**
     * @Assert\NotBlank(message="Inserisci il tuo nome")
     * @Assert\Length(min=3, minMessage="La lughezza minima per il nome è 3 caratteri!")
     */
    public string $firstName;

    /**
     * @Assert\NotBlank(message="Inserisci il tuo cognome")
     * @Assert\Length(min=3, minMessage="La lughezza minima per il cognome è 3 caratteri!!")
     */
    public string $lastName;

    /**
     * @Assert\NotBlank(message="Inserisci la tua email")
     * @Assert\Email()
     */
    public string $email;

    /**
     * @Assert\NotBlank(message="Inserisci il tuo recapito telefonico")
     */
    public string $phone;

    /**
     * @Assert\NotBlank(message="Inserisci una città")
     */
    public string $city;

    public string $classe = '';
}
