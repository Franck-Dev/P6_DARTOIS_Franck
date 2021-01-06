<?php

namespace App\Form;

use App\Entity\Comments;
use App\Entity\Tricks;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Content', TextareaType::class, [
                'label' => 'Veuillez saisir votre commentaire içi :',
                'help' => 'Merci de rester ZEN!!!'])
            //->add('CreatedAt')
            ->add('Trick', EntityType::class, [
               'class' => Tricks::class,
               'choice_label' => 'Name',
            ])
            // ->add('User')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
