<?php declare(strict_types=1);

namespace App\DataFixtures\LibraiStage;

use App\Factory\BookFactory;
use App\Factory\ReservationFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ReservationFixtures extends Fixture implements FixtureGroupInterface
{
//    private UserPasswordEncoderInterface $passwordEncoder;
//
//    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
//    {
//        $this->passwordEncoder = $passwordEncoder;
//    }

    public function load(ObjectManager $manager): void
    {
        $reservation = ReservationFactory::createOne([
            'books' => BookFactory::new()->many(5),
        ]);

        $reservations = ReservationFactory::createMany(50, [
            'books' => BookFactory::new()->many(1, 8),
        ]);

        //$manager->persist($reservation);

        //$manager->flush();
    }

    public static function getGroups(): array
    {
        return ['stage', 'stageReservation'];
    }
}
