<?php

namespace ForumBundle\Controller;

use ForumBundle\Entity\question;
use ForumBundle\Form\questionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class QuestionController extends Controller
{
    public function createAction(Request $request)
    {
        //1-preparation objet vide
        $question=new question();
        //2-creation formulaire
        $form=$this->createForm(questionType::class,$question);
        //4-recupartion de donne
        $form=$form->handleRequest($request);
        //5-valiation de formulaire
        if($form->isValid())
        {
            //6-creation entity mannager
            $em=$this->getDoctrine()->getManager();
            //7-sauvgarder les donnes dans orm
            $em->persist($question);
            //8-sauvgarde les donne dans la base de donne
            $em->flush();
            //9-redirection
            return $this->redirectToRoute('read');
        }
        //envoi de notre formaulire au utilisateur
        return $this->render('@Forum/question/create.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    public function readAction()
    {
        $questions=$this->getDoctrine()->getRepository(question::class)->findall();

        return $this->render('@Forum/question/read.html.twig', array(
            'questions'=>$questions
        ));
    }

    public function updateAction($id,Request $request)
    {
        //0-preparation entity manager
        $em=$this->getDoctrine()->getManager();
        //1-preparation de notre objet
        $question=$em->getRepository(question::class)->find($id);
        //3-preparation de notre ofrm
        $form=$this->createForm(questionType::class,$question);
        //5-recuperation de donne de formulaire de base de donne
        $form=$form->handleRequest($request);
        //6-validation formulaire
        if ($form->isValid())
        {
            //7-update dans base de donnee
            $em->flush();
            //8-redirection
            return $this->redirectToRoute('read');
        }
        //4-envoi form a utilisateur
        return $this->render('@Forum/question/update.html.twig', array(
            'form1'=>$form->createView()
        ));
    }

    public function deleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $question=$em->getRepository(question::class)->find($id);
        $em->remove($question);
        $em->flush();
        return $this->redirectToRoute('read');

    }

    public function rechercheAction (Request $request)
    {

        $contenu = $request->get('contenu');
        if (isset ($contenu))
        {
            $questions= $this->getDoctrine()
                ->getRepository(question::class)
                ->myfindall($contenu);



            if (empty($questions))
            {

                return $this->render('@Forum/question/recherche.html.twig', array(


                ));
            }

            return $this->render('@Forum/question/read2.html.twig', array(
                'questions'=>$questions
            ));

        }

        //1-preparation form in the view
        //1-envoi d form à l'utilisateur
        return $this->render('@Forum/question/recherche.html.twig', array(

        ));
    }


}
