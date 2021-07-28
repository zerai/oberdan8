<?php declare(strict_types=1);

namespace Booking\Application\Domain\Model\ConfirmationStatus;

use DateTimeImmutable;

final class ConfirmationStatus
{
    /**
     * @var int  numero di giorni prima di essere considerato scaduto
     */
    private const DEFAULT_EXPIRATION_TIME = 7;

    /**
     * @var int numero di giorni da sommare al default expiration time
     */
    private const EXTENDED_EXPIRATION_TIME = 7;

    private DateTimeImmutable $confirmedAt;

    private ExtensionTime $extensionTime;

    public function __construct(DateTimeImmutable $confirmedAt, ExtensionTime $extensionTime)
    {
        $this->confirmedAt = $confirmedAt;
        $this->extensionTime = $extensionTime;
    }

    public static function create(DateTimeImmutable $confirmedAt): self
    {
        return new self(
            $confirmedAt,
            //new ExtensionTime(false)
        ExtensionTime::false()
        );
    }

    public function isExpired(): bool
    {
        //$today = new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome'));
        $today = new \DateTimeImmutable("today", new \DateTimeZone('Europe/Rome'));

        $expirationDays = $this->extensionTime()->value() === true ? self::DEFAULT_EXPIRATION_TIME + self::EXTENDED_EXPIRATION_TIME : self::DEFAULT_EXPIRATION_TIME;

        $interval = $this->confirmedAt()->diff($today);

        if ($interval->days >= $expirationDays) {
            return true;
        } else {
            return false;
        }
    }

    public function expireAt(): DateTimeImmutable
    {
        if ($this->extensionTime()->value()) {
            $expirationDays = self::DEFAULT_EXPIRATION_TIME + self::EXTENDED_EXPIRATION_TIME;
        } else {
            $expirationDays = self::DEFAULT_EXPIRATION_TIME;
        }

        $expirationDate = $this->confirmedAt();

        $newDate = $expirationDate->modify(
            //"+ ${expirationDays} days"
            sprintf("+ %s days", (string) $expirationDays)
        );

        return $newDate;
    }

    public function confirmedAt(): DateTimeImmutable
    {
        return $this->confirmedAt;
    }

    public function withConfirmedAt(DateTimeImmutable $confirmedAt): self
    {
        return new self(
            $confirmedAt,
            $this->extensionTime
        );
    }

    public function extensionTime(): ExtensionTime
    {
        return $this->extensionTime;
    }

    public function withExtensionTime(ExtensionTime $extensionTime): self
    {
        return new self(
            $this->confirmedAt,
            $extensionTime
        );
    }

    public static function fromArray(array $data): self
    {
        if (! isset($data['confirmedAt']) || ! DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.uP', $data['confirmedAt'], new \DateTimeZone('Europe/Rome'))
        ) {
            throw new \InvalidArgumentException('Error on "confirmedAt", datetime string expected');
        }

        if (! isset($data['extensionTime']) || ! \is_bool($data['extensionTime'])) {
            throw new \InvalidArgumentException('Error on "extensionTime", bool expected');
        }

        return new self(
            (function () use ($data) {
                $_x = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.uP', $data['confirmedAt'], new \DateTimeZone('Europe/Rome'));

                if (false === $_x) {
                    throw new \UnexpectedValueException('Expected a date time string');
                }

                return $_x;
            })(),
            new ExtensionTime($data['extensionTime']),
        );
    }

    public function toArray(): array
    {
        return [
            'confirmedAt' => $this->confirmedAt->format('Y-m-d\TH:i:s.uP'),
            'extensionTime' => $this->extensionTime->value(),
        ];
    }

    public function equals(?ConfirmationStatus $other): bool
    {
        if (! $other instanceof self) {
            return false;
        }

        if (! ($this->confirmedAt === $other->confirmedAt)) {
            return false;
        }

        if (! $this->extensionTime->equals($other->extensionTime)) {
            return false;
        }

        return true;
    }
}
