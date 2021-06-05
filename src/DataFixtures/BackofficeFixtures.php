<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\BackofficeUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BackofficeFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /**
     * BackofficeFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = new BackofficeUser();
        $user->setEmail('demo@example.com');
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, 'demo')
        );

        $manager->persist($user);

        $admin = new BackofficeUser();
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordEncoder->encodePassword($user, 'demo')
        );

        $manager->persist($admin);

        $manager->flush();
    }
}
