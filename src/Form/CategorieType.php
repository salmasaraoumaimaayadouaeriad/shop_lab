<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 3]
            ])
            ->add('icone', TextType::class, [
                'label' => 'Icon (FontAwesome class)',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'e.g., fa-shopping-cart'
                ]
            ])
            ->add('couleur', ColorType::class, [
                'label' => 'Color',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('parent', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'required' => false,
                'placeholder' => '-- No Parent --',
                'attr' => ['class' => 'form-select']
            ])
            ->add('ordre', IntegerType::class, [
                'label' => 'Order',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0
                ]
            ])
            ->add('actif', CheckboxType::class, [
                'label' => 'Active',
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
