<?php declare(strict_types=1);


namespace Booking\Infrastructure\Framework\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClasseField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'label' => 'Classe',
                // 'help' => 'La tua classe (es: 3B)',
                'constraints' => [
                    //new NotBlank(),
                    //new LeanpubInvoiceIdConstraint()
                ],
            ]
        );
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
