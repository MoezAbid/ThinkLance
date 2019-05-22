<?php

namespace ProjetBundle\Controller;

use Doctrine\DBAL\Schema\View;
use ProjetBundle\Entity\Categorie;
use ProjetBundle\Entity\Demande;
use ProjetBundle\Entity\Notification;
use ProjetBundle\Entity\Projet;
use AppBundle\Entity\User;
use ProjetBundle\Form\ProjetType;
use ProjetBundle\Form\DemandeType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;



class DefaultController extends Controller
{

    public function getAllJsonAction()
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('ProjetBundle:Projet')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);

    }



    public function indexAction()
    {

        return $this->render('@Projet/Default/index.html.twig');
    }
    public function myProjectAction($id){

        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('ProjetBundle:Projet')
            ->findByIdUser($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }
    public function readAction()
    {
        $iduser = $this->getUser()->getId();
        $notif=$this->getDoctrine()->getManager()->getRepository(Notification::class)->find($iduser);
        $projets = $this->getDoctrine()->getRepository(Projet::class)->findByIdUser($iduser);
        return $this->render('@Projet/Default/readprojets.html.twig', array("projets" => $projets, "notif" => $notif
        ));
    }

    public function ajoutAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $task = new Projet();
        $task->setTitreProjet($request->get('titreProjet'));
        $task->setDescription($request->get('description'));
        $task->setNbreJours($request->get('nbreJours'));
        $task->setPrix($request->get('prix'));
        $task->setNomFichiers($request->get('nomFichiers'));
        $task->setFile("http://localhost/mobileimg/file.pdf");
        $c=$this->getDoctrine()->getManager()->getRepository(Categorie::class);
        //$v='validation';
        //$objectCategorie = $em->getRepository(Categorie::class)->findCategorie($v);

        $c=$this->getDoctrine()->getManager()->getRepository(Categorie::class)->find(1);

        $objectemp = $em->getRepository(\ProjetBundle\Entity\User::class)->find($request->get('employeur'));
        $task->setEmployeur($objectemp);
        /* $task->setFreelancer($request->get('freelancer'));*/
        $em->persist($task);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);}

    public function afficheAction($id)
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('ProjetBundle:Projet')
            ->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);
    }
    public function readdAction()
    {
        $projet = $this->getDoctrine()->getRepository(Projet::class)->findAll();
        return $this->render('@Projet/Default/read.html.twig', array('projets' => $projet));
    }


    public function modifierProjetAction($id, Request $request)

    { //preparation de l'entity manager
        $em = $this->getDoctrine()->getManager();

        //preparation de l'objet
        $projet = $em->getRepository(Projet::class)->find($id);
        //preparation du formulaire
        $form = $this->createForm(ProjetType::class, $projet);
        //5/ recuperation du formulaire
        $form = $form->handleRequest($request);
        if ( $form->isValid()) {
            $em->flush();
            //redirection
            return $this->redirectToRoute('projets');
        }
        //4envoi du formulaire
        return $this->render('@Projet/Default/modifierProjet.html.twig', array(
            'form' => $form->createView()
        ));
    }


    public function tousProjetAction()
    {
        //recup données
        $projets = $this->getDoctrine()->getRepository(Projet::class)->findAll();
        return $this->render('@Projet/Default/interface_freelancer.html.twig', array("projets" => $projets

        ));
    }

    public function supprimerProjetAction($id)
    {

        $task = $this->getDoctrine()->getManager()->getRepository(Projet::class)->find($id);
        if ($task == null) {
            //return new View(null, Response::HTTP_NOT_FOUND);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);



    }
    public function index1Action(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $result = $em->createQuery("SELECT p FROM ProjetBundle:Projet p    
           JOIN p.categorie c WHERE c.titreCategorie LIKE :search")->setParameter('search', '%' . $request->query->getAlnum('filter') . '%')->getResult();
        return $this->render('@Projet/Default/read.html.twig', array(
            'projets' => $result,
        ));

    }

}
