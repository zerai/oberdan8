<?php declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\PantherTestCase;

class ReservationControllerTest extends PantherTestCase
{
    private const FIRST_NAME = 'Carlo';

    private const LAST_NAME = 'Rossi';

    private const EMAIL = 'carlorossi@example.it';

    private const PHONE = '+39 392 111111';

    private const CITY = 'Roma';

    private const CLASSE = 'prima';

    private const NOTES = 'Avrei una certa urgenza di ricevere una vostra risposta';

    private const BOOK_ONE_ISBN = '1-56619-909-3';

    private const BOOK_ONE_TITLE = 'Matematica.blu';

    private const BOOK_ONE_AUTHOR = 'Massimo Bergamini Graziella Barozzi Anna Trifone';

    private const BOOK_ONE_VOLUME = '2';

    private const BOOK_TWO_ISBN = '1-56619-909-3';

    private const BOOK_TWO_TITLE = 'Storia facile per le scuole superiori. Unità didattiche semplificate. Dalla preistoria al XIV secolo';

    private const BOOK_TWO_AUTHOR = 'Ferruccio Bianchi, Patrizia Farello, Carlo Scataglini';

    private const BOOK_TWO_VOLUME = 'vol.1';

    /** @test */
    public function reservationPageShouldBeAccessibile(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/reservation');
        self::assertResponseIsSuccessful();

        self::assertPageTitleSame('Oberdan - banco 8 - prenotazioni', $message = 'Unexpected title in reservation page');

        //self::assertSelectorTextContains('h1', 'modulo esteso di prenotazione', $message = 'Unexpected H1 tag in reservation page');
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

        $form['reservation[person][last_name]'] = self::LAST_NAME;
        $form['reservation[person][first_name]'] = self::FIRST_NAME;
        $form['reservation[person][email]'] = self::EMAIL;
        $form['reservation[person][phone]'] = self::PHONE;
        $form['reservation[person][city]'] = self::CITY;
        $form['reservation[classe]']->setValue(self::CLASSE);

        // click add button
        $client->executeScript("document.querySelector('.js-add-book-item').click()");
        $client->takeScreenshot('var/screen.png');

        // Add books data
        $form['reservation[books][0][isbn]'] = self::BOOK_ONE_ISBN;
        $form['reservation[books][0][title]'] = self::BOOK_ONE_TITLE;
        $form['reservation[books][0][author]'] = self::BOOK_ONE_AUTHOR;
        $form['reservation[books][0][volume]'] = self::BOOK_ONE_VOLUME;

        $form['reservation[notes]'] = self::NOTES;
        $form['reservation[privacyConfirmed]']->setValue(true);

        $client->submit($form);

        self::assertSame(self::$baseUri . '/esito', $client->getCurrentURL()); // Assert we're still on the same page
        $client->takeScreenshot('var/screen.png');
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

        $form['reservation[person][last_name]'] = self::LAST_NAME;
        $form['reservation[person][first_name]'] = self::FIRST_NAME;
        $form['reservation[person][email]'] = self::EMAIL;
        $form['reservation[person][phone]'] = self::PHONE;
        $form['reservation[person][city]'] = self::CITY;
        $form['reservation[classe]']->setValue(self::CLASSE);

        // click add button
        $client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add first book data
        $form['reservation[books][0][isbn]'] = self::BOOK_ONE_ISBN;
        $form['reservation[books][0][title]'] = self::BOOK_ONE_TITLE;
        $form['reservation[books][0][author]'] = self::BOOK_ONE_AUTHOR;
        $form['reservation[books][0][volume]'] = self::BOOK_ONE_VOLUME;

        // click add button
        $client->executeScript("document.querySelector('.js-add-book-item').click()");

        // Add second book data
        $form['reservation[books][1][isbn]'] = self::BOOK_TWO_ISBN;
        $form['reservation[books][1][title]'] = self::BOOK_TWO_TITLE;
        $form['reservation[books][1][author]'] = self::BOOK_TWO_AUTHOR;
        $form['reservation[books][1][volume]'] = self::BOOK_TWO_VOLUME;

        $form['reservation[notes]'] = self::NOTES;
        $form['reservation[privacyConfirmed]']->setValue(true);

        $client->submit($form);

        self::assertSame(self::$baseUri . '/esito', $client->getCurrentURL()); // Assert we're still on the same page
    }
}
