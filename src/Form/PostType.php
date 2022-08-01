<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre",
                'row_attr' => [
                    'class' => "form-floating mb-3"
                ],
                'attr' => [
                    'placeholder' => "Titre"
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => "Contenu",
                'row_attr' => [
                    'class' => "form-floating mb-3"
                ],
                'attr' => [
                    'placeholder' => "Contenu",
                    'rows' => 5
                ]
            ])
            ->add('picture', FileType::class, [
                'label' => "Image d'en-tête",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        mimeTypes: ['image/jpeg', 'image/png'],
                        mimeTypesMessage: "Seuls les fichiers image .jpeg, .jpg, .png sont acceptés"
                    )
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => "title",
                // 'expanded' => true,
                'multiple' => false
                ])
                ->add('submit', SubmitType::class, [
                'label' => "Ajouter"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
