<?php declare(strict_types=1);

namespace App\Tests\E2E\RegularReservation;

use App\Tests\Support\Fixtures\ReservationStaticFixture;
use Symfony\Component\Panther\PantherTestCase;

class ReservationControllerTest extends PantherTestCase
{
    private const REDIRECT_AFTER_SUBMIT = '/esito';

    protected $client = null;

    public function setUp(): void
    {
        $this->client = static::createPantherClient([
            'browser' => static::FIREFOX,
        ]);
    }

    public function tearDown(): void
    {
        $this->client = null;
    }

    /** @test */
    public function reservationPageShouldBeAccessibile(): void
    {
        $this->client->request('GET', '/reservation');

        self::assertPageTitleSame('Oberdan - banco 8 - prenotazioni', $message = 'Unexpected title in reservation page');
    }

    /** @test */
    public function shouldSubmitFormWithAllFields(): void
    {
        $crawler = $this->client->request('GET', '/reservation');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $this->client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        $form['reservation[otherInfo]'] = ReservationStaticFixture::NOTES;
        $form['reservation[privacyConfirmed]']->setValue(true);

        $this->client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $this->client->getCurrentURL());
    }

    /** @test */
    public function shouldSubmitDataWithoutOtherInfoField(): void
    {
        $crawler = $this->client->request('GET', '/reservation');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $this->client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        $form['reservation[otherInfo]'] = '';
        $form['reservation[privacyConfirmed]']->setValue(true);

        $this->client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $this->client->getCurrentURL());
    }

    /** @test */
    public function shouldSubmitFormWithoutIsbnBook(): void
    {
        $crawler = $this->client->request('GET', '/reservation');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $this->client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['reservation[books][0][isbn]'] = '';
        $form['reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        $form['reservation[otherInfo]'] = ReservationStaticFixture::NOTES;
        $form['reservation[privacyConfirmed]']->setValue(true);

        $this->client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $this->client->getCurrentURL());
    }

    /** @test */
    public function shouldSubmitFormWithoutAuthorsBookFiled(): void
    {
        $crawler = $this->client->request('GET', '/reservation');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $this->client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['reservation[books][0][author]'] = '';
        $form['reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        $form['reservation[otherInfo]'] = ReservationStaticFixture::NOTES;
        $form['reservation[privacyConfirmed]']->setValue(true);

        $this->client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $this->client->getCurrentURL());
    }

    /** @test */
    public function shouldSubmitFormWithoutVolumeBookField(): void
    {
        $crawler = $this->client->request('GET', '/reservation');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $this->client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['reservation[books][0][volume]'] = '';

        $form['reservation[otherInfo]'] = ReservationStaticFixture::NOTES;
        $form['reservation[privacyConfirmed]']->setValue(true);

        $this->client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $this->client->getCurrentURL());
    }

    /** @test */
    public function shouldSubmitAReservationWithTwoBooks(): void
    {
        $crawler = $this->client->request('GET', '/reservation');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $this->client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add first book data
        $form['reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        // click add button
        $this->client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add second book data
        $form['reservation[books][1][isbn]'] = ReservationStaticFixture::BOOK_TWO_ISBN;
        $form['reservation[books][1][title]'] = ReservationStaticFixture::BOOK_TWO_TITLE;
        $form['reservation[books][1][author]'] = ReservationStaticFixture::BOOK_TWO_AUTHOR;
        $form['reservation[books][1][volume]'] = ReservationStaticFixture::BOOK_TWO_VOLUME;

        $form['reservation[otherInfo]'] = ReservationStaticFixture::NOTES;
        $form['reservation[privacyConfirmed]']->setValue(true);

        $this->client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $this->client->getCurrentURL());
    }
}
