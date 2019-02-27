<?php

namespace ProfileBundle\Controller;


use PaiementBundle\Entity\Paiement;
use ProfileBundle\Entity\Categorie;
use ProfileBundle\Entity\Commentaire;
use ProfileBundle\Entity\Competence;
use ProfileBundle\Entity\ExperiencePro;
use ProfileBundle\Entity\Formation;
use ProfileBundle\Entity\Langue;
use ProfileBundle\Entity\Notification;
use ProfileBundle\Entity\PortFolio;
use AppBundle\Entity\User;
use ProfileBundle\Entity\Rate;
use ProfileBundle\Form\CommentaireType;
use ProfileBundle\Form\CompetenceType;
use ProfileBundle\Form\EmployeurformType;
use ProfileBundle\Form\ExperienceProType;
use ProfileBundle\Form\FormationType;
use ProfileBundle\Form\LangueType;
use ProfileBundle\Form\PortFolioType;
use ProfileBundle\Form\RateType;
use ProfileBundle\Form\UserType;
use PaiementBundle\Repository\PaiementRepository;
use SBC\NotificationsBundle\NotificationsBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    //Les méthodes du freelancer
    public function redirectAction()
    {
        return $this->render('@Profile/Default/redirect.html.twig');
    }

    public function createAction(Request $request)
    {
        //Formulaire pour l'ajout d'une photo
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form = $form->handleRequest($request);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $user->uploadProfilePicture();
            $em->persist($user);
            $user->setCheckprofile(true);
            $user->setDisponibilite(true);
            $em->flush();

            return $this->redirectToRoute('visualiser_profile_page');
        }

        //Pour ajouter une catégorie
        if ($request->get('souscat') != null) {
            $categorie = new Categorie();
            if($request->get('souscat')=="Réalisateur"||$request->get('souscat')=="Game Designer"||$request->get('souscat')=="Motion Designer"){
                $categorie->setNomCategorie("Motion Designers & Réalisateurs");
            }
            if($request->get('souscat')=="Community Manager"||$request->get('souscat')=="Pigiste"||$request->get('souscat')=="Traducteur"||$request->get('souscat')=="Rédacteur Web")
            {
                $categorie->setNomCategorie("Rédacteurs, Traducteurs,Community Managers");

            }
            if($request->get('souscat')=="Graphiste"||$request->get('souscat')=="Illustrateur"||$request->get('souscat')=="Photographe"||$request->get('souscat')=="UX Designer"||$request->get('souscat')=="Directeur artistique")
            {
                $categorie->setNomCategorie("Graphistes,Designers,Photographes");

            }
            if($request->get('souscat')=="Coach Agile"||$request->get('souscat')=="Product Manager"||$request->get('souscat')=="Chef de Projet"||$request->get('souscat')=="Scrum Master")
            {
                $categorie->setNomCategorie("Chefs de Projet & Coachs Agile");
            }
            if($request->get('souscat')=="Business Developer"||$request->get('souscat')=="Consultant en Communication"||$request->get('souscat')=="Consultant en  Stratégie")
            {
                $categorie->setNomCategorie("Consultants en Stratégie, Progiciel, Communication");
            }
            if($request->get('souscat')=="Développeur Back-End"||$request->get('souscat')=="Data Scientist"||$request->get('souscat')=="Développeur Front-End / Intégrateur Web"||$request->get('souscat')=="Développeur Mobile"||$request->get('souscat')=="Webmaster"){

                $categorie->setNomCategorie("Développeurs,Intégrateurs & Data scientists");
            }
            $categorie->setSousCategorie($request->get('souscat'));
            $categorie->setUser($user);
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute('visualiser_profile_page');

        }
        //Insert into the entity Formation
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);
        $formFormation = $formFormation->handleRequest($request);
        if ($formFormation->isValid()) {
            $formation->setUser($user);
            $em->persist($formation);
            $em->flush();
            return $this->redirectToRoute('visualiser_profile_page');
        }

        //Insert into the entity ExperiencePro
        $experience = new ExperiencePro();
        $formexpe = $this->createForm(ExperienceProType::class, $experience);
        $formexpe = $formexpe->handleRequest($request);
        if ($formexpe->isValid()) {
            $experience->setUser($user);
            $em->persist($experience);
            $em->flush();
            return $this->redirectToRoute('visualiser_profile_page');
        }
        //Insert into portFolio
        $portfolio = new PortFolio();
        $form2 = $this->createForm(PortFolioType::class, $portfolio);
        $form2 = $form2->handleRequest($request);
        if ($form2->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $portfolio->uploadProfilePicture();
            $portfolio->setUser($user);
            $em->persist($portfolio);
            $em->flush();
            return $this->redirectToRoute('visualiser_profile_page');
        }


        //Insert Competence
        $comptence = new Competence();
        $form3 = $this->createForm(CompetenceType::class, $comptence);
        $form3 = $form3->handleRequest($request);
        if ($form3->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $comptence->setUser($this->getUser());
            $em->persist($comptence);
            $em->flush();
            return $this->redirectToRoute('visualiser_profile_page');
        }

        //Insert into Langue
        if ($request->get('langue') != null) {
            $langue = new Langue();
            $langue->setLangueTitre($request->get('langue'));
            $langue->setUser($user);
            $em->persist($langue);
            $em->flush();
            return $this->redirectToRoute('visualiser_profile_page');
        }
        $pays = $form->get("pays")->getData();
        return $this->render('@Profile/Default/create.html.twig', array(
            'form' => $form->createView(),
            'user' => $this->getUser(),
            'form2' => $form2->createView(),
            'form3' => $form3->createView(),
            'formformation' => $formFormation->createView(),
            'portfolio' => $portfolio,
            'paysform' => $pays,
            'formexp' => $formexpe->createView(),

        ));
    }







    public function DescriptionProfileAction($id, Request $request)
    {
        //Ajouter un commentaire
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form = $form->handleRequest($request);
        $commentaire->setUser($this->getUser());
        $date = new \DateTime('now');
        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $commentaire->setDateAjout($date);
            $em->persist($commentaire);
            $em->flush();
            $notification = new Notification();
            $user = $em->getRepository(User::class)->find($id);
            $notification->setTitle("new Comment")->setDescription($commentaire->getContenu())->setRoute('profile_homepage')
                ->setParameters(array('id' => $commentaire->getId()))->setIdEmployer($this->getUser())->setIdFreelancer($user);
            $em->persist($notification);
            $em->flush();
            $pusher = $this->get('mrad.pusher.notificaitons');
            $pusher->trigger($notification);
        }
        //Ajouter un rating
        $rate = new Rate();
        $form2 = $this->createForm(RateType::class, $rate);
        $form2 = $form2->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $user1 = $em->getRepository(User::class)->find($id);
        if ($form2->isValid()) {

            $rate->setIdEmployeur($this->getUser())->setIdFrellancer($user1);
            $em->persist($rate);
            $em->flush();
            return $this->redirect($request->getUri());
        }
        //Get the rate
        $notefinale = null;
        $rate = $this->getDoctrine()->getManager()->getRepository(Rate::class)->getRate($this->getUser()->getId(), $id);
        foreach ($rate as $ra) {
            $notefinale = ($ra->getRatingQualite() * 4 + $ra->getRatingCommunication() * 3 + $ra->getRatingRespectDelais() * 4) / 11;
        }
        $noteuser = $user1->getNote() + $notefinale / 5;
        $user1->setNote($noteuser);
        $user1->setNbrPoints($noteuser * 10);
        $em->flush();
        $note = $user1->getNbrPoints();
        $paiement = $this->getDoctrine()->getManager()->getRepository(Paiement::class)->findPaiement($this->getUser()->getId(), $id);
        $commentaires = $this->getDoctrine()->getRepository(Commentaire::class)->findAll();
        $em = $this->getDoctrine()->getManager();
        $user1 = $em->getRepository(User::class)->find($id);
        $formations = $this->getDoctrine()->getRepository(Formation::class)->findFormations($user1->getId());
        $experiencesPro = $this->getDoctrine()->getRepository(ExperiencePro::class)->findExperiences($user1->getId());
        $langues = $this->getDoctrine()->getRepository(Langue::class)->findLangues($user1->getId());
        $portfolios = $this->getDoctrine()->getRepository(PortFolio::class)->findportfolio($user1->getId());
        $competences = $this->getDoctrine()->getRepository(Competence::class)->findCompetences($user1->getId());


        return $this->render('@Profile/Default/descri_profile.html.twig', array(
            'user' => $user1,
            'formations' => $formations,
            'experiences' => $experiencesPro,
            'langues' => $langues,
            'portfolios' => $portfolios,
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'commentaires' => $commentaires,
            'paiement' => $paiement,
            'notefinale' => $notefinale,
            'competences' => $competences,
            'note' => $note,
            'date' => $date

        ));

    }

