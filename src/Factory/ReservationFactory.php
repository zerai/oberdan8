<?php declare(strict_types=1);

namespace App\Factory;

use Booking\Application\Domain\Model\Reservation;
use Booking\Application\Domain\Model\ReservationSaleDetail;
use Booking\Application\Domain\Model\ReservationStatus;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Reservation|Proxy createOne(array $attributes = [])
 * @method static Reservation[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Reservation|Proxy find($criteria)
 * @method static Reservation|Proxy findOrCreate(array $attributes)
 * @method static Reservation|Proxy first(string $sortedField = 'id')
 * @method static Reservation|Proxy last(string $sortedField = 'id')
 * @method static Reservation|Proxy random(array $attributes = [])
 * @method static Reservation|Proxy randomOrCreate(array $attributes = [])
 * @method static Reservation[]|Proxy[] all()
 * @method static Reservation[]|Proxy[] findBy(array $attributes)
 * @method static Reservation[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Reservation[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method Reservation|Proxy create($attributes = [])
 */
final class ReservationFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        $defaultSaleDetail = new ReservationSaleDetail();
        $defaultSaleDetail->setStatus(ReservationStatus::NewArrival());

        return [
            // TODO add your default values here (https://github.com/zenstruck/foundry#model-factories)
            'firstName' => self::faker()->firstName(),
            'lastName' => self::faker()->lastName(),
            'email' => self::faker()->unique()->email(),
            'phone' => self::faker()->phoneNumber(),
            'city' => self::faker()->city(),
            'classe' => 'Prima',
            'registrationDate' => new \DateTimeImmutable('now'),
            //'registrationDate' => self::faker()->dateTime('now', 'UTC'),
            'saleDetail' => $defaultSaleDetail,
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Reservation $reservation) {})
        ;
    }

    protected static function getClass(): string
    {
        return Reservation::class;
    }
}
