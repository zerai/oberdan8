<?php declare(strict_types=1);

namespace App\Tests\Functional\RegularReservation;

use App\Tests\Support\Fixtures\ReservationStaticFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class MailSendingInReservationControllerTest extends WebTestCase
{
    private const REDIRECT_AFTER_SUBMIT = '/esito';

    /** @test */
    public function afterFormSubmit_shouldSendTwoEmail(): void
    {
        $client = static::createClient();

        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $client->request(
            Request::METHOD_POST,
            '/reservation',
            [
                'reservation' => [
                    'person' => [
                        "last_name" => ReservationStaticFixture::LAST_NAME,
                        "first_name" => ReservationStaticFixture::FIRST_NAME,
                        "email" => ReservationStaticFixture::EMAIL,
                        "phone" => ReservationStaticFixture::PHONE,
                        "city" => ReservationStaticFixture::CITY,
                    ],
                    'classe' => ReservationStaticFixture::CLASSE,
                    'books' => [
                        [
                            "isbn" => ReservationStaticFixture::BOOK_ONE_ISBN,
                            "title" => ReservationStaticFixture::BOOK_ONE_TITLE,
                            "author" => ReservationStaticFixture::BOOK_ONE_AUTHOR,
                            "volume" => ReservationStaticFixture::BOOK_ONE_VOLUME,
                        ],
                        [
                            "isbn" => ReservationStaticFixture::BOOK_TWO_ISBN,
                            "title" => ReservationStaticFixture::BOOK_TWO_TITLE,
                            "author" => ReservationStaticFixture::BOOK_TWO_AUTHOR,
                            "volume" => ReservationStaticFixture::BOOK_TWO_VOLUME,
                        ],                    ],
                    "otherInfo" => "Vorrei sapere di che anno è la vostra edizione.",
                    "privacyConfirmed" => "1",
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects(self::REDIRECT_AFTER_SUBMIT);

        self::assertEmailCount(2);
    }

    /** @test */
    public function afterFormSubmit_shouldSendAReservationConfirmationEmailToClient(): void
    {
        $client = static::createClient();

        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $client->request(
            Request::METHOD_POST,
            '/reservation',
            [
                'reservation' => [
                    'person' => [
                        "last_name" => ReservationStaticFixture::LAST_NAME,
                        "first_name" => ReservationStaticFixture::FIRST_NAME,
                        "email" => ReservationStaticFixture::EMAIL,
                        "phone" => ReservationStaticFixture::PHONE,
                        "city" => ReservationStaticFixture::CITY,
                    ],
                    'classe' => ReservationStaticFixture::CLASSE,
                    'books' => [
                        [
                            "isbn" => ReservationStaticFixture::BOOK_ONE_ISBN,
                            "title" => ReservationStaticFixture::BOOK_ONE_TITLE,
                            "author" => ReservationStaticFixture::BOOK_ONE_AUTHOR,
                            "volume" => ReservationStaticFixture::BOOK_ONE_VOLUME,
                        ],
                        [
                            "isbn" => ReservationStaticFixture::BOOK_TWO_ISBN,
                            "title" => ReservationStaticFixture::BOOK_TWO_TITLE,
                            "author" => ReservationStaticFixture::BOOK_TWO_AUTHOR,
                            "volume" => ReservationStaticFixture::BOOK_TWO_VOLUME,
                        ],                    ],
                    "otherInfo" => "Vorrei sapere di che anno è la vostra edizione.",
                    "privacyConfirmed" => "1",
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects(self::REDIRECT_AFTER_SUBMIT);

        self::assertEmailCount(2);

        $email = self::getMailerMessage(0);
        self::assertEmailHeaderSame($email, 'To', ReservationStaticFixture::EMAIL);
        self::assertEmailTextBodyContains($email, ReservationStaticFixture::LAST_NAME);
        self::assertEmailTextBodyContains($email, ReservationStaticFixture::FIRST_NAME);
        self::assertEmailTextBodyContains($email, ReservationStaticFixture::EMAIL);
        self::assertEmailTextBodyContains($email, ReservationStaticFixture::PHONE);
        // TODO image attach
        //self::assertEmailAttachmentCount($email, 0);
    }

    /** @test */
    public function afterFormSubmit_shouldSendANewReservationEmailToBackoffice(): void
    {
        $client = static::createClient();

        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('reservation');

        $client->request(
            Request::METHOD_POST,
            '/reservation',
            [
                'reservation' => [
                    'person' => [
                        "last_name" => ReservationStaticFixture::LAST_NAME,
                        "first_name" => ReservationStaticFixture::FIRST_NAME,
                        "email" => ReservationStaticFixture::EMAIL,
                        "phone" => ReservationStaticFixture::PHONE,
                        "city" => ReservationStaticFixture::CITY,
                    ],
                    'classe' => ReservationStaticFixture::CLASSE,
                    'books' => [
                        [
                            "isbn" => ReservationStaticFixture::BOOK_ONE_ISBN,
                            "title" => ReservationStaticFixture::BOOK_ONE_TITLE,
                            "author" => ReservationStaticFixture::BOOK_ONE_AUTHOR,
                            "volume" => ReservationStaticFixture::BOOK_ONE_VOLUME,
                        ],
                        [
                            "isbn" => ReservationStaticFixture::BOOK_TWO_ISBN,
                            "title" => ReservationStaticFixture::BOOK_TWO_TITLE,
                            "author" => ReservationStaticFixture::BOOK_TWO_AUTHOR,
                            "volume" => ReservationStaticFixture::BOOK_TWO_VOLUME,
                        ],                    ],
                    "otherInfo" => "Vorrei sapere di che anno è la vostra edizione.",
                    "privacyConfirmed" => "1",
                    "submit" => "",
                    "_token" => $csrfToken->getValue(),
                ],
            ],
            [],
        );

        self::assertResponseRedirects(self::REDIRECT_AFTER_SUBMIT);

        self::assertEmailCount(2);

        $email = self::getMailerMessage(1);
        self::assertEmailHeaderSame($email, 'To', ReservationStaticFixture::BACKOFFICE_EMAIL_ADDRESS);
        self::assertEmailTextBodyContains($email, ReservationStaticFixture::LAST_NAME);
        self::assertEmailTextBodyContains($email, ReservationStaticFixture::FIRST_NAME);
        self::assertEmailTextBodyContains($email, ReservationStaticFixture::EMAIL);
        self::assertEmailTextBodyContains($email, ReservationStaticFixture::PHONE);
        self::assertEmailAttachmentCount($email, 0);
    }
}
