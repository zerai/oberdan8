<?php declare(strict_types=1);

namespace Booking\Adapter\Web\Free\Form;

use Booking\Adapter\Web\Free\Form\Dto\BookDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isbn', TextType::class, [
                'label' => 'Isbn',
                'required' => false,
                'empty_data' => '',
            ])
            ->add('title', TextType::class, [
                'label' => 'Titolo',
                'required' => true,
            ])
            ->add('author', TextType::class, [
                'label' => 'Autore',
                'required' => false,
                'empty_data' => '',
            ])
            ->add('volume', TextType::class, [
                'label' => 'Volume',
                'required' => false,
                'empty_data' => '',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => BookDto::class,
        ]);
    }
}
