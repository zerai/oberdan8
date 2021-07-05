<?php declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\BackofficeUser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use function Zenstruck\Foundry\factory;

class SecurityWebtestCase extends WebTestCase
{
    protected $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    protected function logInAsAdmin(): void
    {
        $backofficeUserRepository = $this->client->getContainer()->get('App\Repository\BackofficeUserRepository');

        $admin = $backofficeUserRepository->findOneBy([
            'email' => 'admin@example.com',
        ]);

        if (null === $admin) {
            // find a persisted object for the given attributes, if not found, create with the attributes
            $factory = factory(BackofficeUser::class);
            $admin = $factory->findOrCreate([
                //  'id' => 100,
                'email' => 'admin@example.com',
                'active' => true,
                'password' => 'xxx',
                'roles' => ['ROLE_ADMIN'],
            ])->object();
        }

        $session = $this->client->getContainer()->get('session');

        $firewallName = 'secure_area';
        // if you don't define multiple connected firewalls, the context defaults to the firewall name
        // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
        $firewallContext = 'secured_area';

        // you may need to use a different token class depending on your application.
        // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken

        //$token = new UsernamePasswordToken('admin@example.com', null, $firewallName, ['ROLE_ADMIN']);

        $token = new PostAuthenticationGuardToken($admin, 'app_user_provider', $admin->getRoles());

        $session->set('_security_' . $firewallContext, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