//Modifier Langue
    public function modifierlangueAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $formation = $em->getRepository(Langue::class)->find($id);

        $form = $this->createForm(LangueType::class, $formation);

        $form = $form->handleRequest($request);
        if ($form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('visualiser_profile_page');
        }
        return $this->render('@Profile/Default/modifier_langue.html.twig', array(
            'form' => $form->createView()
        ));
    }





//visualiser les informations de mon profil
    public function visualiserProfileAction()
    {

        $formations = $this->getDoctrine()->getRepository(Formation::class)->findFormations($this->getUser()->getId());
        $experiencesPro = $this->getDoctrine()->getRepository(ExperiencePro::class)->findExperiences($this->getUser()->getId());
        $langues = $this->getDoctrine()->getRepository(Langue::class)->findLangues($this->getUser()->getId());
        $portfolios = $this->getDoctrine()->getRepository(PortFolio::class)->findportfolio($this->getUser()->getId());
        $competences = $this->getDoctrine()->getRepository(Competence::class)->findCompetences($this->getUser()->getId());
        return $this->render('@Profile/Default/view_profile.html.twig', array(
            'formations' => $formations,
            'experiences' => $experiencesPro,
            'langues' => $langues,
            'portfolios' => $portfolios,
            'competences' => $competences,
        ));


    }

    //Lister les freelancers
    public function listerFreelancersAction()
    {
        $freelancers = $this->getDoctrine()->getRepository(User::class)->findAll();
        foreach ($freelancers as $free) {

            $competences = $this->getDoctrine()->getRepository(Competence::class)->findCompetences($free->getId());
        }

        return $this->render('@Profile/ViewEmployeur/listfreelancers.html.twig', array(
            'freelancers' => $freelancers,
            'competences' => $competences
        ));
    }

