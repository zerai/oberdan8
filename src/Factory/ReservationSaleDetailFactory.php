<?php declare(strict_types=1);

namespace App\Factory;

use Booking\Application\Domain\Model\ReservationSaleDetail;
use Booking\Application\Domain\Model\ReservationStatus;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static ReservationSaleDetail|Proxy createOne(array $attributes = [])
 * @method static ReservationSaleDetail[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static ReservationSaleDetail|Proxy find($criteria)
 * @method static ReservationSaleDetail|Proxy findOrCreate(array $attributes)
 * @method static ReservationSaleDetail|Proxy first(string $sortedField = 'id')
 * @method static ReservationSaleDetail|Proxy last(string $sortedField = 'id')
 * @method static ReservationSaleDetail|Proxy random(array $attributes = [])
 * @method static ReservationSaleDetail|Proxy randomOrCreate(array $attributes = [])
 * @method static ReservationSaleDetail[]|Proxy[] all()
 * @method static ReservationSaleDetail[]|Proxy[] findBy(array $attributes)
 * @method static ReservationSaleDetail[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static ReservationSaleDetail[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method ReservationSaleDetail|Proxy create($attributes = [])
 */
final class ReservationSaleDetailFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://github.com/zenstruck/foundry#model-factories)
            'Status' => ReservationStatus::NewArrival(),
            'ReservationPackageId' => self::faker()->numberBetween(100, 200),
            'GeneralNotes ' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(ReservationSaleDetail $ReservationSaleDetail) {})
        ;
    }

    protected static function getClass(): string
    {
        return ReservationSaleDetail::class;
    }

    private function withReservationStatus(string $status = 'NewArrival'): ReservationStatus
    {
        return ReservationStatus::fromName($status);
    }

    public function withConfirmed1DayAgo(): self
    {
        return $this->addState([
            'status' => $this->withReservationStatus('Confirmed'),
            'pvtExtensionTime' => false,
            'pvtConfirmedAt' => (new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome')))->modify('- 1 days'),
        ]);
    }

    public function withConfirmed2DayAgo(): self
    {
        return $this->addState([
            'status' => $this->withReservationStatus('Confirmed'),
            'pvtExtensionTime' => false,
            'pvtConfirmedAt' => (new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome')))->modify('- 2 days'),
        ]);
    }

    public function withConfirmed7DayAgo(): self
    {
        return $this->addState([
            'status' => $this->withReservationStatus('Confirmed'),
            'pvtExtensionTime' => false,
            'pvtConfirmedAt' => (new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome')))->modify('- 7 days'),
        ]);
    }

    public function withConfirmed10DayAgo(): self
    {
        return $this->addState([
            'status' => $this->withReservationStatus('Confirmed'),
            'pvtExtensionTime' => false,
            'pvtConfirmedAt' => (new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome')))->modify('- 10days'),
        ]);
    }

    public function withConfirmed14DayAgo(): self
    {
        return $this->addState([
            'status' => $this->withReservationStatus('Confirmed'),
            'pvtExtensionTime' => false,
            'pvtConfirmedAt' => (new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome')))->modify('- 14 days'),
        ]);
    }

    public function withConfirmed16DayAgo(): self
    {
        return $this->addState([
            'status' => $this->withReservationStatus('Confirmed'),
            'pvtExtensionTime' => false,
            'pvtConfirmedAt' => (new \DateTimeImmutable("now", new \DateTimeZone('Europe/Rome')))->modify('- 16 days'),
        ]);
    }
}
