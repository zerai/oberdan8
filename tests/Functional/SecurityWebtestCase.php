<?php declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\BackofficeUser;
use App\Repository\BackofficeUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use function Zenstruck\Foundry\factory;

class SecurityWebtestCase extends WebTestCase
{
    protected $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function newLogInAsAdmin(): void
    {
        $backofficeUserRepository = $this->client->getContainer()->get(BackofficeUserRepository::class);
        // retrieve the test user
        $admin = $backofficeUserRepository->findOneBy([
            'email' => 'admin@example.com',
        ]);

        if (null === $admin) {
            // find a persisted object for the given attributes, if not found, create with the attributes
            $factory = factory(BackofficeUser::class);
            $admin = $factory->findOrCreate([
                'email' => 'admin@example.com',
                'active' => true,
                'password' => 'xxx',
                'roles' => ['ROLE_ADMIN'],
            ])->_real();
        }
        $this->client->followRedirects(true);
        // simulate $testUser being logged in
        $this->client->loginUser($admin, 'secured_area');
    }

    protected function logInAsAdmin(): void
    {
        $this->newLogInAsAdmin();
    }
}
