<?php
namespace ProfileBundle\Form;
use ProfileBundle\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;

class tagtransformer implements DataTransformerInterface{
    /**
     * @var ObjectManager
     */
private $em;
    public function __construct(ObjectManager $objectManager)
    {

        $this->em = $objectManager;
    }
    //Get the data from database
    public function transform($value)
    {
        //return implode(',',$value);
    }

    public function reverseTransform($value)
    {


        $tagname=array_filter(array_unique(array_map('trim',explode(',',$value))));
       $oldTags=$this->em->getRepository('ProfileBundle:Tag')->findBy([
           'nomTag'=>$tagname
       ]);
       $newTags=array_diff($tagname,$oldTags);
       $tags=[];
        foreach ($newTags as $name){

            $tag=new Tag();
           $tag->setNomTag($name);
           $tags[]=$tag;
        }
      return $tags;
    }

}