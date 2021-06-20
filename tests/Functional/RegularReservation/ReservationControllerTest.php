<?php declare(strict_types=1);

namespace App\Tests\Functional\RegularReservation;

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

class ReservationControllerTest extends PantherTestCase
{
    private const REDIRECT_AFTER_SUBMIT = '/esito';

    /** @test */
    public function reservationPageShouldBeAccessibile(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/reservation');
        self::assertResponseIsSuccessful();

        self::assertPageTitleSame('Oberdan - banco 8 - prenotazioni', $message = 'Unexpected title in reservation page');
    }

    /** @test */
    public function validDataShouldPassTheFormValidation(): void
    {
        //$client = static::createClient();
        //$client = Client::createChromeClient();
        $client = static::createPantherClient([
            'browser' => static::FIREFOX,
        ]);
        //$client = static::createPantherClient(['browser' => static::CHROME]);
        $crawler = $client->request('GET', '/reservation');
        //self::assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        $form['reservation[otherInfo]'] = ReservationStaticFixture::NOTES;
        $form['reservation[privacyConfirmed]']->setValue(true);

        $client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $client->getCurrentURL());
    }

    /** @test */
    public function fullfilledFormWithoutOtherInfoShouldPassTheFormValidation(): void
    {
        $client = static::createPantherClient([
            'browser' => static::FIREFOX,
        ]);

        $crawler = $client->request('GET', '/reservation');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        $form['reservation[otherInfo]'] = '';
        $form['reservation[privacyConfirmed]']->setValue(true);

        $client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $client->getCurrentURL());
    }

    /** @test */
    public function fullfilledFormWithoutIsbnBookShouldPassTheFormValidation(): void
    {
        $client = static::createPantherClient([
            'browser' => static::FIREFOX,
        ]);

        $crawler = $client->request('GET', '/reservation');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['reservation[books][0][isbn]'] = '';
        $form['reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        $form['reservation[otherInfo]'] = ReservationStaticFixture::NOTES;
        $form['reservation[privacyConfirmed]']->setValue(true);

        $client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $client->getCurrentURL());
    }

    /** @test */
    public function fullfilledFormWithoutAuthorsBookShouldPassTheFormValidation(): void
    {
        $client = static::createPantherClient([
            'browser' => static::FIREFOX,
        ]);

        $crawler = $client->request('GET', '/reservation');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['reservation[books][0][author]'] = '';
        $form['reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        $form['reservation[otherInfo]'] = ReservationStaticFixture::NOTES;
        $form['reservation[privacyConfirmed]']->setValue(true);

        $client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $client->getCurrentURL());
    }

    /** @test */
    public function fullfilledFormWithoutVolumeBookShouldPassTheFormValidation(): void
    {
        $client = static::createPantherClient([
            'browser' => static::FIREFOX,
        ]);

        $crawler = $client->request('GET', '/reservation');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add books data
        $form['reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['reservation[books][0][volume]'] = '';

        $form['reservation[otherInfo]'] = ReservationStaticFixture::NOTES;
        $form['reservation[privacyConfirmed]']->setValue(true);

        $client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $client->getCurrentURL());
    }

    /** @test */
    public function submitAReservationWithTwoBooks(): void
    {
        $client = static::createPantherClient([
            'browser' => static::FIREFOX,
        ]);
        $crawler = $client->request('GET', '/reservation');

        $buttonCrawlerNode = $crawler->selectButton('Invia');

        $form = $buttonCrawlerNode->form();

        $form['reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // click add button
        $client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add first book data
        $form['reservation[books][0][isbn]'] = ReservationStaticFixture::BOOK_ONE_ISBN;
        $form['reservation[books][0][title]'] = ReservationStaticFixture::BOOK_ONE_TITLE;
        $form['reservation[books][0][author]'] = ReservationStaticFixture::BOOK_ONE_AUTHOR;
        $form['reservation[books][0][volume]'] = ReservationStaticFixture::BOOK_ONE_VOLUME;

        // click add button
        $client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add second book data
        $form['reservation[books][1][isbn]'] = ReservationStaticFixture::BOOK_TWO_ISBN;
        $form['reservation[books][1][title]'] = ReservationStaticFixture::BOOK_TWO_TITLE;
        $form['reservation[books][1][author]'] = ReservationStaticFixture::BOOK_TWO_AUTHOR;
        $form['reservation[books][1][volume]'] = ReservationStaticFixture::BOOK_TWO_VOLUME;

        $form['reservation[otherInfo]'] = ReservationStaticFixture::NOTES;
        $form['reservation[privacyConfirmed]']->setValue(true);

        $client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $client->getCurrentURL());
    }
}
