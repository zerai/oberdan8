<?php declare(strict_types=1);

namespace App\Factory;

use Booking\Application\Domain\Model\Reservation;
use Booking\Application\Domain\Model\ReservationSaleDetail;
use Booking\Application\Domain\Model\ReservationStatus;
use DateTimeImmutable;
use DateTimeZone;
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

        /**
         * TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
         */
    }

    protected function getDefaults(): array
    {
        $defaultSaleDetail = new ReservationSaleDetail();
        $defaultSaleDetail->setStatus(ReservationStatus::NewArrival());

        return [
            /**
             * TODO add your default values here (https://github.com/zenstruck/foundry#model-factories)
             */
            'firstName' => self::faker()->firstName(),
            'lastName' => self::faker()->lastName(),
            'email' => self::faker()->unique()->email(),
            'phone' => self::faker()->phoneNumber(),
            'city' => self::faker()->city(),
            'classe' => 'Prima',
            'registrationDate' => new DateTimeImmutable('now', new DateTimeZone('Europe/Rome')),
            'coupondCode' => '',
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

    public function withCouponCode(string $couponCode): self
    {
        return $this->addState([
            'coupondCode' => $couponCode,
        ]);
    }

    public function withConfirmedStatus(): self
    {
        return $this->addState([
            'saleDetail' => $this->createReservationDetailWithStatus('Confirmed'),
        ]);
    }

    public function withInProgressStatus(): self
    {
        return $this->addState([
            'saleDetail' => $this->createReservationDetailWithStatus('InProgress'),
        ]);
    }

    public function withPendingStatus(): self
    {
        return $this->addState([
            'saleDetail' => $this->createReservationDetailWithStatus('Pending'),
        ]);
    }

    public function withRejectedStatus(): self
    {
        return $this->addState([
            'saleDetail' => $this->createReservationDetailWithStatus('Rejected'),
        ]);
    }

    public function withSaleStatus(): self
    {
        return $this->addState([
            'saleDetail' => $this->createReservationDetailWithStatus('Sale'),
        ]);
    }

    public function withPickedUpStatus(): self
    {
        return $this->addState([
            'saleDetail' => $this->createReservationDetailWithStatus('PickedUp'),
        ]);
    }

    public function withBlacklistStatus(): self
    {
        return $this->addState([
            'saleDetail' => $this->createReservationDetailWithStatus('Blacklist'),
        ]);
    }

    public function withShippedStatus(): self
    {
        return $this->addState([
            'saleDetail' => $this->createReservationDetailWithStatus('Shipped'),
        ]);
    }

    private function createReservationDetailWithStatus(string $status = 'NewArrival'): ReservationSaleDetail
    {
        $defaultSaleDetail = new ReservationSaleDetail();
        $defaultSaleDetail->setStatus(
            ReservationStatus::fromName($status)
        );

        return $defaultSaleDetail;
    }
}
