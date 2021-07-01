<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\InfoBox;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class InfoBoxType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => 'Titolo',
                'empty_data' => '',
                'attr' => [
                    'label' => 'Titolo',
                ],
            ])
            ->add('body', TextareaType::class, [
                'required' => false,
                'label' => 'Corpo messaggio',
                'empty_data' => '',
                'attr' => [
                    'label' => 'Testo',
                ],
            ])
            ->add('active', ChoiceType::class, [
                'required' => true,
                'label' => 'Stato',
                'choices' => [
                    'Attivo' => true,
                    'Disattivo' => false,
                ],
                //'placeholder' => 'seleziona lo stato',

                //                'constraints' => [
                //                    new NotNull([
                //                        'message' => 'Seleziona lo stato ',
                //                    ]),
                //                ],
                'empty_data' => 0,
            ])
            ->add('boxType', ChoiceType::class, [
                'required' => true,
                'label' => 'Tipologia',
                'choices' => [
                    'nessuno' => 'none',
                    'Info' => 'info',
                    'Avviso' => 'warning',
                    'Attenzione' => 'danger',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InfoBox::class,
        ]);
    }
}
