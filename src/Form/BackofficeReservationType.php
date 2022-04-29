<?php declare(strict_types=1);

namespace App\Form;

use App\Form\Model\BackofficeReservationFormModel;
use Booking\Infrastructure\Framework\Form\BookType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BackofficeReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('person', ClientType::class, [
                'label' => false,
                'required' => true,
            ])

            ->add('classe', ChoiceType::class, [
                'choices' => [
                    'Non Disponibile' => 'non disponibile',
                    'Prima' => 'prima',
                    'Seconda' => 'seconda',
                    'Terza' => 'terza',
                    'Quarta' => 'Quarta',
                    'Quinta' => 'Quinta',
                    'Varia' => 'varia',
                ],
                'required' => false,
                //'empty_data' => null,
                //'data' => '',
                'placeholder' => false,
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

            ->add('generalNotes', TextareaType::class, [
                'required' => false,
                'label' => 'Note',
                'empty_data' => '',
                'attr' => [
                    'label' => 'Note',
                ],
            ])

            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Invia',
                ]
            )
        ;

        if ((bool) $options['include_reservation_status']) {
            $builder->add('status', ChoiceType::class, [
                'required' => true,
                //'mapped' => false,
                'label' => 'Status',
                'choices' => [
                    'Nuovo' => 'NewArrival',
                    'In lavorazione' => 'InProgress',
                    'Sospeso' => 'Pending',
                    'Rifiutato' => 'Rejected',
                    'Confermato' => 'Confirmed',
                    'Vendita' => 'Sale',
                    'Ritirato' => 'PickedUp',
                    'Blacklist' => 'Blacklist',
                ],
            ]);
        }

        if ((bool) $options['include_packageId']) {
            $builder->add('packageId', TextType::class, [
                'required' => false,
                'label' => 'Codice busta',
                'empty_data' => '',
                'attr' => [
                    'label' => 'Codice busta',
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BackofficeReservationFormModel::class,
            'csrf_token_id' => 'reservation',
            'include_reservation_status' => false,
            'include_packageId' => false,
        ]);
    }
}
