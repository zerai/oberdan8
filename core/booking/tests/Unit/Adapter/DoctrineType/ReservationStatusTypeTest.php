<?php declare(strict_types=1);


namespace Booking\Tests\Unit\Adapter\DoctrineType;

use Booking\Adapter\Persistance\DoctrineType\ReservationStatusType;
use Booking\Application\Domain\Model\ReservationStatus;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySqlPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Booking\Adapter\Persistance\DoctrineType\ReservationStatusType
 */
class ReservationStatusTypeTest extends TestCase
{
    /** @var Type|null */
    private $type;

    /** @var AbstractPlatform|null */
    private $platform;

    public function setUp(): void
    {
        $this->platform = new MySqlPlatform();
        try {
            $this->type = Type::getType('reservation_status_test');
        } catch (Exception $e) {
        }
    }

    public static function setUpBeforeClass(): void
    {
        Type::addType('reservation_status_test', ReservationStatusType::class);
    }

    /** @test */
    public function itCanGetName(): void
    {
        self::assertEquals('reservation_status', $this->type->getName());
    }

    /** @test */
    public function itCanConvertFromPhpValueToADatabaseValue(): void
    {
        $status = 'Pending';

        $reservationStatus = ReservationStatus::fromName($status);

        self::assertEquals($reservationStatus, $this->type->convertToDatabaseValue($reservationStatus, $this->platform));
    }

    /**
     * @test
     * @dataProvider  validValueForDbToPhpConversionProvider
     */
    public function itCanConvertFromDatabaseValueToAPhpValue(string $inputValue): void
    {
        $value = $inputValue;

        $originalValue = ReservationStatus::fromName($value);

        $convertedPHPValue = $this->type->convertToPHPValue($value, $this->platform);

        self::assertTrue($originalValue->equals($convertedPHPValue));
    }

    public function validValueForDbToPhpConversionProvider(): Generator
    {
        return [
            yield 'new arrival' => ['NewArrival'],
            yield 'in progress' => ['InProgress'],
            yield 'pending' => ['Pending'],
            yield 'rejected' => ['Rejected'],
            yield 'confirmed' => ['Confirmed'],
            yield 'sale' => ['Sale'],
            yield 'picked up' => ['PickedUp'],
        ];
    }

    /** @test */
    public function it_can_convert_a_null_value_to_a_php_value(): void
    {
        self::assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    /** @test */
    public function it_can_not_convert_bad_value_to_php_value(): void
    {
        self::expectException(ConversionException::class);

        $this->type->convertToPHPValue('bad_value', $this->platform);
    }

    protected function tearDown(): void
    {
        $this->platform = null;
        $this->type = null;
    }
}
