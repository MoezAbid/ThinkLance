<?php

namespace ProjetBundle\Controller;

use ProjetBundle\Entity\Projet;
use ProjetBundle\Entity\Tache;
use ProjetBundle\Form\TacheFType;
use ProjetBundle\Form\TacheType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ProjetBundle\Repository\TacheRepository;

class TâcheController extends Controller
{
    public function ajoutTâcheAction(Request $request)
    {

        //creation diun objet vide
        $tache = new Tache();
        //creation d'un formulaire
        $form = $this->createForm(TacheType::class, $tache);
        //recuperation des doonées
        $form = $form->handleRequest($request);
        //validation du formulaire

        if ($form->isValid()) {  //creation de entity manager
            $em = $this->getDoctrine()->getManager();
            //persister les données danns orm
            $em->persist($tache);
            //sauvegarde des données dans bd
            $em->flush();
            // return $this->redirectToRoute('');
            return $this->redirect($this->generateUrl( 'afficheTache',array('id' => $tache->getProjet()->getIdProjet())));
        }
        //envoi du formulaire
        return $this->render('@Projet/Default/ajoutTâche.html.twig', array(
            "form" => $form->createView()
        ));
    }

    public function afficheTâcheAction($id)
    {
        //recup données
        $tâche = $this->getDoctrine()->getRepository(Tache::class)->findByIdProjet($id);
       // $projet=$this->getDoctrine()->getRepository(Projet::class)->find($id);
        return $this->render('@Projet/Default/afficheTâche.html.twig', array("tache" => $tâche


        ));
    }

    public function afficheTâcheFAction($id)
    {
        //recup données
        $tâche = $this->getDoctrine()->getRepository(Tache::class)->findByIdProjet($id);

        return $this->render('@Projet/Default/afficheTâcheF.html.twig', array("tache" => $tâche

        ));
    }
    public function supprimerTâcheAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $tâche = $em->getRepository(Tache::class)->find($id);
        $em->remove($tâche);
        $em->flush();
        $projet=$tâche->getProjet();


        return $this->redirect($this->generateUrl( 'afficheTache',array('id' => $tâche->getProjet()->getIdProjet())));

    }


    public function modifierTâcheAction($id, Request $request)

    { //preparation de l'entity manager
        $em = $this->getDoctrine()->getManager();

        //preparation de l'objet
        $tâche = $em->getRepository(Tache::class)->find($id);
        //preparation du formulaire
        $form = $this->createForm(TacheType::class, $tâche);
        //5/ recuperation du formulaire
        $form = $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            //redirection
            return $this->redirect($this->generateUrl( 'afficheTache',array('id' => $tâche->getProjet()->getIdProjet())));
        }
        //4envoi du formulaire
        return $this->render('@Projet/Default/modifierTâche.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function modifierEtatTâcheAction($id, Request $request)

    { //preparation de l'entity manager
        $em = $this->getDoctrine()->getManager();

        //preparation de l'objet
        $tache = $em->getRepository(Tache::class)->find($id);
        $idp=$tache->getProjet();
        //preparation du formulaire
        $etat=$tache->getEtatTache();
        if($etat == null || $etat =="to do")
        {
            $etat="done";
        }
        else
        {
            $etat="to do";
        }
        $tache->setEtatTache($etat);
        $em->persist($tache);
            $em->flush();
            //redirection

        //$tache = $this->getDoctrine()->getRepository(Tache::class)->findByIdProjet($id);
        $tâche = $this->getDoctrine()->getRepository(Tache::class)->findByIdProjet($id);
        //4envoi du formulaire
        return $this->redirectToRoute('tousProjets');
        /*return $this->render('@Projet\Default\afficheTâcheF.html.twig', array(
            "tache"=>$tache, "id"=>$idp
        ));*/
    }



//$response->get('etat');
}
