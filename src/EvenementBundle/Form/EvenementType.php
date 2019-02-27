<?php

namespace EvenementBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvenementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')->add('file')->add('lieu')->add('description',TextareaType::class)->add('dateDebut',DateType::class, ['widget'=>'single_text'])->add('dateFin',DateType::class, ['widget'=>'single_text'])->add('nbrPlace')->add('prix')->add('idCategorie',EntityType::class, array(
            'class'=>'EvenementBundle\Entity\Categorie' ,
            'choice_label'=> 'nom',
            'multiple' =>false
        ));
    }/**
    }/**
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EvenementBundle\Entity\Evenement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'evenementbundle_evenement';
    }


}
