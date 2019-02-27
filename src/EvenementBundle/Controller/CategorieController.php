<?php

namespace EvenementBundle\Controller;

use EvenementBundle\Entity\Categorie;
use EvenementBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends Controller
{
    public function readbcAction()
    {
        //1-Recuperation des donnees
        $Categories=$this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Evenement/Categorie/readbc.html.twig', array(
            'categories'=>$Categories
        ));
    }
    public function createbAction(Request $request)
    {
        //preparation d'un objet vide
        $categorie=new Categorie();
        //creation form
        $form=$this->createForm(CategorieType::class,$categorie);
        //recuperation des donnees
        $form=$form->handleRequest($request);
        if($form->isValid())
        {
            //insertion dans la BD
            $em=$this->getDoctrine()->getManager();

            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('readb');
        }
        //envoi de formulaire
        return $this->render('@Evenement/Categorie/createb.html.twig', array(
            'form'=>$form->createView()));
    }
    public function updatebcAction($id ,Request $request)
    {
        //1-preparation de l'entity manager
        $em=$this->getDoctrine()->getManager();
        //2-preparation de notre objet
        $categorie=$em->getRepository(Categorie::class)->find($id);
        //3-preparation de notre formulaire
        $form=$this->createForm(CategorieType::class,$categorie);
        //5-Recuperation du formulaire
        $form=$form->handleRequest($request);
        //6-validation du formulaire
        if($form->isValid())
        {

            //7-update dans la base de donnee
            $em->flush();
            //8-redirection
            return $this->redirectToRoute('readbc');
        }
        //4-envoi du formulaire a notre utilisateur

        return $this->render('@Evenement/Categorie/updatebc.html.twig', array(
            'form'=>$form->createView()
        ));
    }
    public function deletebcAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $Categorie=$em->getRepository(Categorie::class)->find($id);
        $em->remove($Categorie);
        $em->flush();

        return $this->redirectToRoute('readbc') ;
    }
    public function readcAction()
    {
        //1-Recuperation des donnees
        $Categories=$this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Evenement/Categorie/readc.html.twig', array(
            'categories'=>$Categories
        ));
    }
}
