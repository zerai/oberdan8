<?php declare(strict_types=1);

namespace Booking\Application\Domain\Model;

use InvalidArgumentException;

final class ReservationStatus
{
    public const NewArrival = 0;

    public const InProgress = 1;

    public const Pending = 2;

    public const Rejected = 3;

    public const Confirmed = 4;

    public const Sale = 5;

    public const PickedUp = 6;

    public const Blacklist = 7;

    public const Shipped = 8;

    public const Options = [
        'NewArrival',
        'InProgress',
        'Pending',
        'Rejected',
        'Confirmed',
        'Sale',
        'PickedUp',
        'Blacklist',
        'Shipped',
    ];

    private string $name;

    private int $value;

    public static function newArrival(): self
    {
        return new self('NewArrival', 0);
    }

    public static function inProgress(): self
    {
        return new self('InProgress', 1);
    }

    public static function pending(): self
    {
        return new self('Pending', 2);
    }

    public static function rejected(): self
    {
        return new self('Rejected', 3);
    }

    public static function confirmed(): self
    {
        return new self('Confirmed', 4);
    }

    public static function sale(): self
    {
        return new self('Sale', 5);
    }

    public static function pickedUp(): self
    {
        return new self('PickedUp', 6);
    }

    public static function blacklist(): self
    {
        return new self('Blacklist', 7);
    }

    public static function Shipped(): self
    {
        return new self('Shipped', 8);
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

        throw new InvalidArgumentException('Unknown enum name given');
    }

    public static function fromValue(int $value): self
    {
        if (! isset(self::Options[$value])) {
            throw new InvalidArgumentException('Unknown enum value given');
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
