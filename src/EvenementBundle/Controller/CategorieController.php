<?php

namespace EvenementBundle\Controller;

use EvenementBundle\Entity\Categorie;
use EvenementBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CategorieController extends Controller
{
    public function readbcAction()
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('EvenementBundle:Categorie')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }
    public function createbAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = new Categorie();
        $categorie->setNom($request->get('Nom'));
        $categorie->setDescription($request->get('Description'));
        $em->persist($categorie);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($categorie);
        return new JsonResponse($formatted);
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
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('EvenementBundle:Categorie')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }
}
