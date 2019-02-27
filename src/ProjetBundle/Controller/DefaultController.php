<?php

namespace ProjetBundle\Controller;

use ProjetBundle\Entity\Demande;
use ProjetBundle\Entity\Notification;
use ProjetBundle\Entity\Projet;
use AppBundle\Entity\User;
use ProjetBundle\Form\ProjetType;
use ProjetBundle\Form\DemandeType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Projet/Default/index.html.twig');
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
        //creation diun objet vide
        $projet = new Projet();
        //creation d'un formulaire
        $form = $this->createForm(ProjetType::class, $projet);
        //recuperation des doonées
        $projet->setEmployeur($this->getUser());
        $form = $form->handleRequest($request);
        //validation du formulaire

        if ($form->isSubmitted() && $form->isValid()) {  //creation de entity manager
            $em = $this->getDoctrine()->getManager();
            $f = $projet->getFile();
            $fn = md5(uniqid()) . '.' . $f->guessExtension();
            //$f->move($this->getParameter('files_directory'),$fn);

            try {
                $f->move($this->getParameter('files_directory'), $fn);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $projet->setFile($fn);
            //persister les données danns orm
            $em->persist($projet);
            //sauvegarde des données dans bd
            $em->flush();
            return $this->redirectToRoute('projets');
        }
        //envoi du formulaire
        return $this->render('@Projet/Default/ajout.html.twig', array(
            "form" => $form->createView()
        ));
    }

    public function afficheAction($id)
    {
        $projet = $this->getDoctrine()->getRepository(Projet::class)->find($id);
        return $this->render('@Projet/Default/details.html.twig', array('projet' => $projet));
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
        $em = $this->getDoctrine()->getManager();
        $projet = $em->getRepository(Projet::class)->find($id);
        $em->remove($projet);
        $em->flush();
        return $this->redirectToRoute('projets');

    }
    public function index1Action(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $result = $em->createQuery("SELECT p FROM ProjetBundle:Projet p    
           JOIN p.categorie c WHERE c.titreCategorie LIKE :search")->setParameter('search', '%' . $request->query->getAlnum('filter') . '%')->getResult();
        return $this->render('@Projet/Default/interface_freelancer.html.twig', array(
            'projets' => $result,
        ));

    }

}
