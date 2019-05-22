<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\reponse;
use ForumBundle\Form\reponseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReponseController extends Controller
{
    public function createAction(Request $request){
        //1-preparation objet
        $reponse=new reponse();
        //2- creation du formulaire
        $form=$this->createForm(reponseType::class,$reponse);
        //4-recuperation des donnees
        $form=$form->handleRequest($request);
        if($form->isValid()){
            //5-insertion dans la bd
            $em=$this->getDoctrine()->getManager();
            //7-sauvgarder les donnes dans orm
            $em->persist($reponse);
            //8-sauvgarde les donne dans la base de donne
            $em->flush();
            return $this->redirectToRoute('read_reponse');
        }
        //3-envoi du formulaire
        //envoi de notre formaulire au utilisateur
        return $this->render('@Forum/reponse/create.html.twig', array(
            'f'=>$form->createView()
        ));
    }

    public function readAction(){
        //1-recuparation des donee
        $reponse=$this->getDoctrine()->getRepository(reponse::class)->findAll();
        return $this->render('@Forum/reponse/read.html.twig', array(
            'reponses'=>$reponse ));
    }

    public function updateAction($id,Request $request)
    {
        //0-preparation entity manager
        $em=$this->getDoctrine()->getManager();
        //1-preparation de notre objet
        $reponse=$em->getRepository(reponse::class)->find($id);
        //3-preparation de notre ofrm
        $form=$this->createForm(reponseType::class,$reponse);
        //5-recuperation de donne de formulaire de base de donne
        $form=$form->handleRequest($request);
        //6-validation formulaire
        if ($form->isValid())
        {
            //7-update dans base de donnee
            $em->flush();
            //8-redirection
            return $this->redirectToRoute('read_reponse');
        }
        //4-envoi form a utilisateur
        return $this->render('@Forum/reponse/update.html.twig', array(
            'f'=>$form->createView()
        ));
    }

    public function deleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $reponse=$em->getRepository(reponse::class)->find($id);
        $em->remove($reponse);
        $em->flush();
        return $this->redirectToRoute('read_reponse');

    }






}
