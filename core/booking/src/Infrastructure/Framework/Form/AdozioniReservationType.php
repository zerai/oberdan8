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
use Symfony\Component\Validator\Constraints\File;

class AdozioniReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('person', ClientType::class, [
                //                'entry_type' => ClientType::class,
                //                'entry_options' => [
                //                    'label' => false,
                //                ],
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
                'label' => 'File delle adozioni (formato PDF)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                'multiple' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '5120k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ]),
                ],
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Altre informazioni',
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
