<?php

namespace ProfileBundle\Controller;

use ProfileBundle\Entity\ExperiencePro;
use ProfileBundle\Form\ExperienceProType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ExperienceProController extends Controller
{
    //Modifier Experience
    public function modifierExpAction($id, Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $formation = $em->getRepository(ExperiencePro::class)->find($id);

        $form = $this->createForm(ExperienceProType::class, $formation);

        $form = $form->handleRequest($request);
        if ($form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('visualiser_profile_page');
        }
        return $this->render('@Profile/Default/modifier_exp.html.twig', array(
            'form' => $form->createView()
        ));


    }

    //supprimmerExperience
    public function supprimerExpAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $formation = $em->getRepository(ExperiencePro::class)->find($id);
        $em->remove($formation);
        $em->flush();
        return $this->redirectToRoute('visualiser_profile_page');
    }
}
