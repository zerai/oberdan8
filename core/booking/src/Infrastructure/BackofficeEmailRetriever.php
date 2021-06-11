<?php declare(strict_types=1);

namespace Booking\Infrastructure;

final class BackofficeEmailRetriever
{
    private string $address;

    private string $name;

    public function __construct(string $address, string $name)
    {
        $this->address = $address;
        $this->name = $name;
    }

    public static function fromData(string $address, string $name): self
    {
        // TODO ADD mail validation
        return new self($address, $name);
    }

    public function address(): string
    {
        return $this->address;
    }

    public function name(): string
    {
        return $this->name;
    }
}
