<?php

namespace ReclamationBundle\Controller;

use ReclamationBundle\Entity\Categorie;
use ReclamationBundle\Entity\Notifications;
use ReclamationBundle\Entity\reclamations;
use ReclamationBundle\Entity\user;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Reclamation/Default/index.html.twig');
    }
    public function reclamationFormAction()
    {
        return $this->render('@Reclamation/Default/categorieForm.html.twig');
    }
    public function mailAction($id)
    {

        $modele=$this->getDoctrine()->getRepository(reclamations::class)->find($id);
        $modele->setEtat(" traitée");
//$modele-> getEmail();

        $em=$this->getDoctrine()->getManager();

        $mailer=$this->container->get('mailer');

        $transport= \Swift_SmtpTransport::newInstance('smtp.gmail.com',465,'ssl')
            ->setUsername('testmailfarah@gmail.com')
            ->setPassword('roottoor');
        $message=" reclamation de l'administateur .vous avez ete banni par l'utilisateur "
            .$modele->getUser()->getUsername()." car vous n'avez pas respecté les Conditions Générales du plateforme";
        $Subject="reclm";

        $mailer=\Swift_Mailer::newInstance($transport);
        $message=\Swift_Message::newInstance('Test')

            ->setSubject($Subject)
            ->setFrom('testmailfarah@gmail.com')
            ->setTo("farah.marsaoui@esprit.tn")
            ->setBody( $message);
        $this->get('mailer')->send($message);


        $em->flush();
        return $this->redirectToRoute('reclamationR');
    }
    public function adminAction()
    {    $mod=$this->getDoctrine()->getManager()->getRepository(Notifications::class)->findAll();
        return $this->render('@Reclamation/Default/admin.html.twig', array(
        'notif'=>$mod,
    ));
    }
    public function adminRAction()
    {    $modele=$this->getDoctrine()->getRepository(reclamations::class)->findAll();
        $model=$this->getDoctrine()->getRepository(reclamations::class)->findAll();
        $mod=$this->getDoctrine()->getManager()->getRepository(Notifications::class)->findAll();
        $categ=$this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Reclamation/Default/adminR.html.twig', array(
            'modele'=>$modele,'notif'=>$mod,'rec'=>$model,'categ'=>$categ
        ));
    }
    public function adminCAction(){

    }
}
