<?php

namespace ProfileBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use ProfileBundle\Entity\Formation;
use ProfileBundle\Form\FormationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FormationController extends Controller
{


    //Modifier Formation
    public function modifierFormationAction($id, Request $request)
    {



        $em = $this->getDoctrine()->getManager();
        $formation = $em->getRepository(Formation::class)->find($id);
        $form = $this->createForm(FormationType::class, $formation);
        $form = $form->handleRequest($request);
        if ($form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('visualiser_profile_page');
        }
            return $this->render('@Profile/Default/modifier_formation.html.twig', array(
                'form' => $form->createView(),

            ));




    }

    //Supprimer Formation
    public function supprimerFormationAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $formation = $em->getRepository(Formation::class)->find($id);
        $em->remove($formation);
        $em->flush();
        return $this->redirectToRoute('visualiser_profile_page');
    }









}
