<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Titre de la catégorie'
            ]);

            // ->add('createdAt', DateTimeType::class, [
            //     'label' => 'Date de création',
            //     'input' => 'datetime_immutable',
            //     'widget' => 'single_text'
            // ]);

            // ->add('updatedAt', DateTimeType::class, [
            //     'label' => 'Date de mise à jour',
            //     'input' => 'datetime_immutable',
            //     'widget' => 'single_text'
            // ])

            // ->add('submit', SubmitType::class, [
            //     'label' => 'Valider'
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
