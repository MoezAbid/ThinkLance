<?php

namespace ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienceProType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titreExp',TextType::class, [
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

        ])->add('entreprise',TextType::class, [
            'required' => true,
            'error_bubbling' => true,

        ])->add('description', TextareaType::class, [
            'required' => true,
            'error_bubbling' => true,

        ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProfileBundle\Entity\ExperiencePro'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'profilebundle_experiencepro';
    }


}
