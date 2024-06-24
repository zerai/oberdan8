<?php

declare(strict_types=1);

namespace Booking\Application\Domain\Model\Booking\Client;

use InvalidArgumentException;

/**
 * null
 * @codeCoverageIgnore
 */
final class Color
{
    public const Varia = 0;

    public const Prima = 1;

    public const Seconda = 2;

    public const Terza = 3;

    public const Quarta = 4;

    public const Quinta = 5;

    public const Options = ['Varia', 'Prima', 'Seconda', 'Terza', 'Quarta', 'Quinta'];

    private string $name;

    private int $value;

    public static function Varia(): self
    {
        return new self('Varia', 0);
    }

    public static function Prima(): self
    {
        return new self('Prima', 1);
    }

    public static function Seconda(): self
    {
        return new self('Seconda', 2);
    }

    public static function Terza(): self
    {
        return new self('Terza', 3);
    }

    public static function Quarta(): self
    {
        return new self('Quarta', 4);
    }

    public static function Quinta(): self
    {
        return new self('Quinta', 5);
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
