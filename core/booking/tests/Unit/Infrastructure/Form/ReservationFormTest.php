<?php declare(strict_types=1);

namespace Booking\Tests\Unit\Infrastructure\Form;

use Booking\Adapter\Web\Free\Form\AdozioniReservationType;
use Booking\Adapter\Web\Free\Form\Dto\AdozioniReservationFormModel;
use Booking\Adapter\Web\Free\Form\Dto\BookDto;
use Booking\Adapter\Web\Free\Form\Dto\ClientDto;
use Booking\Adapter\Web\Free\Form\Dto\ReservationFormModel;
use Booking\Adapter\Web\Free\Form\ReservationType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class ReservationFormTest
 *
 * @covers \Booking\Infrastructure\Framework\Form\ReservationType
 * @package Booking\Tests\Unit\Infrastructure\Form
 */
class ReservationFormTest extends TypeTestCase
{
    public function testSubmitValidDataWithoutBook(): void
    {
        $formData = [
            'person' => [
                'first_name' => 'joe',
                'last_name' => 'doe',
                'email' => 'irrelevant@example.com',
                'phone' => '123456',
                'city' => 'rome',
            ],
            'classe' => 'prima',

            'otherInfo' => 'irrelevant notes',
            'privacyConfirmed' => true,
        ];

        $model = new ReservationFormModel();
        // $formData will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(ReservationType::class, $model);

        $expected = new ReservationFormModel();
        $client = new ClientDto();
        $client->setFirstName('joe');
        $client->setLastName('doe');
        $client->setEmail('irrelevant@example.com');
        $client->setPhone('123456');
        $client->setCity('rome');
        $expected->person = $client;
        $expected->classe = 'prima';
        $expected->books = [];

        $expected->otherInfo = 'irrelevant notes';
        $expected->privacyConfirmed = true;

        // ...populate $object properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        self::assertTrue($form->isSynchronized());

        // check that $formData was modified as expected when the form was submitted
        self::assertEquals($expected, $model);
    }

    public function testSubmitValidDataWithBook(): void
    {
        $formData = [
            'person' => [
                'first_name' => 'joe',
                'last_name' => 'doe',
                'email' => 'irrelevant@example.com',
                'phone' => '123456',
                'city' => 'rome',
            ],
            'classe' => 'prima',

            'books' => [
                [
                    'isbn' => '1-56619-909-3',
                    'title' => 'foo',
                    'author' => 'foo',
                    'volume' => 'foo',
                ],
            ],

            'otherInfo' => 'irrelevant notes',
            'privacyConfirmed' => true,
        ];

        $model = new ReservationFormModel();
        // $formData will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(ReservationType::class, $model);

        $expected = new ReservationFormModel();
        $client = new ClientDto();
        $client->setFirstName('joe');
        $client->setLastName('doe');
        $client->setEmail('irrelevant@example.com');
        $client->setPhone('123456');
        $client->setCity('rome');
        $bookDto = new BookDto();
        $bookDto->setIsbn('1-56619-909-3');
        $bookDto->setTitle('foo');
        $bookDto->setAuthor('foo');
        $bookDto->setVolume('foo');

        $expected->person = $client;
        $expected->classe = 'prima';
        $expected->books = [
            $bookDto,
        ];

        $expected->otherInfo = 'irrelevant notes';
        $expected->privacyConfirmed = true;

        // ...populate $object properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        self::assertTrue($form->isSynchronized());

        // check that $formData was modified as expected when the form was submitted
        self::assertEquals($expected, $model);
    }

    public function testCustomFormView(): void
    {
        self::markTestSkipped();
        $formData = new AdozioniReservationFormModel();

        $client = new ClientDto();
        $client->setFirstName('foo');
        $client->setLastName('foo');
        $client->setEmail('irrelevant@example.com');
        $client->setPhone('123456');
        $client->setCity('firenze');
        $formData->person = $client;

        $formData->otherInfo = 'irrelevant notes';
        $formData->privacyConfirmed = true;        // ... prepare the data as you need

        // The initial data may be used to compute custom view variables
        $view = $this->factory->create(AdozioniReservationType::class, $formData)
            ->createView();

        //var_dump($view->vars);

        $this->assertArrayHasKey('custom_var', $view->vars);
        $this->assertSame('expected value', $view->vars['custom_var']);
    }
}
