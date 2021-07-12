<?php declare(strict_types=1);

namespace Booking\Application\Domain\Model\ConfirmationStatus;


final class extensionTime
{
    private bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public static function true(): self
    {
        return new self(true);
    }

    public static function false(): self
    {
        return new self(false);
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function equals(?self $other): bool
    {
        return null !== $other && $this->value === $other->value;
    }
}
