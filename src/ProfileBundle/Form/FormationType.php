<?php

namespace ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre',TextType::class, [
            'required' => true,
            'error_bubbling' => true,

        ])->add('dateDebut',DateType::class, [
            'widget' => 'single_text',
            'required' => true,
            'error_bubbling' => true,

        ])->add('dateFin',DateType::class, [
            'widget' => 'single_text',
            'required' => true,
            'error_bubbling' => true,

        ])->add('institution',TextType::class, [
            'required' => true,
            'error_bubbling' => true,

        ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'error_bubbling' => true,

            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProfileBundle\Entity\Formation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'profilebundle_formation';
    }


}
