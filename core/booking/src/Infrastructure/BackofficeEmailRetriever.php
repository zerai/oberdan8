<?php declare(strict_types=1);

namespace Booking\Infrastructure;

final class BackofficeEmailRetriever
{
    public function __construct(
        private string $address,
        private string $name
    ) {
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
