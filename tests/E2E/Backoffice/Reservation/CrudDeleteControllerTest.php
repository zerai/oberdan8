<?php declare(strict_types=1);

namespace App\Tests\E2E\Backoffice\Reservation;

use App\Entity\BackofficeUser;
use App\Factory\ReservationFactory;
use App\Repository\BackofficeUserRepository;
use App\Tests\Support\Fixtures\ReservationStaticFixture;
use Booking\Adapter\Persistance\ReservationRepository;
use Booking\Application\Domain\Model\Reservation;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use function Zenstruck\Foundry\factory;

class CrudDeleteControllerTest extends PantherTestCase
{
    use ResetDatabase;
    use Factories;

    private const REDIRECT_AFTER_SUBMIT = '/admin/prenotazioni/';

    protected $client = null;

    public function setUp(): void
    {
        $this->client = static::createPantherClient([
            'browser' => static::FIREFOX,
        ]);
        $this->client->request('GET', '/');
        $this->logInAsAdmin();
    }

    public function tearDown(): void
    {
        $this->client = null;
    }

    /** @test */
    public function shouldDeleteAReservation(): void
    {
        /** @var Reservation $reservation */
        $reservation = ReservationFactory::createOne()->object();

        $this->client->request('GET', '/');

        $crawler = $this->client->request('GET', '/admin/prenotazioni/');

        self::assertPageTitleSame('Prenotazioni Administration - Oberdan 8');

        self::assertEquals(
            1,
            $crawler->filterXPath('//td//a/i[@class="fa fa-eye"]')->count()
        );

        $crawler = $this->client->request('GET', '/admin/prenotazioni/' . $reservation->getId()->toString());

        self::assertPageTitleSame('Prenotazioni Administration - Oberdan 8');

        $this->client->takeScreenshot('screen.png');

        $crawler->click('Elimina');

        self::assertPageTitleSame('Prenotazioni Administration - Oberdan 8');

        self::assertEquals(
            0,
            $crawler->filterXPath('//td//a/i[@class="fa fa-eye"]')->count()
        );

//        /** @var ReservationRepository $reservationRepository */
//        $reservationRepository = self::$container->get('Booking\Adapter\Persistance\ReservationRepository');
//
//        $result = $reservationRepository->find($reservation->getId());
//
//        $result = $reservationRepository->findAll();
//
//        dd($result);

    }

    protected function logInAsAdmin(): void
    {
        $kernel = static::createKernel();

        $kernel->boot();

        /** @var BackofficeUserRepository $backofficeUserRepository */
        $backofficeUserRepository = static::$kernel->getContainer()->get('App\Repository\BackofficeUserRepository');

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

        /** @var Session $session */
        $session = static::$kernel->getContainer()->get('session');

        //$session = $this->client->getContainer()->get('session');

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
