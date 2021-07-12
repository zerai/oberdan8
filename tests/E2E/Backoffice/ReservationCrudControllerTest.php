<?php declare(strict_types=1);

namespace App\Tests\E2E\Backoffice;

use App\Entity\BackofficeUser;
use App\Repository\BackofficeUserRepository;
use App\Tests\Support\Fixtures\ReservationStaticFixture;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use function Zenstruck\Foundry\factory;

class ReservationCrudControllerTest extends PantherTestCase
{
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
    public function reservationPageShouldBeAccessibile(): void
    {
        $this->client->request('GET', '/');
        $this->logInAsAdmin();
        $crawler = $this->client->request('GET', '/admin/prenotazioni');
        //self::assertResponseIsSuccessful();

        self::assertPageTitleSame('Prenotazioni Administration - Oberdan 8');
    }

    /** @test */
    public function newReservationPageShouldBeAccessibile(): void
    {
        $this->client->request('GET', '/');

        $crawler = $this->client->request('GET', '/admin/prenotazioni/new');

        self::assertPageTitleSame('Prenotazioni Administration - Oberdan 8');
    }

    /** @test */
    public function validDataShouldPassTheFormValidation(): void
    {

        $this->client->request('GET', '/');

        $crawler = $this->client->request('GET', '/admin/prenotazioni/new');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['backoffice_reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['backoffice_reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['backoffice_reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['backoffice_reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['backoffice_reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['backoffice_reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $this->client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['backoffice_reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['backoffice_reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['backoffice_reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['backoffice_reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        $form['backoffice_reservation[otherInfo]'] = ReservationStaticFixture::NOTES;

        $this->client->submit($form);

        $this->client->takeScreenshot('var/error-screenshots/screen.png');
        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $this->client->getCurrentURL());
    }

    /** @test */
    public function fullfilledFormWithoutOtherInfoShouldPassTheFormValidation(): void
    {

        $crawler = $this->client->request('GET', '/admin/prenotazioni/new');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['backoffice_reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['backoffice_reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['backoffice_reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['backoffice_reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['backoffice_reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['backoffice_reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $this->client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['backoffice_reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['backoffice_reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['backoffice_reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['backoffice_reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        $form['backoffice_reservation[otherInfo]'] = '';

        $this->client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $this->client->getCurrentURL());
    }

    /** @test */
    public function fullfilledFormWithoutIsbnBookShouldPassTheFormValidation(): void
    {
        $crawler = $this->client->request('GET', '/admin/prenotazioni/new');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['backoffice_reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['backoffice_reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['backoffice_reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['backoffice_reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['backoffice_reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['backoffice_reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $this->client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['backoffice_reservation[books][0][isbn]'] = '';
        $form['backoffice_reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['backoffice_reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['backoffice_reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        $form['backoffice_reservation[otherInfo]'] = ReservationStaticFixture::NOTES;

        $this->client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $this->client->getCurrentURL());
    }

    /** @test */
    public function fullfilledFormWithoutAuthorsBookShouldPassTheFormValidation(): void
    {
        $crawler = $this->client->request('GET', '/admin/prenotazioni/new');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['backoffice_reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['backoffice_reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['backoffice_reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['backoffice_reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['backoffice_reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['backoffice_reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $this->client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['backoffice_reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['backoffice_reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['backoffice_reservation[books][0][author]'] = '';
        $form['backoffice_reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        $form['backoffice_reservation[otherInfo]'] = ReservationStaticFixture::NOTES;

        $this->client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $this->client->getCurrentURL());
    }

    /** @test */
    public function fullfilledFormWithoutVolumeBookShouldPassTheFormValidation(): void
    {
        $crawler = $this->client->request('GET', '/admin/prenotazioni/new');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['backoffice_reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['backoffice_reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['backoffice_reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['backoffice_reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['backoffice_reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['backoffice_reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $this->client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['backoffice_reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['backoffice_reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['backoffice_reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['backoffice_reservation[books][0][volume]'] = '';

        $form['backoffice_reservation[otherInfo]'] = ReservationStaticFixture::NOTES;

        $this->client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $this->client->getCurrentURL());
    }

    /** @test */
    public function submitAReservationWithTwoBooks(): void
    {
        $this->client->request('GET', '/');
        $crawler = $this->client->request('GET', '/admin/prenotazioni/new');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['backoffice_reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['backoffice_reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['backoffice_reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['backoffice_reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['backoffice_reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['backoffice_reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $this->client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add first book data
        $form['backoffice_reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['backoffice_reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['backoffice_reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['backoffice_reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        // click add button
        $this->client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add second book data
        $form['backoffice_reservation[books][1][isbn]'] = ReservationStaticFixture::BOOK_TWO_ISBN;
        $form['backoffice_reservation[books][1][title]'] = ReservationStaticFixture::BOOK_TWO_TITLE;
        $form['backoffice_reservation[books][1][author]'] = ReservationStaticFixture::BOOK_TWO_AUTHOR;
        $form['backoffice_reservation[books][1][volume]'] = ReservationStaticFixture::BOOK_TWO_VOLUME;

        $form['backoffice_reservation[otherInfo]'] = ReservationStaticFixture::NOTES;

        $this->client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $this->client->getCurrentURL());

        /** @var BackofficeUserRepository $backofficeUserRepository */
        $backofficeUserRepository = static::$kernel->getContainer()->get('App\Repository\BackofficeUserRepository');
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
