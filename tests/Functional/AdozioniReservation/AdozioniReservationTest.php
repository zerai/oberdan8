<?php declare(strict_types=1);

namespace App\Tests\Functional\AdozioniReservation;

use Symfony\Component\Panther\PantherTestCase;

class AdozioniReservationTest extends PantherTestCase
{
    private const REDIRECT_AFTER_SUBMIT = '/esito';

    private const PDF_FILE_1 = 'RMPC00500D_3A-NT-LI01-UNDEF.pdf';

    /** @test */
    public function fullfilledFormShouldPassTheFormValidation(): void
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
}
