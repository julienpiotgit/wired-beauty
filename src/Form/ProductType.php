<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    const ROLE_CUSTOMER = 'ROLE_CUSTOMER';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('type')
            ->add('gender')
            ->add('age_range')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.roles LIKE \'%ROLE_CUSTOMER%\' ');
                },
                'choice_label' => 'email'
            ])
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
