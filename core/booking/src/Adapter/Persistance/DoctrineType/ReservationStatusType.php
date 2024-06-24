<?php declare(strict_types=1);


namespace Booking\Adapter\Persistance\DoctrineType;

use Booking\Application\Domain\Model\ReservationStatus;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use InvalidArgumentException;

class ReservationStatusType extends StringType
{
    public const NAME = 'reservation_status';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof ReservationStatus) {
            return $value;
        }

        try {
            $reservationStatus = ReservationStatus::fromName($value);
        } catch (InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, static::NAME);
        }

        return $reservationStatus;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof ReservationStatus) {
            return $value->toString();
        }

        throw ConversionException::conversionFailed($value, static::NAME);
    }

    public function getName()
    {
        return self::NAME;
    }
}
