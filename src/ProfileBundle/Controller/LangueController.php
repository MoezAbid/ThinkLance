<?php

namespace ProfileBundle\Controller;

use ProfileBundle\Entity\Langue;
use ProfileBundle\Form\LangueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LangueController extends Controller
{

//Modifier Langue
    public function modifierlangueAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $formation = $em->getRepository(Langue::class)->find($id);

        $form = $this->createForm(LangueType::class, $formation);

        $form = $form->handleRequest($request);
        if ($form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('visualiser_profile_page');
        }
        return $this->render('@Profile/Default/modifier_langue.html.twig', array(
            'form' => $form->createView()
        ));
    }
    //Supprimer langue

    public function supprimerlangueAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $formation = $em->getRepository(Langue::class)->find($id);
        $em->remove($formation);
        $em->flush();
        return $this->redirectToRoute('visualiser_profile_page');
    }
}
