<?php declare(strict_types=1);

namespace App\Form;

use App\Form\Model\BackofficeReservationFormModel;
use Booking\Infrastructure\Framework\Form\BookType;
use Booking\Infrastructure\Framework\Form\ClasseField;
use Booking\Infrastructure\Framework\Form\ClientType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BackofficeReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('person', ClientType::class, [
                'label' => false,
                'required' => true,
            ])

            ->add('classe', ClasseField::class, [
                'choices' => [
                    'Prima' => 'prima',
                    'Seconda' => 'seconda',
                    'Terza' => 'terza',
                    'Quarta' => 'Quarta',
                    'Quinta' => 'Quinta',
                    'Varia' => 'varia',
                ],
                'required' => true,
                'placeholder' => 'seleziona',
            ])

            ->add('books', CollectionType::class, [
                'label' => false,
                'required' => true,
                'entry_type' => BookType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'block_name' => 'book_lists',
            ])

            ->add('otherInfo', TextareaType::class, [
                'label' => 'Altre informazioni',
                'required' => false,
                'empty_data' => '',
            ])

//            ->add('privacyConfirmed', CheckboxType::class, [
//                'label' => 'Acconsento al trattamento dei dati secondo la normativa sulla privacy',
//                'required' => true,
//            ])

            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Invia',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BackofficeReservationFormModel::class,
            'csrf_token_id' => 'reservation',
        ]);
    }
}
