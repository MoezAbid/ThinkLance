<?php

namespace ReclamationBundle\Form;

use function PHPSTORM_META\type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class reclamationsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('sujet')
            ->add('message',TextareaType::class)

        ->add('categorie',EntityType::class, array(
            'class'=>'ReclamationBundle\Entity\Categorie',
            'choice_label' =>'nomC',
            'multiple'=> false
        )

    )
            ->add('user',EntityType::class, array(
                    'class'=>'AppBundle\Entity\User',
                    'choice_label' =>'username',
                    'multiple'=> false
                )

            );
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ReclamationBundle\Entity\reclamations'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'reclamationbundle_reclamations';
    }


}
