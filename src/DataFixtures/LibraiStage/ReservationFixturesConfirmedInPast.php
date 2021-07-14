<?php declare(strict_types=1);

namespace App\DataFixtures\LibraiStage;

use App\Factory\BookFactory;
use App\Factory\ReservationFactory;
use App\Factory\ReservationSaleDetailFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ReservationFixturesConfirmedInPast extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        ReservationFactory::createOne([
            'books' => BookFactory::new()->many(5),
            'saleDetail' => ReservationSaleDetailFactory::new()->withConfirmed1DayAgo()->create(),
        ]);

        ReservationFactory::createOne([
            'books' => BookFactory::new()->many(5),
            'saleDetail' => ReservationSaleDetailFactory::new()->withConfirmed2DayAgo()->create(),
        ]);

        ReservationFactory::createOne([
            'books' => BookFactory::new()->many(5),
            'saleDetail' => ReservationSaleDetailFactory::new()->withConfirmed7DayAgo()->create(),
        ]);

        ReservationFactory::createOne([
            'books' => BookFactory::new()->many(5),
            'saleDetail' => ReservationSaleDetailFactory::new()->withConfirmed10DayAgo()->create(),
        ]);

        ReservationFactory::createOne([
            'books' => BookFactory::new()->many(5),
            'saleDetail' => ReservationSaleDetailFactory::new()->withConfirmed14DayAgo()->create(),
        ]);

        ReservationFactory::createOne([
            'books' => BookFactory::new()->many(5),
            'saleDetail' => ReservationSaleDetailFactory::new()->withConfirmed16DayAgo()->create(),
        ]);
    }

    public static function getGroups(): array
    {
        return ['stage', 'stageReservationInPast'];
    }
}
