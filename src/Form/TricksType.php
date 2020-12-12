<?php

namespace App\Form;

use App\Entity\Tricks;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TricksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('pictures')
            ->add('video')
            // ->add('category', Category::class, array(
            //     'class' => Category::class,
            //     'choice_label' => 'Name',
            //     'multiple' => false,
            //     'label' => 'Veuillez sélectionner la catégorie de votre Trick'
            // ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }
}
