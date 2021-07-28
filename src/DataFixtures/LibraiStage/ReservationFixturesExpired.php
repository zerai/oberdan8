<?php declare(strict_types=1);

namespace App\DataFixtures\LibraiStage;

use App\Factory\ReservationFactory;
use App\Factory\ReservationSaleDetailFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ReservationFixturesExpired extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $format = 'Y-m-d H:i:s';

        $daysAgo7 = (new \DateTimeImmutable("today"))->modify('- 7days');

        // EXPIRED 7 DAYS AGO IN THE MORNING
        ReservationFactory::createOne([
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmationDate(
                \DateTimeImmutable::createFromFormat($format, $daysAgo7->format('Y-m-d') . ' 08:15:00', new \DateTimeZone('Europe/Rome'))
            ),
        ]);

        // EXPIRED 7 DAYS AGO IN THE AFTERNOON
        ReservationFactory::createOne([
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmationDate(
                \DateTimeImmutable::createFromFormat($format, $daysAgo7->format('Y-m-d') . ' 18:15:00', new \DateTimeZone('Europe/Rome'))
            ),
        ]);

        $daysAgo14 = (new \DateTimeImmutable("today"))->modify('- 14days');

        // EXPIRED 14 DAYS AGO IN THE MORNING
        ReservationFactory::createOne([
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmationDate(
                \DateTimeImmutable::createFromFormat($format, $daysAgo14->format('Y-m-d') . ' 08:15:00', new \DateTimeZone('Europe/Rome'))
            )->withExtensionTime(),
        ]);

        // EXPIRED 14 DAYS AGO IN THE AFTERNOON
        ReservationFactory::createOne([
            'saleDetail' => ReservationSaleDetailFactory::new([
            ])->withConfirmationDate(
                \DateTimeImmutable::createFromFormat($format, $daysAgo14->format('Y-m-d') . ' 18:15:00', new \DateTimeZone('Europe/Rome'))
            )->withExtensionTime(),
        ]);
    }

    public static function getGroups(): array
    {
        return ['stage', 'stageReservationExpired'];
    }
}
