<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\BlogMeta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogMetaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'required' => true,
                'help' => 'Fil meta tags',
            ])
            ->add('keywords', TextType::class, [
                'required' => true,
                'help' => 'Fil meta tag keywords',
            ])
            ->add('author', TextType::class, [
                'required' => true,
                'help' => 'Fil meta tag keywords',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BlogMeta::class,
            'csrf_protection' => false,
        ]);
    }
}
