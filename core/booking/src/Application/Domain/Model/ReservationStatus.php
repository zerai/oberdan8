<?php declare(strict_types=1);

namespace Booking\Application\Domain\Model;

final class ReservationStatus
{
    public const NewArrival = 0;

    public const InProgress = 1;

    public const Pending = 2;

    public const Rejected = 3;

    public const Confirmed = 4;

    public const Sale = 5;

    public const PickedUp = 6;

    public const Options = ['NewArrival', 'InProgress', 'Pending', 'Rejected', 'Confirmed', 'Sale', 'PickedUp'];

    private string $name;

    private int $value;

    public static function NewArrival(): self
    {
        return new self('NewArrival', 0);
    }

    public static function InProgress(): self
    {
        return new self('InProgress', 1);
    }

    public static function Pending(): self
    {
        return new self('Pending', 2);
    }

    public static function Rejected(): self
    {
        return new self('Rejected', 3);
    }

    public static function Confirmed(): self
    {
        return new self('Confirmed', 4);
    }

    public static function Sale(): self
    {
        return new self('Sale', 5);
    }

    public static function PickedUp(): self
    {
        return new self('PickedUp', 6);
    }

    private function __construct(string $name, int $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public static function fromName(string $name): self
    {
        foreach (self::Options as $i => $n) {
            if ($n === $name) {
                return new self($n, $i);
            }
        }

        throw new \InvalidArgumentException('Unknown enum name given');
    }

    public static function fromValue(int $value): self
    {
        if (! isset(self::Options[$value])) {
            throw new \InvalidArgumentException('Unknown enum value given');
        }

        return new self(self::Options[$value], $value);
    }

    public function equals(?self $other): bool
    {
        return null !== $other && $this->name === $other->name;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function toString(): string
    {
        return $this->name;
    }
}
