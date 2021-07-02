<?php declare(strict_types=1);

namespace App\Tests\E2E\AdozioniReservation;

use App\Tests\Support\Fixtures\ReservationStaticFixture;
use Symfony\Component\Panther\PantherTestCase;

class AdozioniReservationTest extends PantherTestCase
{
    private const REDIRECT_AFTER_SUBMIT = '/esito';

    private const PDF_FILE_1 = 'RMPC00500D_3A-NT-LI01-UNDEF.pdf';

    private const JPEG_FILE_1 = 'jpeg-fixture-file-1.jpg';

    /** @test */
    public function fullfilledFormWithPdfFileShouldPassTheFormValidation(): void
    {
        $client = self::createPantherClient([
            'browser' => static::FIREFOX,
        ]);

        $crawler = $client->request('GET', '/reservation/adozioni');

        $form = $crawler->selectButton('Invia')->form();

        $form['adozioni_reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['adozioni_reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['adozioni_reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['adozioni_reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['adozioni_reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['adozioni_reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // UPLOADED FILE
        //$form['adozioni_reservation[adozioni]']->upload(\dirname(__FILE__) . '/' . self::PDF_FILE_1);
        $form['adozioni_reservation[adozioni]']->upload(\dirname(__FILE__) . '/FileFixtures/' . self::PDF_FILE_1);

        $form['adozioni_reservation[otherInfo]'] = ReservationStaticFixture::NOTES;
        $form['adozioni_reservation[privacyConfirmed]']->setValue(true);

        $client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $client->getCurrentURL());
    }

    /** @test */
    public function fullfilledFormWithJpegFileShouldPassTheFormValidation(): void
    {
        $client = self::createPantherClient([
            'browser' => static::FIREFOX,
        ]);

        $crawler = $client->request('GET', '/reservation/adozioni');

        $form = $crawler->selectButton('Invia')->form();

        $form['adozioni_reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['adozioni_reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['adozioni_reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['adozioni_reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['adozioni_reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['adozioni_reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // UPLOADED FILE
        //$form['adozioni_reservation[adozioni]']->upload(\dirname(__FILE__) . '/' . self::PDF_FILE_1);
        $form['adozioni_reservation[adozioni]']->upload(\dirname(__FILE__) . '/FileFixtures/' . self::JPEG_FILE_1);

        $form['adozioni_reservation[otherInfo]'] = ReservationStaticFixture::NOTES;
        $form['adozioni_reservation[privacyConfirmed]']->setValue(true);

        $client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $client->getCurrentURL());
    }

    /** @test */
    public function fullfilledFormWithoutOtherInfoShouldPassTheFormValidation(): void
    {
        $client = self::createPantherClient([
            'browser' => static::FIREFOX,
        ]);

        $crawler = $client->request('GET', '/reservation/adozioni');

        $form = $crawler->selectButton('Invia')->form();

        $form['adozioni_reservation[person][last_name]'] = ReservationStaticFixture::LAST_NAME;
        $form['adozioni_reservation[person][first_name]'] = ReservationStaticFixture::FIRST_NAME;
        $form['adozioni_reservation[person][email]'] = ReservationStaticFixture::EMAIL;
        $form['adozioni_reservation[person][phone]'] = ReservationStaticFixture::PHONE;
        $form['adozioni_reservation[person][city]'] = ReservationStaticFixture::CITY;
        $form['adozioni_reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);

        // UPLOADED FILE
        //$form['adozioni_reservation[adozioni]']->upload(\dirname(__FILE__) . '/' . self::PDF_FILE_1);
        $form['adozioni_reservation[adozioni]']->upload(\dirname(__FILE__) . '/FileFixtures/' . self::PDF_FILE_1);

        $form['adozioni_reservation[otherInfo]'] = '';
        $form['adozioni_reservation[privacyConfirmed]']->setValue(true);

        $client->submit($form);

        self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $client->getCurrentURL());
    }

    /**
     * @test
     * @dataProvider missingFieldDataProvider
     */
    public function ShouldSeeErrorMessageForMissingData(string $missingField): void
    {
        $client = self::createPantherClient([
            'browser' => static::FIREFOX,
        ]);

        $crawler = $client->request('GET', '/reservation/adozioni');

        $form = $crawler->selectButton('Invia')->form();

        $form['adozioni_reservation[person][last_name]'] = ('last_name' === $missingField) ? '' : ReservationStaticFixture::LAST_NAME;
        $form['adozioni_reservation[person][first_name]'] = ('first_name' === $missingField) ? '' : ReservationStaticFixture::FIRST_NAME;
        $form['adozioni_reservation[person][email]'] = ('email' === $missingField) ? '' : ReservationStaticFixture::EMAIL;
        $form['adozioni_reservation[person][phone]'] = ('phone' === $missingField) ? '' : ReservationStaticFixture::PHONE;
        $form['adozioni_reservation[person][city]'] = ('city' === $missingField) ? '' : ReservationStaticFixture::CITY;

        if ('classe' === $missingField) {
            $form['adozioni_reservation[classe]']->setValue(null);
        } else {
            $form['adozioni_reservation[classe]']->setValue(ReservationStaticFixture::CLASSE);
        }

        // UPLOADED FILE
        //$form['adozioni_reservation[adozioni]']->upload(\dirname(__FILE__) . '/' . self::PDF_FILE_1);
        $form['adozioni_reservation[adozioni]']->upload(\dirname(__FILE__) . '/FileFixtures/' . self::PDF_FILE_1);

        $form['adozioni_reservation[otherInfo]'] = ReservationStaticFixture::NOTES;

        if ('privacy' === $missingField) {
            $form['adozioni_reservation[privacyConfirmed]']->setValue(null);
        } else {
            $form['adozioni_reservation[privacyConfirmed]']->setValue(true);
        }

        $client->submit($form);

        self::assertSelectorIsVisible('.form-error-message');

        //self::assertSame(self::$baseUri . self::REDIRECT_AFTER_SUBMIT, $client->getCurrentURL());
    }

    public function missingFieldDataProvider(): \Generator
    {
        yield 'missing last name' => ['last_name'];
        yield 'missing first name' => ['first_name'];
        yield 'missing email' => ['email'];
        yield 'missing phone' => ['phone'];
        yield 'missing city' => ['city'];
        yield 'missing classe' => ['classe'];
        yield 'missing privacy' => ['privacy'];
    }
}
