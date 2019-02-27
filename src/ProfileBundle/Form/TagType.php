<?php
namespace ProfileBundle\Form;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType{
    /**
     * @var ObjectManager
     */
    private $em;
   public function __construct(ObjectManager $objectManager)
    {

        $this->em = $objectManager;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder->addModelTransformer(new CollectionToArrayTransformer(),true)
          ->addModelTransformer(new tagtransformer($this->em),true);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['attr'=>['data-role'=>'tagsinput']]);
    }

    public function getParent()
    {
        return TextType::class;
    }

}