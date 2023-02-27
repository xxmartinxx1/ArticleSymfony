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


class ArticleEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
         function lineCount($options){

             $text = $options->getText(); // pobranie treści z obiektu
             $lines = explode("\n", $text);
             $count = count($lines);
             //dd($count, $lines);
            return $count*2;
    }
        lineCount($options['data']);


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

                ],
                'attr' => [
                    'class' => 'form-control',
                    'rows' => lineCount($options['data']),
                    'cols' => 33,
                ]
            ])
            ->add('Relation', EntityType::class, [
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
            'csrf_token_id'   => 'edit_article',
        ]);
    }
}
