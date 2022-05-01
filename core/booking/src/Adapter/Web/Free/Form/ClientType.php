<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Free\Form;

use Booking\Adapter\Web\Free\Form\Dto\ClientDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('last_name', TextType::class, [
                'label' => 'Cognome',
                'required' => true,
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Nome',
                'required' => true,
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Telefono',
                'required' => true,
            ])
            ->add('city', TextType::class, [
                'label' => 'Città',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => ClientDto::class,
        ]);
    }
}
