<?php declare(strict_types=1);

namespace App\Form;

use App\Form\Model\BackofficeReservationEditFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BackofficeReservationEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('packageId', TextType::class, [
                'required' => false,
                'label' => 'Codice busta',
                'empty_data' => '',
                'attr' => [
                    'label' => 'Codice busta',
                ],
            ])
            ->add('notes', TextareaType::class, [
                'required' => false,
                'label' => 'Note',
                'empty_data' => '',
                'attr' => [
                    'label' => 'Note',
                ],
            ])
            ->add('status', ChoiceType::class, [
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
            ])
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
            'data_class' => BackofficeReservationEditFormModel::class,
        ]);
    }
}
