<?php

namespace EvenementBundle\Controller;

use EvenementBundle\Entity\Evenement;
use EvenementBundle\Entity\Categorie;
use EvenementBundle\Entity\Participer;
use EvenementBundle\Entity\Repository;


use EvenementBundle\Form\EvenementType;
use EvenementBundle\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
class EvenementController extends Controller
{
    public function readAction()
    {
        $evenement = $this->getDoctrine()->getManager()
            ->getRepository('EvenementBundle:Evenement')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenement);
        return new JsonResponse($formatted);
    }
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $evenement = new Evenement();
        $evenement->setNom($request->get('nom'));
        $evenement->setDescription($request->get('description'));
        $evenement->setLieu($request->get('lieu'));
        $evenement->setDateDebut($request->get('date_debut'));
        $evenement->setDateFin($request->get('date_fin'));
        $evenement->setNbrPlace($request->get('nbr_place'));
        $evenement->setPrix($request->get('prix'));
        $evenement->setDate($request->get('date'));
        $evenement->setNomcategorie($request->get('nom_categorie'));
        $em->persist($evenement);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenement);
        return new JsonResponse($formatted);
    }
    public function updateAction($id ,Request $request)
    {
        //1-preparation de l'entity manager
        $em=$this->getDoctrine()->getManager();
        //2-preparation de notre objet
        $evenement=$em->getRepository(Evenement::class)->find($id);
        //3-preparation de notre formulaire
        $form=$this->createForm(EvenementType::class,$evenement);
        //5-Recuperation du formulaire
        $form=$form->handleRequest($request);
        //6-validation du formulaire
        if($form->isValid())
        {
            $evenement->uploadProfilePicture();
            //7-update dans la base de donnee
            $em->flush();
            //8-redirection
            return $this->redirectToRoute('read');
        }
        //4-envoi du formulaire a notre utilisateur

        return $this->render('@Evenement/Evenement/update.html.twig', array(
            'form'=>$form->createView()
        ));
    }
    public function deleteAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $Evenement=$em->getRepository(Evenement::class)->find($id);
        $em->remove($Evenement);
        $em->flush();

        return $this->redirectToRoute('read') ;
    }
    public function read2Action($id)
    {
        //1-Recuperation des donnees
        $Evenements=$this->getDoctrine()->getRepository(Evenement::class)->find($id);
        $Participer=$this->getDoctrine()->getRepository(Participer::class)->findAll();

        return $this->render('@Evenement/Evenement/read2.html.twig', array(
            'evenements'=>$Evenements , 'participer'=>$Participer
        ));
    }
    public function readbAction()
    {
        //1-Recuperation des donnees
        $Categories=$this->getDoctrine()->getRepository(Categorie::class)->findAll();

        $Evenements=$this->getDoctrine()->getRepository(Evenement::class)->findAll();
        return $this->render('@Evenement/Evenement/readb.html.twig', array(
            'evenements'=>$Evenements ,
                        'categories'=>$Categories

        ));


    }
    public function updatenbrAction($id)
    {
        //1-preparation de l'entity manager
        $em=$this->getDoctrine()->getManager();
        //2-preparation de notre objet
        $evenement=$em->getRepository(Evenement::class)->find($id);
        //3-preparation de notre formulaire
        $evenement->setNbrplace( $evenement->getNbrplace()-1);
        //5-Recuperation du formulaire
        $em->flush();

        return $this->redirectToRoute('read' );


    }
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $Participer=$this->getDoctrine()->getRepository(Participer::class)->findAll();

        $result = $em->createQuery("SELECT e FROM EvenementBundle:Evenement e
        JOIN e.idCategorie c WHERE c.nom LIKE :search")->setParameter('search', '%' . $request->query->getAlnum('filter') . '%')->getResult();
        return $this->render('@Evenement/Evenement/read.html.twig', array(
            'evenements' => $result,'participer'=>$Participer
        ));
    }
}
