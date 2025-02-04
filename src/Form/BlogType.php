<?php

declare(strict_types=1);

namespace App\Form;

use App\Dbal\Type\BlogStatus;
use App\Entity\Blog;
use App\Entity\Category;
use App\Entity\User;
use App\Form\DataTransformer\TagTransformer;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    public function __construct(
        private readonly TagTransformer $transformer,
        private readonly Security $security,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'help' => 'Fill in the heading of the text',
                'attr' => [
                    'class' => 'myclass',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
            ])
            ->add('text', TextareaType::class, [
                'required' => true,
            ]);

        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder
                ->add('category', EntityType::class, [
                    'class' => Category::class,
                    'query_builder' => function ($repository) {
                        return $repository->createQueryBuilder('p')->orderBy('p.name', 'ASC');
                    },
                    'required' => false,
                    'empty_data' => '',
                    'placeholder' => '-- Choosing a category --',
                ])
                ->add('user', EntityType::class, [
                    'class' => User::class,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository->createQueryBuilder('p')->orderBy('p.id', 'ASC');
                    },
                    'required' => false,
                    'empty_data' => '',
                    'choice_label' => 'emailFormatted',
                    'placeholder' => '-- User choice --',
                ])
                ->add('status', EnumType::class, [
                    'class' => BlogStatus::class,
                ]);
        }

        $builder
            ->add('tags', TextType::class, [
                'label' => 'Теги',
                'required' => false,
            ]);

        $builder->get('tags')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
            'csrf_protection' => false,
        ]);
    }
}