//Afficher les notifications
    public function displayNotificationAction()
    {

        $notifications = $this->getDoctrine()->getManager()->getRepository(Notification::class)->findAll();
        return $this->render('@Profile/Default/notification.html.twig', array(
            'notifications' => $notifications,

        ));
    }

    //Freelancer disponible
    public function disponibleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $user->setDisponibilite(true);
        $em->flush();
        return $this->redirectToRoute('visualiser_profile_page');
    }

    public function indisponibleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $user->setDisponibilite(false);
        $em->flush();
        return $this->redirectToRoute('visualiser_profile_page');
    }

    public function createEmployeurAction(Request $request)
    {

        //Formulaire pour l'ajout d'une photo d'un employeur
        $user = $this->getUser();
        $form = $this->createForm(EmployeurformType::class, $user);
        $form = $form->handleRequest($request);
        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $user->uploadProfilePicture();
            $em->persist($user);
            $user->setCheckprofile(true);
            $user->setDisponibilite(true);
            $em->flush();
            return $this->redirectToRoute('employeur_view_profile');
        }

        return $this->render('@Profile/ViewEmployeur/createEmployeur.html.twig', array(
            'form' => $form->createView(),
            'user' => $this->getUser(),

        ));


    }

    public function viewProfileEmployeurAction()
    {


        return $this->render('@Profile/ViewEmployeur/profileEmployeur.html.twig', array());


    }

    public function listEmployeurAction()
    {

       $employeurs=$this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('@Profile/ViewEmployeur/listeEmployeur.html.twig', array(

            'employeurs'=>$employeurs

        ));


    }

    public function descriptionEmployeurAction($id)
    {
        $em = $this->getDoctrine();
        $user = $em->getRepository(User::class)->find($id);
        return $this->render('@Profile/ViewEmployeur/description_employeur.html.twig', array(
            'user'=>$user

        ));

    }

    //Recherche freelancers By Pays
    public function searchFreelancersByPaysAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine();
            $pays = $request->get('pays');
            $freelancers = $em->getRepository(User::class)->findFreelancersByPays($pays);
            $response = new Response(json_encode($freelancers));
            $response->headers->set('Content-Type', 'application/json');
            return $response;

        }
        return $this->render('@Profile/ViewEmployeur/listfreelancers.html.twig', array());

    }

    public function searchFreelancersByPrixAction(Request $request)
    {

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine();
            $prixmin = $request->get('prixmin');
            $primax = $request->get('prixmax');
            $freelancersT = $em->getRepository(User::class)->findFreelancersByTarif($prixmin, $primax);
            $response = new Response(json_encode($freelancersT));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        return $this->render('@Profile/ViewEmployeur/listfreelancers.html.twig', array());


    }
    public function searchFreelancersByDisponibiliteAction(Request $request){

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine();
            $disponibilite = $request->get('disponibilite');

            $freelancers = $em->getRepository(User::class)->findFreelancersByDidponibilite($disponibilite);
            $response = new Response(json_encode($freelancers));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        return $this->render('@Profile/ViewEmployeur/listfreelancers.html.twig', array());



    }
    public function searchFreelancersByCategorieAction(Request $request){

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine();
            $cat = $request->get('myCheckboxes[]');
            $allfreelancers=$em->getRepository(User::class)->findAll();
            foreach ($allfreelancers as $free){
          $freelancers = $em->getRepository(Categorie::class)->findCategories($cat,$free->getId());
            }
            $response = new Response(json_encode($freelancers));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        return $this->render('@Profile/ViewEmployeur/listfreelancers.html.twig', array());



    }



}
