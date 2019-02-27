<?php

namespace EvenementBundle\Controller;

use EvenementBundle\Entity\Evenement;
use EvenementBundle\Entity\Participer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ParticiperController extends Controller
{
    public function participerAction($id)
    {
        $participer=new Participer();
        $em=$this->getDoctrine()->getManager();
        $participer->setIduser($this->getUser());
        $evenement=$em->getRepository(Evenement::class)->find($id);
        $evenement->setNbrplace( $evenement->getNbrplace()-1);

        $participer->setIdevent($evenement);
        $em->persist($participer);
        $em->flush();

        return $this->redirectToRoute('read' );
    }

}
