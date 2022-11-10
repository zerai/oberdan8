<?php declare(strict_types=1);

namespace Booking\Tests\Unit\Infrastructure\Form;

use Booking\Adapter\Web\Free\Form\AdozioniReservationType;
use Booking\Adapter\Web\Free\Form\Dto\AdozioniReservationFormModel;
use Booking\Adapter\Web\Free\Form\Dto\ClientDto;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * @covers \Booking\Adapter\Web\Free\Form\AdozioniReservationType
 */
class AdozioniReservationFormTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'person' => [
                'first_name' => 'foo',
                'last_name' => 'foo',
                'email' => 'irrelevant@example.com',
                'phone' => '123456',
                'city' => 'firenze',
            ],

            'otherInfo' => 'irrelevant notes',
            'privacyConfirmed' => true,
        ];

        $model = new AdozioniReservationFormModel();
        // $formData will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(AdozioniReservationType::class, $model);

        $expected = new AdozioniReservationFormModel();
        $client = new ClientDto();
        $client->setFirstName('foo');
        $client->setLastName('foo');
        $client->setEmail('irrelevant@example.com');
        $client->setPhone('123456');
        $client->setCity('firenze');
        $expected->person = $client;

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
