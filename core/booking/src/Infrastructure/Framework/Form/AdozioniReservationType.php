<?php declare(strict_types=1);

namespace Booking\Infrastructure\Framework\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

            ->add('notes', TextareaType::class, [
                'label' => 'Altre informazioni',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
