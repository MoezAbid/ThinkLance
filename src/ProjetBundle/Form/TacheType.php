<?php

namespace ProjetBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TacheType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nomTache')->add('typeTache', ChoiceType::class, array (
            'choices' => array (
                'test '=>' Test ',
                'deploiement'=>'Deploiement','developpement '=>' developpement ',
                'maintenance'=>'maintenance')))
            ->add('estimationTache')->add('prioriteTache')
            ->add('projet', EntityType::class, Array ('class'=>'ProjetBundle\Entity\Projet',
                'choice_label'=>'titreProjet'));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProjetBundle\Entity\Tache'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'projetbundle_tache';
    }


}
