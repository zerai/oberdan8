<?php declare(strict_types=1);

namespace App\Form;

use App\Form\Dto\ClientDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('last_name', TextType::class, [
                'label' => 'Cognome',
                'required' => false,
                'empty_data' => '',
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Nome',
                'required' => false,
                'empty_data' => '',
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => ClientDto::class,
        ]);
    }
}
