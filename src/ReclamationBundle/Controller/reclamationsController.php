<?php

namespace ReclamationBundle\Controller;

use ReclamationBundle\Entity\Categorie;
use ReclamationBundle\Entity\Notifications;
use ReclamationBundle\Entity\reclamations;
use ReclamationBundle\Entity\user;
use ReclamationBundle\Form\reclamationsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class reclamationsController extends Controller
{
    public function afficheAction()
    {

        $modele=$this->getDoctrine()->getRepository(reclamations::class)->findAll();
        $mod=$this->getDoctrine()->getManager()->getRepository(Notifications::class)->findAll();



        return $this->render('@Reclamation/Default/afficherReclamation.html.twig', array(
            'rec'=>$modele,'notif'=>$mod,


        ));


    }
    public function afficheaAction()
    {
        $modele=$this->getDoctrine()->getRepository(reclamations::class)->findAll();
        $mod=$this->getDoctrine()->getManager()->getRepository(Notifications::class)->findAll();

        return $this->render('@Reclamation/Default/afficherReclamationadmin.html.twig', array(
            'rec'=>$modele,'notif'=>$mod,


        ));


    }

    public function supprimerAction($ref)
    {

        //1-prepartion de l'entity manager
        $em=$this->getDoctrine()->getManager();
        //2-prepartion de notre objet
        $modele=$em->getRepository(reclamations::class)->find($ref);
        $em->remove($modele);
        $em->flush();
        return $this->redirectToRoute('reclamationR');

    }
    public function suppUserAction($i,$id)
    {

        //1-prepartion de l'entity manager
        $em=$this->getDoctrine()->getManager();
        //2-prepartion de notre objet

        $modele=$em->getRepository(\AppBundle\Entity\User::class)->find($i);

        $modele->setPassword("autreMotdePasse");
       $em->persist($modele);
        $em->flush();
        $em=$this->getDoctrine()->getManager();
        $modelee=$em->getRepository(reclamations::class)->find($id);
        $em->remove($modelee);
        $em->flush();

        return $this->redirectToRoute('reclamationR');

    }
    public function ajoutAction(Request $req)
    {
        $modele=new reclamations();
        //1-prepartion d un objet vide


        //2-creation dun formulaire
        $form=$this->createform(reclamationsType::class,$modele);
        //4-recuperation des donnes
        $form=$form->handleRequest($req);
        //5-validation du formulaire
        if($form->isValid())
        {// 6-creation de l'entity manager
            $em=$this->getDoctrine()->getManager();
           // $modele->setCategorie(null);
            $modele->setDatemc(new \DateTime());
            $modele->setEtat("non traitée");
            $modele->setAdmin($this->getUser());
            //->setUser(null);
            //persistance des donnees dans l orm(doctrine)
            $em->persist($modele);
           // $em->flush();

            //sauvgarde des donnees dans la base des donnes



            $mailer=$this->container->get('mailer');

            $transport= \Swift_SmtpTransport::newInstance('smtp.gmail.com',465,'ssl')
                ->setUsername('testmailfarah@gmail.com')
                ->setPassword('roottoor');
            $message=$form->get('message')->getData();
            $Subject=$form->get('sujet')->getData();

            $mailer=\Swift_Mailer::newInstance($transport);
            $message=\Swift_Message::newInstance('Test')

                ->setSubject($Subject)
                ->setFrom('testmailfarah@gmail.com')
                ->setTo("farah.marsaoui@esprit.tn")
                ->setBody( $message);
            $this->get('mailer')->send($message);



            $em->flush();



            return $this->redirectToRoute('reclamation_affiche',array('id'=>$modele->getId()));

        }

      /*  $notif=new Notifications();
        $notif->setTitle('nouvelle reclamation')
            ->setDescription($form->get('sujet')->getData())
            ->setRoute('reclamation_affiche')
            ->setParameters(array('id'=>$modele->getId()));

        $em=$this->getDoctrine()->getManager();
        $em->persist($notif);
        $em->flush();

        $pusher=$this->get('mrad.pusher.notifications');
        $pusher->trigger($notif);
        return $this->redirectToRoute('reclamation_affiche');*/

     //   $mod=$this->getDoctrine()->getManager()->getRepository(Notifications::class)->findAll();


        //envoie formulaire
        return $this->render('@Reclamation/Default/reclamationFormm.html.twig', array(
            'f'=>$form->createview(),
            //'notif'=>$mod,
        ));
    }
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $mod=$this->getDoctrine()->getManager()->getRepository(Notifications::class)->findAll();
        $modele=$this->getDoctrine()->getRepository(Categorie::class)->FindAll();


        $result = $em->createQuery("SELECT r FROM ReclamationBundle:reclamations r
       WHERE r.sujet LIKE :search")->setParameter('search', '%' . $request->query->getAlnum('filter') . '%')->getResult();
        return $this->render('@Reclamation/Default/adminR.html.twig', array(
            'rec' => $result,'notif'=>$mod,'categ'=>$modele,
        ));
    }


}
