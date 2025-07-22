<?php

namespace App\Form;

use App\Entity\Boutique;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;

class TemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Store Name',
                'required' => true,
            ])
            ->add('couleur', ColorType::class, [
                'label' => 'Primary Color',
                'required' => false,
            ])
            ->add('backgroundColor', ColorType::class, [
                'label' => 'Background Color',
                'required' => false,
            ])
            ->add('customPanelJson', TextareaType::class, [
                'label' => 'Custom Config (JSON)',
                'required' => false,
                'attr' => ['rows' => 10],
            ])
            ->add('slogan', TextType::class, [
                'label' => 'Slogan',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boutique::class,
        ]);
    }
}