<?php
namespace AppBundle\Form;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TagType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder->addModelTransformer(new CollectionToArrayTransformer(),true)
          ->addModelTransformer(new tagtransformer(),true);
    }

    public function getParent()
    {
        return TextType::class;
    }

}