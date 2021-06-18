<?php declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MailSendingInReservationControllerTest extends WebTestCase
{
    private const BACKOFFICE_EMAIL_ADDRESS = 'Gestione prenotazioni <info@oberdan8.it>';

    private const FIRST_NAME = 'Carlo';

    private const LAST_NAME = 'Rossi';

    private const EMAIL = 'carlorossi@example.it';

    private const PHONE = '+39 392 111111';

    private const CITY = 'Roma';

    private const CLASSE = 'prima';

    private const NOTES = 'Avrei una certa urgenza di ricevere una vostra risposta';

    private const BOOK_ONE_ISBN = '1-56619-909-3';

    private const BOOK_ONE_TITLE = 'Matematica blu';

    private const BOOK_ONE_AUTHOR = 'Massimo Bergamini Graziella Barozzi Anna Trifone';

    private const BOOK_ONE_VOLUME = '2';

    private const BOOK_TWO_ISBN = '1-56619-909-3';

    private const BOOK_TWO_TITLE = 'Storia facile per le scuole superiori. Unità didattiche semplificate. Dalla preistoria al XIV secolo';

    private const BOOK_TWO_AUTHOR = 'Ferruccio Bianchi, Patrizia Farello, Carlo Scataglini';

    private const BOOK_TWO_VOLUME = 'vol.1';

    /** @test */
    public function afterFormSubmit_shouldSendTwoEmail(): void
    {
        $client = static::createClient();

        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

//        $crawler = $client->request('GET', '/reservation');
//        self::assertResponseIsSuccessful();
//        $extract = $crawler->filter('input[name="reservation[_token]"]')
//            ->extract(array('value'));
//        $extracted_csrf_token = $extract[0];
//
//        //dd($extracted_csrf_token);
//
//        $formData = array(
//            'reservation[person][last_name]' => self::LAST_NAME,
//            'reservation[person][first_name]' => self::FIRST_NAME,
//            'reservation[person][email]' => self::EMAIL,
//            'reservation[person][phone]' => self::PHONE,
//            'reservation[person][city]' => self::CITY,
//            'reservation[classe]' => self::CLASSE,
//            // Add first book data
//            'reservation[books][0][isbn]' => self::BOOK_ONE_ISBN,
//            'reservation[books][0][title]' => self::BOOK_ONE_TITLE,
//            'reservation[books][0][author]' => self::BOOK_ONE_AUTHOR,
//            'reservation[books][0][volume]' => self::BOOK_ONE_VOLUME,
//
//            'reservation[otherInfo]' => self::BOOK_ONE_TITLE,
//            'reservation[privacyConfirmed]' => true,
//            'reservation[submit]' => '',
//            //'reservation[_token]' => $extracted_csrf_token,
//            'reservation[_token]' => $csrfToken->getValue()
//        );

        $client->request(
            'POST',
            '/reservation',
            [
                'reservation' => [
                    'person' => [
                        "last_name" => self::LAST_NAME,
                        "first_name" => self::FIRST_NAME,
                        "email" => self::EMAIL,
                        "phone" => self::PHONE,
                        "city" => self::CITY,
                    ],
                    'classe' => self::CLASSE,
                    'books' => [
                        [
                            "isbn" => self::BOOK_ONE_ISBN,
                            "title" => self::BOOK_ONE_TITLE,
                            "author" => self::BOOK_ONE_AUTHOR,
                            "volume" => self::BOOK_ONE_VOLUME,
                        ],
                        [
                            "isbn" => self::BOOK_TWO_ISBN,
                            "title" => self::BOOK_TWO_TITLE,
                            "author" => self::BOOK_TWO_AUTHOR,
                            "volume" => self::BOOK_TWO_VOLUME,
                        ],                    ],
                    "otherInfo" => "Vorrei sapere di che anno è la vostra edizione.",
                    "privacyConfirmed" => "1",
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
            //['content-type' => "application/x-www-form-urlencoded"],
            //http_build_query($formData)
        );

        self::assertResponseRedirects('/esito');

        self::assertEmailCount(2);
    }

    /** @test */
    public function afterFormSubmit_shouldSendAReservationConfirmationEmailToClient(): void
    {
        $client = static::createClient();

        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $client->request(
            'POST',
            '/reservation',
            [
                'reservation' => [
                    'person' => [
                        "last_name" => self::LAST_NAME,
                        "first_name" => self::FIRST_NAME,
                        "email" => self::EMAIL,
                        "phone" => self::PHONE,
                        "city" => self::CITY,
                    ],
                    'classe' => self::CLASSE,
                    'books' => [
                        [
                            "isbn" => self::BOOK_ONE_ISBN,
                            "title" => self::BOOK_ONE_TITLE,
                            "author" => self::BOOK_ONE_AUTHOR,
                            "volume" => self::BOOK_ONE_VOLUME,
                        ],
                        [
                            "isbn" => self::BOOK_TWO_ISBN,
                            "title" => self::BOOK_TWO_TITLE,
                            "author" => self::BOOK_TWO_AUTHOR,
                            "volume" => self::BOOK_TWO_VOLUME,
                        ],                    ],
                    "otherInfo" => "Vorrei sapere di che anno è la vostra edizione.",
                    "privacyConfirmed" => "1",
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects('/esito');

        self::assertEmailCount(2);

        $email = self::getMailerMessage(0);
        $this->assertEmailHeaderSame($email, 'To', self::EMAIL);
        $this->assertEmailTextBodyContains($email, self::LAST_NAME);
        $this->assertEmailTextBodyContains($email, self::FIRST_NAME);
        $this->assertEmailTextBodyContains($email, self::EMAIL);
        $this->assertEmailTextBodyContains($email, self::PHONE);
        // TODO image attach
        //$this->assertEmailAttachmentCount($email, 0);
    }

    /** @test */
    public function afterFormSubmit_shouldSendANewReservationEmailToBackoffice(): void
    {
        $client = static::createClient();

        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $client->request(
            'POST',
            '/reservation',
            [
                'reservation' => [
                    'person' => [
                        "last_name" => self::LAST_NAME,
                        "first_name" => self::FIRST_NAME,
                        "email" => self::EMAIL,
                        "phone" => self::PHONE,
                        "city" => self::CITY,
                    ],
                    'classe' => self::CLASSE,
                    'books' => [
                        [
                            "isbn" => self::BOOK_ONE_ISBN,
                            "title" => self::BOOK_ONE_TITLE,
                            "author" => self::BOOK_ONE_AUTHOR,
                            "volume" => self::BOOK_ONE_VOLUME,
                        ],
                        [
                            "isbn" => self::BOOK_TWO_ISBN,
                            "title" => self::BOOK_TWO_TITLE,
                            "author" => self::BOOK_TWO_AUTHOR,
                            "volume" => self::BOOK_TWO_VOLUME,
                        ],                    ],
                    "otherInfo" => "Vorrei sapere di che anno è la vostra edizione.",
                    "privacyConfirmed" => "1",
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects('/esito');

        self::assertEmailCount(2);

        $email = self::getMailerMessage(1);
        self::assertEmailHeaderSame($email, 'To', self::BACKOFFICE_EMAIL_ADDRESS);
        self::assertEmailTextBodyContains($email, self::LAST_NAME);
        self::assertEmailTextBodyContains($email, self::FIRST_NAME);
        self::assertEmailTextBodyContains($email, self::EMAIL);
        self::assertEmailTextBodyContains($email, self::PHONE);
        self::assertEmailAttachmentCount($email, 0);
    }
}
