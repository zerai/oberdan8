<?php

declare(strict_types=1);

namespace Booking\Application\Domain\Model\Booking\Client;

/**
 * null
 * @codeCoverageIgnore
 */
final class FirstName
{
    public function __construct(
        private string $value
    ) {
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
