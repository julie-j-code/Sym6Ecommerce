<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Categories;
use Doctrine\DBAL\Types\BooleanType;
use PhpParser\Node\Expr\AssignOp\Plus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q', TextType::class, [
                'attr' => ['class' => 'form-control mb-5', 'placeholder' => 'Rechercher'],
                'required' => false,
                'label' => false
            ])
            ->add('categories', EntityType::class, [
                'attr' => ['class' => 'form-control mb-5'],
                'class' => Categories::class,
                'choice_label' => function ($categories) {
                    return $categories->getName();
                },
                'multiple' => true,
                'label' => false
            ])
            ->add('max', NumberType::class, [
                'attr' => ['class' => 'form-control mb-5 d-iline-block', 'placeholder' => 'prix max'],
                'label' => false,
                'required' => false
            ])
            ->add('min', NumberType::class, [
                'attr' => ['class' => 'form-control mb-5 d-iline-block', 'placeholder' => 'prix min'],
                'label' => false,
                'required' => false
            ])
            ->add('promo', CheckboxType::class, [
                'attr' => ['class' => 'check-box ms-3 mb-5',],
                'label' => 'En promotion',
                'required' => false
            ])
            ->add('valider', ButtonType::class, [
                'attr'=>['class'=>'btn btn-primary'],
                'label'=>'valider'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Quelle classe sert pour représenter mes données
            'data_class' => SearchData::class,
            'method' => 'GET',
            // Protection inutile
            'csrf_protection' => false

        ]);
    }

    // je ne comprends pas !
    public function getBlockPrefix()
    {
        return '';
    }
}
