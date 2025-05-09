<?php

declare(strict_types=1);

namespace Booking\Application\Domain\Model\Booking\Client;

/**
 * null
 * @codeCoverageIgnore
 */
final class Email
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(?self $other): bool
    {
        return null !== $other && $this->value === $other->value;
    }
}
