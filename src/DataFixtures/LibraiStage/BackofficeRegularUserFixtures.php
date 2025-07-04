<?php declare(strict_types=1);

namespace App\DataFixtures\LibraiStage;

use App\Entity\BackofficeUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BackofficeRegularUserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new BackofficeUser();
        $user->setActive(true);
        $user->setEmail('demo@example.com');
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'demo')
        );

        $manager->persist($user);

        $user = new BackofficeUser();
        $user->setActive(true);
        $user->setEmail('demo2@example.com');
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'demo')
        );

        $manager->persist($user);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['stage', 'stageSecurity', 'stageRegularUser'];
    }
}
