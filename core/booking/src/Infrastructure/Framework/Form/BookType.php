<?php declare(strict_types=1);

namespace Booking\Infrastructure\Framework\Form;

use Booking\Infrastructure\Framework\Form\Dto\BookDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isbn', TextType::class, [
                'label' => 'Isbn',
                'required' => true,
            ])
            ->add('title', TextType::class, [
                'label' => 'Titolo',
                'required' => true,
            ])
            ->add('author', TextType::class, [
                'label' => 'Autore',
                'required' => true,
            ])
            ->add('volume', TextType::class, [
                'label' => 'Volume',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => BookDto::class,
        ]);
    }
}
