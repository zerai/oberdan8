<?php declare(strict_types=1);

namespace Booking\Infrastructure\Framework\Form;

use Booking\Infrastructure\Framework\Form\Dto\AdozioniReservationFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class AdozioniReservationType extends AbstractType
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

            ->add('adozioni', FileType::class, [
                'label' => 'File delle adozioni (formato PDF o immagine JPEG)',
                'required' => true,
            ])

            ->add('otherInfo', TextareaType::class, [
                'label' => 'Altre informazioni',
                'required' => false,
                'empty_data' => '',
            ])

            ->add('privacyConfirmed', CheckboxType::class, [
                'label' => 'Acconsento al trattamento dei dati secondo la normativa sulla privacy',
                'required' => true,
            ])

            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Invia',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => AdozioniReservationFormModel::class,
        ]);
    }
}
