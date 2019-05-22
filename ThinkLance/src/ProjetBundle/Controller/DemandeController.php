<?php

namespace ProjetBundle\Controller;

use ProjetBundle\Entity\Tache;
use ProjetBundle\ProjetBundle;
use SBC\NotificationsBundle\NotificationsBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ProjetBundle\Entity\Demande;
use ProjetBundle\Entity\Notification;
use ProjetBundle\Entity\Projet;
use AppBundle\Entity\User;
use ProjetBundle\Form\ProjetType;
use ProjetBundle\Form\DemandeType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
class DemandeController extends Controller
{
    public function demanderProjetAction($id)
    {  //creation diun objet vide
        $demande = new Demande();
        //creation d'un formulaire
        //recuperation des doonées
        $em = $this->getDoctrine()->getManager();
        $projet = $em->getRepository(Projet::class)->find($id);
        $projet2 = $em->getRepository(Projet::class)->findAll();
        $freelancer = $this->getUser();
        $demande->setProjet($projet);
        $demande->setFreelancer($freelancer);
        $demande->setEmployeur($projet->getEmployeur());
        $demande ->setDateDemande(new \DateTime());
        //persister les données danns orm

        $em->persist($demande);
        //sauvegarde des données dans bd
        $em->flush();
        $notif=$em->getRepository('ProjetBundle:Notification')->findAll();
        $em2 = $this->getDoctrine()->getManager();
        $notifnotseen=$em2->getRepository('ProjetBundle:Notification')->findNotSeen();
        $not=count($notifnotseen);
        return $this->render('@Projet/Default/notif.html.twig', array("demande" => $demande,"projets" => $projet2,"nb"=>$not,"notif"=>$notif));
//        return $this->render('@Projet/Default/read.html.twig', array("projets" => $projet2
//
//        ));
    }

    public function afficheDemandeAction()
    {
        $iduser = $this->getUser()->getId();
        $notif=$this->getDoctrine()->getManager()->getRepository(Notification::class)->find($iduser);
        $demandes = $this->getDoctrine()->getRepository(Demande::class)->findDemandeByIdUser($iduser);
        return $this->render('@Projet/Default/afficheDemande.html.twig', array("demandes" => $demandes
        ));
    }

    public function afficheDemandeFAction()
    {
        $iduser = $this->getUser()->getId();
        $demandes = $this->getDoctrine()->getRepository(Demande::class)->findDemandeByIdFreelancer($iduser);
        return $this->render('@Projet/Default/afficheDemandeF.html.twig', array("demandes" => $demandes
        ));
    }
    public function supprimerDemandeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $demande = $em->getRepository(Demande::class)->find($id);
        $demande->setEtatDemande("Refusée");
        $em->persist($demande);
        $em->flush();
        return $this->redirect($this->generateUrl( 'afficheDemande',array('id' => $demande->getProjet())));

    }
    public function accepterDemandeAction($id,Request $request)
    {
        $projet = new Projet();
        $em = $this->getDoctrine()->getManager();
        $demande = $em->getRepository(Demande::class)->find($id);
        $projet=$demande->getProjet();
        $demande->setEtatDemande("Acceptée");
        $projet->setFreelancer($demande->getFreelancer());

        $em->persist($projet);
        //sauvegarde des données dans bd
        $em->flush();

        return $this->redirect($this->generateUrl( 'afficheDemande',array('id' => $demande->getProjet())));

    }
/*demander projettttt */
    public function newDemandeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $task = new Demande();
       // $emp=new User();
        $task->setDateDemande(new \DateTime());
        $task->setEtatDemande("to do ");

        $c=$this->getDoctrine()->getManager()->getRepository(User::class)->find(1);
        $f=$this->getDoctrine()->getManager()->getRepository(User::class)->find(2);
        $task->setEmployeur($c);
        $task->setFreelancer($f);
       $task->setProjet($request->get('Projet'));
        $em->persist($task);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);
    }



}
