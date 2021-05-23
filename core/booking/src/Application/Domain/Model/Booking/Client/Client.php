<?php

declare(strict_types=1);

namespace Booking\Application\Domain\Model\Booking\Client;

/**
 *
 * codeCoverageIgnore
 */
final class Client
{
    private ClientId $id;

    private FirstName $firstName;

    private LastName $lastName;

    private Email $email;

    private Phone $phone;

    private ?Classe $classe;

    private string $city;

    public function __construct(
        ClientId $id,
        FirstName $firstName,
        LastName $lastName,
        Email $email,
        Phone $phone,
        ?Classe $classe,
        string $city = ''
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        $this->classe = $classe;
        $this->city = $city;
    }

    public function id(): ClientId
    {
        return $this->id;
    }

    public function withId(ClientId $id): self
    {
        return new self(
            $id,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->phone,
            $this->classe,
            $this->city
        );
    }

    public function firstName(): FirstName
    {
        return $this->firstName;
    }

    public function withFirstName(FirstName $firstName): self
    {
        return new self(
            $this->id,
            $firstName,
            $this->lastName,
            $this->email,
            $this->phone,
            $this->classe,
            $this->city
        );
    }

    public function lastName(): LastName
    {
        return $this->lastName;
    }

    public function withLastName(LastName $lastName): self
    {
        return new self(
            $this->id,
            $this->firstName,
            $lastName,
            $this->email,
            $this->phone,
            $this->classe,
            $this->city
        );
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function withEmail(Email $email): self
    {
        return new self(
            $this->id,
            $this->firstName,
            $this->lastName,
            $email,
            $this->phone,
            $this->classe,
            $this->city
        );
    }

    public function phone(): Phone
    {
        return $this->phone;
    }

    public function withPhone(Phone $phone): self
    {
        return new self(
            $this->id,
            $this->firstName,
            $this->lastName,
            $this->email,
            $phone,
            $this->classe,
            $this->city
        );
    }

    public function classe(): ?Classe
    {
        return $this->classe;
    }

    public function withClasse(?Classe $classe): self
    {
        return new self(
            $this->id,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->phone,
            $classe,
            $this->city
        );
    }

    public function city(): string
    {
        return $this->city;
    }

    public function withCity(string $city): self
    {
        return new self(
            $this->id,
            $this->firstName,
            $this->lastName,
            $this->email,
            $this->phone,
            $this->classe,
            $city
        );
    }

    public static function fromArray(array $data): self
    {
        if (! isset($data['id']) || ! \is_string($data['id'])) {
            throw new \InvalidArgumentException('Error on "id", string expected');
        }

        if (! isset($data['firstName']) || ! \is_string($data['firstName'])) {
            throw new \InvalidArgumentException('Error on "firstName", string expected');
        }

        if (! isset($data['lastName']) || ! \is_string($data['lastName'])) {
            throw new \InvalidArgumentException('Error on "lastName", string expected');
        }

        if (! isset($data['email']) || ! \is_string($data['email'])) {
            throw new \InvalidArgumentException('Error on "email", string expected');
        }

        if (! isset($data['phone']) || ! \is_string($data['phone'])) {
            throw new \InvalidArgumentException('Error on "phone", string expected');
        }

        if (isset($data['classe']) && ! \is_string($data['classe'])) {
            throw new \InvalidArgumentException('Error on "classe", string expected');
        }

        if (! isset($data['city']) || ! \is_string($data['city'])) {
            throw new \InvalidArgumentException('Error on "city": string expected');
        }

        return new self(
            ClientId::fromString($data['id']),
            new FirstName($data['firstName']),
            new LastName($data['lastName']),
            new Email($data['email']),
            new Phone($data['phone']),
            isset($data['classe']) ? Classe::fromName($data['classe']) : null,
            $data['city'],
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toString(),
            'firstName' => $this->firstName->value(),
            'lastName' => $this->lastName->value(),
            'email' => $this->email->value(),
            'phone' => $this->phone->value(),
            'classe' => $this->classe !== null ? $this->classe->name() : null,
            'city' => $this->city,
        ];
    }

    public function equals(?Client $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        if (! $this->id->equals($other->id)) {
            return false;
        }

        if (! $this->firstName->equals($other->firstName)) {
            return false;
        }

        if (! $this->lastName->equals($other->lastName)) {
            return false;
        }

        if (! $this->email->equals($other->email)) {
            return false;
        }

        if (! $this->phone->equals($other->phone)) {
            return false;
        }

        if (null === $this->classe) {
            if (null !== $other->classe) {
                return false;
            }
        } elseif (! $this->classe->equals($other->classe)) {
            return false;
        }

        if ($this->city !== $other->city) {
            return false;
        }

        return true;
    }
}
