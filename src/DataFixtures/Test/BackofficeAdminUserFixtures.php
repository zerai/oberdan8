<?php declare(strict_types=1);

namespace App\DataFixtures\Test;

use App\Entity\BackofficeUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BackofficeAdminUserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new BackofficeUser();
        $admin->setActive(true);
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'demo')
        );

        $manager->persist($admin);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test', 'testSecurity', 'testAdminUser'];
    }
}
