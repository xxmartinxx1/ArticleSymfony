<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class ArticleAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Tytuł',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Tytuł nie może być pusty']),
                    new Assert\Length(['max' => 255, 'maxMessage' => 'Tytuł może zawierać maksymalnie {{ limit }} znaków']),
                    new Assert\Length(['min' => 5, 'minMessage' => 'Tytuł nie może zawierać mniej niż {{ limit }} znaków']),
                ],
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Treść',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Treść artykułu nie może być pusty']),
                    new Assert\Length(['min' => 30, 'minMessage' => 'Treść artykułu nie może zawierać mniej niż {{ limit }} znaków']),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9\s\-\@\:\"\(\)śćąęłżźńó!?,.;]+$/', // litery, cyfry oraz znaki interpunkcyjne takie jak kropki, przecinki, wykrzykniki, znaki zapytania, spacje, nawiasy, @, " -, oraz polskie znaki diakrytyczne
                        'message' => 'Tekst zawiera niedozwolone znaki specjalne.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 10,
                ]
            ])
            ->add('Relation', EntityType::class, [
                'label' => 'Autor',
                'class' => User::class,
                'choice_label' => 'username',
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'add_article',
        ]);
    }
}
