<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Admin\Form\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @codeCoverageIgnore
 */
class ClientDto
{
    /**
     * @Assert\Length(min=3, minMessage="La lughezza minima per il nome è 3 caratteri!")
     */
    public string $firstName;

    /**
     *
     * @Assert\Length(min=3, minMessage="La lughezza minima per il cognome è 3 caratteri!!")
     */
    public string $lastName;

    /**
     * @Assert\Email()
     */
    public string $email;

    public string $phone;

    public string $city;

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
}
