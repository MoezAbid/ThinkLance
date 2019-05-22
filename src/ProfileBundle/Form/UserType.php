<?php

namespace ProfileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file')->add('pays', TextType::class, [
                'required' => true,
                'error_bubbling' => true,

            ]
        )->add('description', TextareaType::class, [
            'required' => true,
            'error_bubbling' => true,

        ])
            ->add('tarifMoyen', TextType::class, [
                'required' => true,
                'error_bubbling' => true,

            ])
            ->add('titreProfile', TextType::class, [
                'required' => true,
                'error_bubbling' => true,

            ])
            ->add('ville', TextType::class, [
                'required' => true,
                'error_bubbling' => true,

            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
