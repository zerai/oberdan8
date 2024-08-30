<?php declare(strict_types=1);

namespace App\DataFixtures\Dev;

use App\Factory\BookFactory;
use App\Factory\ReservationFactory;
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
    }

    public static function getGroups(): array
    {
        return ['dev', 'devReservation'];
    }
}
