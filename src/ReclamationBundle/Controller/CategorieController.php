<?php

namespace ReclamationBundle\Controller;

use ReclamationBundle\Entity\Categorie;
use ReclamationBundle\Entity\Notifications;
use ReclamationBundle\Entity\reclamations;
use ReclamationBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends Controller
{
    public function afficheCAction()
    {
        $modele=$this->getDoctrine()->getRepository(Categorie::class)->FindAll();


        return $this->render('@Reclamation/Default/afficherCategorie.html.twig', array(
            'cat'=>$modele,
        ));

    }
    public function afficheCaAction()
    {
        $modele=$this->getDoctrine()->getRepository(Categorie::class)->FindAll();
        $mod=$this->getDoctrine()->getManager()->getRepository(Notifications::class)->findAll();
        $model=$this->getDoctrine()->getRepository(reclamations::class)->findAll();

        return $this->render('@Reclamation/Default/adminC.html.twig', array(
            'cat'=>$modele,'notif'=>$mod,'rec'=>$model
        ));

    }

    public function categorieSuppAction($id)
    {

        //1-prepartion de l'entity manager
        $em=$this->getDoctrine()->getManager();
        //2-prepartion de notre objet
        $modele=$em->getRepository(Categorie::class)->find($id);
        $em->remove($modele);
        $em->flush();
        return $this->redirectToRoute('reclamationC');

    }
    public function ajoutCAction(Request $req)
    {
        $modele=new Categorie();
        //1-prepartion d un objet vide

        //2-creation dun formulaire
        $form=$this->createform(CategorieType::class,$modele);
        //4-recuperation des donnes
        $form=$form->handleRequest($req);
        //5-validation du formulaire
        if($form->isValid())
        {// 6-creation de l'entity manager
            $em=$this->getDoctrine()->getManager();

            //persistance des donnees dans l orm(doctrine)
            $em->persist($modele);
            //sauvgarde des donnees dans la base des donnes
            $em->flush();
            return $this->redirectToRoute('categorie_affiche');

        }
        //envoie formulaire
        return $this->render('@Reclamation/Default/categorieForm.html.twig', array(
            'f'=>$form->createview()
        ));
    }

    public function ajoutCaAction(Request $req)
    {
        $modele=new Categorie();
        //1-prepartion d un objet vide

        //2-creation dun formulaire
        $form=$this->createform(CategorieType::class,$modele);
        //4-recuperation des donnes
        $form=$form->handleRequest($req);
        //5-validation du formulaire
        if($form->isValid())
        {// 6-creation de l'entity manager
            $em=$this->getDoctrine()->getManager();

            //persistance des donnees dans l orm(doctrine)
            $em->persist($modele);
            //sauvgarde des donnees dans la base des donnes
            $em->flush();
            return $this->redirectToRoute('reclamationC');

        }
        $mod=$this->getDoctrine()->getManager()->getRepository(Notifications::class)->findAll();
        $model=$this->getDoctrine()->getRepository(reclamations::class)->findAll();

        //envoie formulaire
        return $this->render('@Reclamation/Default/categorieFormm.html.twig', array(
            'f'=>$form->createview(),'notif'=>$mod,'rec'=>$model
        ));
    }
    public function modifierCAction($id,Request $request)
    {
        //1-prepartion de l'entity manager
        $em=$this-> getDoctrine()->getManager();
        //2-prepartion de notre objet
        $modele=$em->getRepository(Categorie::class)->find($id);
        //3-preparation du formulaire
        $form=$this->createform(CategorieType::class,$modele);
        //5-recuperation du form
        $form=$form->handleRequest($request);
        //6-validation du formulaire et ajout de donnees
        if($form->isValid())
        {
            //7-update des donnees dans la base des donnes
            $em->flush();
            return $this->redirectToRoute('categorie_affiche');
        }
        //4-envoie formulaire s l'untitlisateur
        return $this->render('@Reclamation/Default/modifierC.html.twig', array(
            'f'=>$form->createview()
        ));
    }
    public function modifierCaAction($id,Request $request)
    {
        //1-prepartion de l'entity manager
        $em=$this-> getDoctrine()->getManager();
        //2-prepartion de notre objet
        $modele=$em->getRepository(Categorie::class)->find($id);
        //3-preparation du formulaire
        $form=$this->createform(CategorieType::class,$modele);
        //5-recuperation du form
        $form=$form->handleRequest($request);
        //6-validation du formulaire et ajout de donnees
        if($form->isValid())
        {
            //7-update des donnees dans la base des donnes
            $em->flush();
            return $this->redirectToRoute('reclamationC');
        }
        $mod=$this->getDoctrine()->getManager()->getRepository(Notifications::class)->findAll();
        $model=$this->getDoctrine()->getRepository(reclamations::class)->findAll();
        //4-envoie formulaire s l'untitlisateur
        return $this->render('@Reclamation/Default/adminModif.html.twig', array(
            'f'=>$form->createview(),'notif'=>$mod,'rec'=>$model
        ));
    }
}
