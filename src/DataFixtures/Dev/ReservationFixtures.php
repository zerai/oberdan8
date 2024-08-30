<?php declare(strict_types=1);

namespace App\DataFixtures\Dev;

use App\Factory\BookFactory;
use App\Factory\ReservationFactory;
use App\Factory\ReservationSaleDetailFactory;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ReservationFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $reservation = ReservationFactory::createOne([
            'books' => BookFactory::new()->many(5),
        ]);

        $reservations = ReservationFactory::createMany(50, [
            'books' => BookFactory::new()->many(1, 8),
        ]);

        $expiredReservations = ReservationFactory::createMany(50, [
            'books' => BookFactory::new()->many(1, 8),
        ]);

        $reservationWithCouponCode = ReservationFactory::createOne([
            'books' => BookFactory::new()->many(5),
        ]);

        $reservationWithCouponCode = ReservationFactory::new()->createOne([
            'coupondCode' => 'Ho il vostro biglietto giallo dello scorso anno',
            'books' => BookFactory::new()->many(5),
        ]);
        $reservationWithCouponCode = ReservationFactory::new()->createOne([
            'coupondCode' => 'Polizia di Stato',
            'books' => BookFactory::new()->many(5),
        ]);

        $reservationWithCouponCode = ReservationFactory::new()->createOne([
            'coupondCode' => 'Abbonamento Metrebus',
            'books' => BookFactory::new()->many(5),
        ]);

        $reservationWithCouponCode = ReservationFactory::new()->createOne([
            'coupondCode' => 'ALI - intesa San Paolo',
            'books' => BookFactory::new()->many(5),
        ]);

        $reservationWithCouponCode = ReservationFactory::new()->withCouponCode('Super coupon 2024')->createOne([

            'books' => BookFactory::new()->many(5),
        ]);

        $format = 'Y-m-d H:i:s';

        $confirmed_8dayAgo = (new DateTimeImmutable("today"))->modify('- 8days');
        // EXPIRED IN THE MORNING
        ReservationFactory::createOne([
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmationDate(
                DateTimeImmutable::createFromFormat($format, $confirmed_8dayAgo->format('Y-m-d') . ' 08:15:00', new DateTimeZone('Europe/Rome'))
            ),
        ]);

        // EXPIRED IN THE AFTERNOON
        ReservationFactory::createOne([
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmationDate(
                DateTimeImmutable::createFromFormat($format, $confirmed_8dayAgo->format('Y-m-d') . ' 18:15:00', new DateTimeZone('Europe/Rome'))
            ),
        ]);
    }

    public static function getGroups(): array
    {
        return ['dev', 'devReservation'];
    }
}
