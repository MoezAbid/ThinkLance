<?php

namespace PaiementBundle\Controller;

use AppBundle\Entity\User;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\ComboChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\LineChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Timeline;
use PaiementBundle\Entity\Paiement;
use ProjetBundle\Entity\Projet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use FOS\UserBundle\Controller\SecurityController as FOSController;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PaiementController extends FOSController
{

    private $tokenManager;

    public function __construct(CsrfTokenManagerInterface $tokenManager = null)
    {
        $this->tokenManager = $tokenManager;
    }

    public function listerPaiementsEmployeurApiAction(Request $request)
    {
        $idUserConnecte = $request->get('idUserConnecte');
        $paiements = $this->getDoctrine()->getRepository(Paiement::class)->mesPaiementsEmployeur($idUserConnecte);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($paiements);
        return new JsonResponse($formatted);
    }

    public function listerPaiementsFreelancerApiAction(Request $request)
    {
        $idUserConnecte = $request->get('idUserConnecte');
        $paiements = $this->getDoctrine()->getRepository(Paiement::class)->mesPaiementsFreelancer($idUserConnecte);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($paiements);
        return new JsonResponse($formatted);
    }

    public function listerPaiementsAction(Request $request)
    {
        //Vérifier le role de l'utilisareuer qui est connecté :
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');

        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return $this->listerPaiementsAdministrateurAction();
        } else if ($authChecker->isGranted('ROLE_FREELANCER')) {
            return $this->listerPaiementsFreelancerAction();
        } else if ($authChecker->isGranted('ROLE_EMPLOYEUR')) {
            return $this->listerPaiementsEmployeurAction();
        } else {
            /** @var $session Session */
            $session = $request->getSession();

            $authErrorKey = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;

            // get the error if any (works with forward and redirect -- see below)
            if ($request->attributes->has($authErrorKey)) {
                $error = $request->attributes->get($authErrorKey);
            } elseif (null !== $session && $session->has($authErrorKey)) {
                $error = $session->get($authErrorKey);
                $session->remove($authErrorKey);
            } else {
                $error = null;
            }

            if (!$error instanceof AuthenticationException) {
                $error = null; // The value does not come from the security component.
            }

            // last username entered by the user
            $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

            $csrfToken = $this->tokenManager
                ? $this->tokenManager->getToken('authenticate')->getValue()
                : null;

            return $this->renderLogin(array(
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_token' => $csrfToken,
            ));
        }
        //End
    }

    public function listerPaiementsFreelancerAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $paiements = $this->getDoctrine()->getRepository(Paiement::class)->mesPaiementsFreelancer($user->getId());
        return $this->render('@Paiement\Default\paiements_freelancer.html.twig', array(
            "paiements" => $paiements,
        ));
    }

    public function listerPaiementsEmployeurAction()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $paiements = $this->getDoctrine()->getRepository(Paiement::class)->mesPaiementsEmployeur($user->getId());

        return $this->render('@Paiement\Default\paiements_employeur.html.twig', array(
            "paiements" => $paiements,
        ));
    }

    public function listerPaiementsAdministrateurAction()
    {
        $paiements = $this->getDoctrine()->getRepository(Paiement::class)->findAll();

        return $this->render('@Paiement\Default\paiements_admin.html.twig', array(
            "paiements" => $paiements,
        ));
    }

    protected function renderLogin(array $data)
    {
        return $this->render('@FOSUser/Security/login.html.twig', $data);
    }

    public function rembourserPaiementApiAction($idPaiement, Request $request)
    {
        //Refund dans Stripe
        \Stripe\Stripe::setApiKey("sk_test_pnfuYUNCotWhyq7uOfqDmuGE");
        $re = \Stripe\Refund::create([
            "charge" => $idPaiement
        ]);
        $em = $this->getDoctrine()->getManager();
        $paiement = $em->getRepository(Paiement::class)->find($idPaiement);

        //Envoi du mail de remboursement
        //Vers l'employeur
        $messageEmployeur = \Swift_Message::newInstance()
            ->setSubject('Demande de remboursement ThinkLance.')
            ->setFrom('barely.managing.pidev@gmail.com')
            ->setTo($paiement->getEmployeur()->getEmail())
            //->setBody('Vous venez de recevoir ' + $paiement->getMontant() + ' € de la part de ' + $paiement->getFreelancer()->getUsername() + ' pour votre travail sur le projet ' + $paiement->getProjet()->getNom() + '. Vous pouvez encore consulter la liste de vos paiements, et les statistiques depuis la page des paiements.');
            ->setBody("Votre remboursement conrenant le paiement : " . $idPaiement . " a été effectuée.");
        $mailer = $this->get('mailer');
        $mailer->send($messageEmployeur);
        $spool = $mailer->getTransport()->getSpool();
        $transport = $this->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);

        //Vers le freelancer
        $messageFreelancer = \Swift_Message::newInstance()
            ->setSubject('Votre paiement a été retiré.')
            ->setFrom('barely.managing.pidev@gmail.com')
            ->setTo($paiement->getFreelancer()->getEmail())
            //->setBody('Vous venez de recevoir ' + $paiement->getMontant() + ' € de la part de ' + $paiement->getFreelancer()->getUsername() + ' pour votre travail sur le projet ' + $paiement->getProjet()->getNom() + '. Vous pouvez encore consulter la liste de vos paiements, et les statistiques depuis la page des paiements.');
            ->setBody("La paiemet : " . $idPaiement . ", conernant le projet : " . $paiement->getProjet()->getTitreProjet() . " a été annulé. Vous pouvez envoyer une réclamation pour plus d'informations.");
        $mailer = $this->get('mailer');
        $mailer->send($messageFreelancer);
        $spool = $mailer->getTransport()->getSpool();
        $transport = $this->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);


        //Suppression du paiement de la base
        $paiement = $em->getRepository(Paiement::class)->find($idPaiement);
        $em->remove($paiement);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($paiement);
        return new JsonResponse(json_encode('Rembourse'));
    }

    public function getNomUserFromIdApiAction($idUser, Request $request)
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($idUser);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);
    }


    public function getPaiemenetSpecifiqueApiAction($idPaiement, Request $request)
    {
        $paiement = $this->getDoctrine()->getManager()->getRepository(Paiement::class)->find($idPaiement);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($paiement);
        return new JsonResponse($formatted);
    }

    public function getNomProjetFromIdApiAction($idProjet, Request $request)
    {
        $projet = $this->getDoctrine()->getManager()->getRepository(Projet::class)->find($idProjet);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($projet);
        return new JsonResponse($formatted);
    }


    public function rembourserPaiementAction($id, Request $request)
    {
        //Refund dans Stripe
        \Stripe\Stripe::setApiKey("sk_test_pnfuYUNCotWhyq7uOfqDmuGE");
        $re = \Stripe\Refund::create([
            "charge" => $id
        ]);
        $em = $this->getDoctrine()->getManager();
        $paiement = $em->getRepository(Paiement::class)->find($id);

        //Envoi du mail de remboursement
        //Vers l'employeur
        $messageEmployeur = \Swift_Message::newInstance()
            ->setSubject('Demande de remboursement ThinkLance.')
            ->setFrom('barely.managing.pidev@gmail.com')
            ->setTo($paiement->getEmployeur()->getEmail())
            //->setBody('Vous venez de recevoir ' + $paiement->getMontant() + ' € de la part de ' + $paiement->getFreelancer()->getUsername() + ' pour votre travail sur le projet ' + $paiement->getProjet()->getNom() + '. Vous pouvez encore consulter la liste de vos paiements, et les statistiques depuis la page des paiements.');
            ->setBody("Votre remboursement conrenant le paiement : " . $id . " a été effectuée.");
        $mailer = $this->get('mailer');
        $mailer->send($messageEmployeur);
        $spool = $mailer->getTransport()->getSpool();
        $transport = $this->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);

        //Vers le freelancer
        $messageFreelancer = \Swift_Message::newInstance()
            ->setSubject('Votre paiement a été retiré.')
            ->setFrom('barely.managing.pidev@gmail.com')
            ->setTo($paiement->getFreelancer()->getEmail())
            //->setBody('Vous venez de recevoir ' + $paiement->getMontant() + ' € de la part de ' + $paiement->getFreelancer()->getUsername() + ' pour votre travail sur le projet ' + $paiement->getProjet()->getNom() + '. Vous pouvez encore consulter la liste de vos paiements, et les statistiques depuis la page des paiements.');
            ->setBody("La paiemet : " . $id . ", conernant le projet : " . $paiement->getProjet()->getTitreProjet() . " a été annulé. Vous pouvez envoyer une réclamation pour plus d'informations.");
        $mailer = $this->get('mailer');
        $mailer->send($messageFreelancer);
        $spool = $mailer->getTransport()->getSpool();
        $transport = $this->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);


        //Suppression du paiement de la base
        $paiement = $em->getRepository(Paiement::class)->find($id);
        $em->remove($paiement);
        $em->flush();
        return $this->redirectToRoute('liste_paiements');
    }

    public function payerFreelancerAction($idProjet, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $projet = $em->getRepository(Projet::class)->find($idProjet);
        $montantAPayer = $projet->getPrix();
        $freelancerAPayer = $projet->getFreelancer();

        $nbrMissions = $projet->getEmployeur()->getNbrMission();
        $nouveauMontant = $montantAPayer;
        if ($nbrMissions < 10) {
            //Ne rien faire
        } else if ($nbrMissions >= 20 && $nbrMissions < 30) {
            $nouveauMontant = $montantAPayer * 0.95;
        } else if ($nbrMissions >= 30) {
            $nouveauMontant = $montantAPayer * 0.9;
        }

        return $this->render('@Paiement\Default\payer_freelancer.html.twig', array(
            "montantAPayer" => $nouveauMontant * 100,
            "freelancerAPayer" => $freelancerAPayer,
            "projet" => $projet,
            "nbr" => $nbrMissions
        ));
    }

    public function payerFreelancerApiAction($idProjet, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $projet = $em->getRepository(Projet::class)->find($idProjet);
        $montantAPayer = $projet->getPrix();
        $freelancerAPayer = $projet->getFreelancer();

        $nbrMissions = $projet->getEmployeur()->getNbrMission();
        $nouveauMontant = $montantAPayer;
        if ($nbrMissions < 10) {
            //Ne rien faire
        } else if ($nbrMissions >= 20 && $nbrMissions < 30) {
            $nouveauMontant = $montantAPayer * 0.95;
        } else if ($nbrMissions >= 30) {
            $nouveauMontant = $montantAPayer * 0.9;
        }

        //Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey("sk_test_pnfuYUNCotWhyq7uOfqDmuGE");

        // Token is created using Checkout or Elements!
        // Get the payment token ID submitted by the form:
        //$token = $_POST['stripeToken'];
        $charge = \Stripe\Charge::create([
            'amount' => $nouveauMontant * 100,
            'currency' => 'eur',
            'description' => 'Paiement du projet : ' + $projet->getTitreProjet(),
            'source' => "tok_mastercard",
        ]);

        $paiement = new Paiement();
        $paiement->setIdPaiement($charge->id);
        $paiement->setDateHeurePaiement(new \DateTime('now'));
        $paiement->setMontant($nouveauMontant);
        //$paiement->setDevise($charge->currency);
        $paiement->setProjet($projet);
        $paiement->setEmployeur($projet->getEmployeur());
        $paiement->setFreelancer($projet->getFreelancer());

        //Enregistrement du paiement dans la table paiement
        $em = $this->getDoctrine()->getManager();
        //Persister les données dans l'ORM
        $em->persist($paiement);
        //Sauvegarde des données dans la base des données
        $em->flush();

        //Envoi du mail vers l'utilisateur
        //Vers l'employeur
        $messageEmployeur = \Swift_Message::newInstance()
            ->setSubject('Votre paiement a été effectué.')
            ->setFrom('barely.managing.pidev@gmail.com')
            ->setTo('moez.abid@esprit.tn')
            ->setBody(
                $this->renderView(
                    '@Paiement\Default\email_paiement_employeur.html.twig',
                    ['paiement' => $paiement]
                ),
                'text/html'
            );
        //->setBody('Votre paiement de ' + $paiement->getMontant() + '€ pour le projet ' + $paiement->getProjet()->getNom() + ' vers ' + $paiement->getFreelancer()->getUsername() + ' a été effecté. Vous pouvez encore consulter la liste de vos paiements, les statistiques ou bien demander un remboursement depuis la page de paiements.');
        //->setBody('Votre paiement a été effecté. Vous pouvez encore consulter la liste de vos paiements, les statistiques ou bien demander un remboursement depuis la page de paiements.');
        $mailer = $this->get('mailer');
        $mailer->send($messageEmployeur);
        $spool = $mailer->getTransport()->getSpool();
        $transport = $this->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);

        //Vers le freelancer
        $messageFreelancer = \Swift_Message::newInstance()
            ->setSubject('Vous venez de recevoir un paiement.')
            ->setFrom('barely.managing.pidev@gmail.com')
            ->setTo($paiement->getFreelancer()->getEmail())
            //->setBody('Vous venez de recevoir ' + $paiement->getMontant() + ' € de la part de ' + $paiement->getFreelancer()->getUsername() + ' pour votre travail sur le projet ' + $paiement->getProjet()->getNom() + '. Vous pouvez encore consulter la liste de vos paiements, et les statistiques depuis la page des paiements.');
            //->setBody('Vous venez de recevoir un paiement. Vous pouvez encore consulter la liste de vos paiements, et les statistiques depuis la page des paiements.');
            ->setBody(
                $this->renderView(
                    '@Paiement\Default\email_paiement_freelancer.html.twig',
                    ['paiement' => $paiement]
                ),
                'text/html'
            );
        $mailer = $this->get('mailer');
        $mailer->send($messageFreelancer);
        $spool = $mailer->getTransport()->getSpool();
        $transport = $this->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($paiement);
        return new JsonResponse($formatted);
    }

    public function confirmerPayementAction($idProjet, $montant, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $projet = $em->getRepository(Projet::class)->find($idProjet);

        //Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey("sk_test_pnfuYUNCotWhyq7uOfqDmuGE");

        // Token is created using Checkout or Elements!
        // Get the payment token ID submitted by the form:
        //$token = $_POST['stripeToken'];
        $charge = \Stripe\Charge::create([
            'amount' => $montant,
            'currency' => 'eur',
            'description' => 'Paiement du projet : ' + $projet->getTitreProjet(),
            'source' => "tok_mastercard",
        ]);

        $paiement = new Paiement();
        $paiement->setIdPaiement($charge->id);
        $paiement->setDateHeurePaiement(new \DateTime('now'));
        $paiement->setMontant($montant / 100);
        //$paiement->setDevise($charge->currency);
        $paiement->setProjet($projet);
        $paiement->setEmployeur($projet->getEmployeur());
        $paiement->setFreelancer($projet->getFreelancer());

        //Enregistrement du paiement dans la table paiement
        $em = $this->getDoctrine()->getManager();
        //Persister les données dans l'ORM
        $em->persist($paiement);
        //Sauvegarde des données dans la base des données
        $em->flush();

        //Envoi du mail vers l'utilisateur
        //Vers l'employeur
        $messageEmployeur = \Swift_Message::newInstance()
            ->setSubject('Votre paiement a été effectué.')
            ->setFrom('barely.managing.pidev@gmail.com')
            ->setTo('moez.abid@esprit.tn')
            ->setBody(
                $this->renderView(
                    '@Paiement\Default\email_paiement_employeur.html.twig',
                    ['paiement' => $paiement]
                ),
                'text/html'
            );
        //->setBody('Votre paiement de ' + $paiement->getMontant() + '€ pour le projet ' + $paiement->getProjet()->getNom() + ' vers ' + $paiement->getFreelancer()->getUsername() + ' a été effecté. Vous pouvez encore consulter la liste de vos paiements, les statistiques ou bien demander un remboursement depuis la page de paiements.');
        //->setBody('Votre paiement a été effecté. Vous pouvez encore consulter la liste de vos paiements, les statistiques ou bien demander un remboursement depuis la page de paiements.');
        $mailer = $this->get('mailer');
        $mailer->send($messageEmployeur);
        $spool = $mailer->getTransport()->getSpool();
        $transport = $this->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);

        //Vers le freelancer
        $messageFreelancer = \Swift_Message::newInstance()
            ->setSubject('Vous venez de recevoir un paiement.')
            ->setFrom('barely.managing.pidev@gmail.com')
            ->setTo($paiement->getFreelancer()->getEmail())
            //->setBody('Vous venez de recevoir ' + $paiement->getMontant() + ' € de la part de ' + $paiement->getFreelancer()->getUsername() + ' pour votre travail sur le projet ' + $paiement->getProjet()->getNom() + '. Vous pouvez encore consulter la liste de vos paiements, et les statistiques depuis la page des paiements.');
            //->setBody('Vous venez de recevoir un paiement. Vous pouvez encore consulter la liste de vos paiements, et les statistiques depuis la page des paiements.');
            ->setBody(
                $this->renderView(
                    '@Paiement\Default\email_paiement_freelancer.html.twig',
                    ['paiement' => $paiement]
                ),
                'text/html'
            );
        $mailer = $this->get('mailer');
        $mailer->send($messageFreelancer);
        $spool = $mailer->getTransport()->getSpool();
        $transport = $this->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);


        return $this->render('@Paiement\Default\confirmer_paiement.html.twig', array(
            "id" => $charge->id
        ));
    }

    public function pdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $paiement = $em->getRepository(Paiement::class)->find($id);

        //Generation du fichier pdf de la facture
        $snappy = $this->get('knp_snappy.pdf');

        $html = $this->renderView('@Paiement\Default\facture.html.twig', array(
            "pay" => $paiement
        ));

        $filename = $paiement->getIdPaiement();

        return new \Symfony\Component\HttpFoundation\Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '.pdf"'
            )
        );
    }

    public function factureHtmlForPdfForDesktopAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $paiement = $em->getRepository(Paiement::class)->find($id);

        return $this->render('@Paiement\Default\facture.html.twig', array(
            "pay" => $paiement
        ));
    }

    public function factureApiAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $paiement = $em->getRepository(Paiement::class)->find($id);

        return $this->render('@Paiement\Default\facture.html.twig', array(
            "pay" => $paiement
        ));
    }

    public function afficherStatistiquesAction(Request $request)
    {
        //Vérifier le role de l'utilisareuer qui est connecté :
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');

        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return $this->statistiquesAdminAction();
        } else if ($authChecker->isGranted('ROLE_FREELANCER')) {
            return $this->statistiquesFreelancerAction();
        } else if ($authChecker->isGranted('ROLE_EMPLOYEUR')) {
            return $this->statistiquesEmployeurAction();
        } else {
            /** @var $session Session */
            $session = $request->getSession();

            $authErrorKey = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;

            // get the error if any (works with forward and redirect -- see below)
            if ($request->attributes->has($authErrorKey)) {
                $error = $request->attributes->get($authErrorKey);
            } elseif (null !== $session && $session->has($authErrorKey)) {
                $error = $session->get($authErrorKey);
                $session->remove($authErrorKey);
            } else {
                $error = null;
            }

            if (!$error instanceof AuthenticationException) {
                $error = null; // The value does not come from the security component.
            }

            // last username entered by the user
            $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

            $csrfToken = $this->tokenManager
                ? $this->tokenManager->getToken('authenticate')->getValue()
                : null;

            return $this->renderLogin(array(
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_token' => $csrfToken,
            ));
        }
    }

    public function statistiquesFreelancerAction()
    {
        $userId = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
        //Combo Chart revenus par mois
        $combo = new ComboChart();
        $combo->getData()->setArrayToDataTable([
            ['Mois', 'Montant'],
            ['Janvier', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMontantPaiementsParmois(1, $userId)],
            ['Fevrier', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMontantPaiementsParmois(2, $userId)],
            ['Mars', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMontantPaiementsParmois(3, $userId)],
            ['Avril', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMontantPaiementsParmois(4, $userId)],
            ['May', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMontantPaiementsParmois(5, $userId)],
            ['Juin', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMontantPaiementsParmois(6, $userId)],
            ['Juillet', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMontantPaiementsParmois(7, $userId)],
            ['Aout', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMontantPaiementsParmois(8, $userId)],
            ['Septembre)', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMontantPaiementsParmois(9, $userId)],
            ['Octobre', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMontantPaiementsParmois(10, $userId)],
            ['Novembre', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMontantPaiementsParmois(11, $userId)],
            ['Decembre', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMontantPaiementsParmois(12, $userId)]]);
        $combo->getOptions()->setTitle('Revenus mensuels par mois');
        $combo->getOptions()->getVAxis()->setTitle('Montant');
        $combo->getOptions()->getHAxis()->setTitle('Mois');
        $combo->getOptions()->setSeriesType('bars');
        $combo->getOptions()->setWidth(900);
        $combo->getOptions()->setHeight(500);

        //ComboCount Chart paiements par mois
        $comboCount = new ComboChart();
        $comboCount->getData()->setArrayToDataTable([
            ['Mois', 'Montant'],
            ['Janvier', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMombrePaiementsParmois(1, $userId)],
            ['Fevrier', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMombrePaiementsParmois(2, $userId)],
            ['Mars', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMombrePaiementsParmois(3, $userId)],
            ['Avril', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMombrePaiementsParmois(4, $userId)],
            ['May', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMombrePaiementsParmois(5, $userId)],
            ['Juin', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMombrePaiementsParmois(6, $userId)],
            ['Juillet', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMombrePaiementsParmois(7, $userId)],
            ['Aout', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMombrePaiementsParmois(8, $userId)],
            ['Septembre)', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMombrePaiementsParmois(9, $userId)],
            ['Octobre', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMombrePaiementsParmois(10, $userId)],
            ['Novembre', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMombrePaiementsParmois(11, $userId)],
            ['Decembre', $this->getDoctrine()->getRepository(Paiement::class)->freelancerMonMombrePaiementsParmois(12, $userId)]]);
        $comboCount->getOptions()->setTitle('Nombre de paiements par mois');
        $comboCount->getOptions()->getVAxis()->setTitle('Nombre de paiements');
        $comboCount->getOptions()->getHAxis()->setTitle('Mois');
        $comboCount->getOptions()->setSeriesType('bars');
        $comboCount->getOptions()->setWidth(900);
        $comboCount->getOptions()->setHeight(500);
        return $this->render('@Paiement\Default\statistiques_freelancer.html.twig', array(
            //'piecharteclassemployeurs' => $pieChartClassProjets,
            "combo" => $combo,
            "comboCount" => $comboCount,
        ));
    }

    public function statistiquesEmployeurAction()
    {
        $userId = $this->container->get('security.token_storage')->getToken()->getUser()->getId();
        //Combo Chart revenus par mois
        $combo = new ComboChart();
        $combo->getData()->setArrayToDataTable([
            ['Mois', 'Montant'],
            ['Janvier', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMontantPaiementsParmois(1, $userId)],
            ['Fevrier', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMontantPaiementsParmois(2, $userId)],
            ['Mars', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMontantPaiementsParmois(3, $userId)],
            ['Avril', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMontantPaiementsParmois(4, $userId)],
            ['May', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMontantPaiementsParmois(5, $userId)],
            ['Juin', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMontantPaiementsParmois(6, $userId)],
            ['Juillet', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMontantPaiementsParmois(7, $userId)],
            ['Aout', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMontantPaiementsParmois(8, $userId)],
            ['Septembre)', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMontantPaiementsParmois(9, $userId)],
            ['Octobre', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMontantPaiementsParmois(10, $userId)],
            ['Novembre', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMontantPaiementsParmois(11, $userId)],
            ['Decembre', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMontantPaiementsParmois(12, $userId)]]);
        $combo->getOptions()->setTitle('Revenus mensuels par mois');
        $combo->getOptions()->getVAxis()->setTitle('Montant');
        $combo->getOptions()->getHAxis()->setTitle('Mois');
        $combo->getOptions()->setSeriesType('bars');
        $combo->getOptions()->setWidth(900);
        $combo->getOptions()->setHeight(500);

        //ComboCount Chart paiements par mois
        $comboCount = new ComboChart();
        $comboCount->getData()->setArrayToDataTable([
            ['Mois', 'Montant'],
            ['Janvier', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMombrePaiementsParmois(1, $userId)],
            ['Fevrier', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMombrePaiementsParmois(2, $userId)],
            ['Mars', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMombrePaiementsParmois(3, $userId)],
            ['Avril', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMombrePaiementsParmois(4, $userId)],
            ['May', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMombrePaiementsParmois(5, $userId)],
            ['Juin', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMombrePaiementsParmois(6, $userId)],
            ['Juillet', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMombrePaiementsParmois(7, $userId)],
            ['Aout', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMombrePaiementsParmois(8, $userId)],
            ['Septembre)', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMombrePaiementsParmois(9, $userId)],
            ['Octobre', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMombrePaiementsParmois(10, $userId)],
            ['Novembre', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMombrePaiementsParmois(11, $userId)],
            ['Decembre', $this->getDoctrine()->getRepository(Paiement::class)->employeurMonMombrePaiementsParmois(12, $userId)]]);
        $comboCount->getOptions()->setTitle('Nombre de paiements par mois');
        $comboCount->getOptions()->getVAxis()->setTitle('Nombre de paiements');
        $comboCount->getOptions()->getHAxis()->setTitle('Mois');
        $comboCount->getOptions()->setSeriesType('bars');
        $comboCount->getOptions()->setWidth(900);
        $comboCount->getOptions()->setHeight(500);

        return $this->render('@Paiement\Default\statistiques_employeur.html.twig', array(
            //'piecharteclassemployeurs' => $pieChartClassProjets,
            "combo" => $combo,
            "comboCount" => $comboCount,
        ));
    }

    public function statistiquesAdminAction()
    {
        //Répartition des paiements selon les classements des employeurs
        $nbBronzeEmp = $this->getDoctrine()->getRepository(Paiement::class)->nombreDePaiementSelonLaClasseDeLemployeur("Bronze");
        $nbSilverEmp = $this->getDoctrine()->getRepository(Paiement::class)->nombreDePaiementSelonLaClasseDeLemployeur("Silver");
        $nbGoldEmp = $this->getDoctrine()->getRepository(Paiement::class)->nombreDePaiementSelonLaClasseDeLemployeur("Gold");
        $total = $nbBronzeEmp + $nbSilverEmp + $nbGoldEmp;
        $pieChartClassEmployeur = new PieChart();
        $pieChartClassEmployeur->getData()->setArrayToDataTable(
            [['Classement', 'Nombre de paiements'],
                ['Bronze', ($nbBronzeEmp * $total) / 100],
                ['Silver', ($nbSilverEmp * $total) / 100],
                ['Gold', ($nbGoldEmp * $total) / 100]
            ]
        );
        $pieChartClassEmployeur->getOptions()->setTitle('Répartition des paiements selon les classements des employeurs');
        $pieChartClassEmployeur->getOptions()->setHeight(500);
        $pieChartClassEmployeur->getOptions()->setWidth(900);
        $pieChartClassEmployeur->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChartClassEmployeur->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChartClassEmployeur->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChartClassEmployeur->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChartClassEmployeur->getOptions()->getTitleTextStyle()->setFontSize(20);

        //Répartition des paiements selon les classements des freelancers
        $nbBronzeFree = $this->getDoctrine()->getRepository(Paiement::class)->nombreDePaiementSelonLaClasseDuFreelancer("Bronze");
        $nbSilverFree = $this->getDoctrine()->getRepository(Paiement::class)->nombreDePaiementSelonLaClasseDuFreelancer("Silver");
        $nbGoldFree = $this->getDoctrine()->getRepository(Paiement::class)->nombreDePaiementSelonLaClasseDuFreelancer("Gold");
        $totalFree = $nbBronzeFree + $nbSilverFree + $nbGoldFree;
        $pieChartClassFreelancer = new PieChart();
        $pieChartClassFreelancer->getData()->setArrayToDataTable(
            [['Classement', 'Nombre de paiements'],
                ['Bronze', ($nbBronzeFree * $totalFree) / 100],
                ['Silver', ($nbSilverFree * $totalFree) / 100],
                ['Gold', ($nbGoldFree * $totalFree) / 100]
            ]
        );
        $pieChartClassFreelancer->getOptions()->setTitle('Répartition des paiements selon les classements des freelancers');
        $pieChartClassFreelancer->getOptions()->setHeight(500);
        $pieChartClassFreelancer->getOptions()->setWidth(900);
        $pieChartClassFreelancer->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChartClassFreelancer->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChartClassFreelancer->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChartClassFreelancer->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChartClassFreelancer->getOptions()->getTitleTextStyle()->setFontSize(20);

        //Combo Chart revenus par mois
        $combo = new ComboChart();
        $combo->getData()->setArrayToDataTable([
            ['Mois', 'Montant'],
            ['Janvier', $this->getDoctrine()->getRepository(Paiement::class)->montantPaiementsParmois(1)],
            ['Fevrier', $this->getDoctrine()->getRepository(Paiement::class)->montantPaiementsParmois(2)],
            ['Mars', $this->getDoctrine()->getRepository(Paiement::class)->montantPaiementsParmois(3)],
            ['Avril', $this->getDoctrine()->getRepository(Paiement::class)->montantPaiementsParmois(4)],
            ['May', $this->getDoctrine()->getRepository(Paiement::class)->montantPaiementsParmois(5)],
            ['Juin', $this->getDoctrine()->getRepository(Paiement::class)->montantPaiementsParmois(6)],
            ['Juillet', $this->getDoctrine()->getRepository(Paiement::class)->montantPaiementsParmois(7)],
            ['Aout', $this->getDoctrine()->getRepository(Paiement::class)->montantPaiementsParmois(8)],
            ['Septembre)', $this->getDoctrine()->getRepository(Paiement::class)->montantPaiementsParmois(9)],
            ['Octobre', $this->getDoctrine()->getRepository(Paiement::class)->montantPaiementsParmois(10)],
            ['Novembre', $this->getDoctrine()->getRepository(Paiement::class)->montantPaiementsParmois(11)],
            ['Decembre', $this->getDoctrine()->getRepository(Paiement::class)->montantPaiementsParmois(12)]]);
        $combo->getOptions()->setTitle('Revenus mensuels par mois');
        $combo->getOptions()->getVAxis()->setTitle('Montant');
        $combo->getOptions()->getHAxis()->setTitle('Mois');
        $combo->getOptions()->setSeriesType('bars');
        $combo->getOptions()->setWidth(900);
        $combo->getOptions()->setHeight(500);

        //ComboCount Chart paiements par mois
        $comboCount = new ComboChart();
        $comboCount->getData()->setArrayToDataTable([
            ['Mois', 'Montant'],
            ['Janvier', $this->getDoctrine()->getRepository(Paiement::class)->nombrePaiementsParmois(1)],
            ['Fevrier', $this->getDoctrine()->getRepository(Paiement::class)->nombrePaiementsParmois(2)],
            ['Mars', $this->getDoctrine()->getRepository(Paiement::class)->nombrePaiementsParmois(3)],
            ['Avril', $this->getDoctrine()->getRepository(Paiement::class)->nombrePaiementsParmois(4)],
            ['May', $this->getDoctrine()->getRepository(Paiement::class)->nombrePaiementsParmois(5)],
            ['Juin', $this->getDoctrine()->getRepository(Paiement::class)->nombrePaiementsParmois(6)],
            ['Juillet', $this->getDoctrine()->getRepository(Paiement::class)->nombrePaiementsParmois(7)],
            ['Aout', $this->getDoctrine()->getRepository(Paiement::class)->nombrePaiementsParmois(8)],
            ['Septembre)', $this->getDoctrine()->getRepository(Paiement::class)->nombrePaiementsParmois(9)],
            ['Octobre', $this->getDoctrine()->getRepository(Paiement::class)->nombrePaiementsParmois(10)],
            ['Novembre', $this->getDoctrine()->getRepository(Paiement::class)->nombrePaiementsParmois(11)],
            ['Decembre', $this->getDoctrine()->getRepository(Paiement::class)->nombrePaiementsParmois(12)]]);
        $comboCount->getOptions()->setTitle('Nombre de paiements par mois');
        $comboCount->getOptions()->getVAxis()->setTitle('Nombre de paiements');
        $comboCount->getOptions()->getHAxis()->setTitle('Mois');
        $comboCount->getOptions()->setSeriesType('bars');
        $comboCount->getOptions()->setWidth(900);
        $comboCount->getOptions()->setHeight(500);


        return $this->render('@Paiement\Default\statistiques_administrateur.html.twig', array(
            'piecharteclassemployeurs' => $pieChartClassEmployeur,
            'piecharteclassefreelancers' => $pieChartClassFreelancer,
            "combo" => $combo,
            "comboCount" => $comboCount,
            "nbrBEmp" => $nbBronzeEmp,
            "nbrSEmp" => $nbSilverEmp,
            "nbrGEmp" => $nbGoldEmp,
            "nbrBFree" => $nbBronzeFree,
            "nbrSFree" => $nbSilverFree,
            "nbrGFree" => $nbGoldFree,
        ));
    }

    public function rechecherPaiementsAdminIdAction(Request $request)
    {

        $results = $this->getDoctrine()->getRepository(Paiement::class)->rechercheParIdPaiementAdmin($request->query->get('search'));

        return new JsonResponse($results);
    }

    public function rechecherPaiementsParIdEmployeurApiAction($idEmployeur, $idMotCle, Request $request)
    {
        $results = $this->getDoctrine()->getRepository(Paiement::class)->rechercheParIdPaiementEmployeur($idEmployeur, $idMotCle);

        return new JsonResponse($results);
    }

    public function rechecherPaiementsParIdFreelancerApiAction($idFreelancer, $idMotCle, Request $request)
    {
        $results = $this->getDoctrine()->getRepository(Paiement::class)->rechercheParIdPaiementFreelancer($idFreelancer, $idMotCle);

        return new JsonResponse($results);
    }

    public function rechecherPaiementsAdminEmployeurAction(Request $request)
    {

        $results = $this->getDoctrine()->getRepository(Paiement::class)->rechercheParEmployeurPaiementAdmin($request->query->get('searchEmp'));

        return new JsonResponse($results);
    }

    public function rechecherPaiementsAdminFreelancerAction(Request $request)
    {

        $results = $this->getDoctrine()->getRepository(Paiement::class)->rechercheParFreelancerPaiementAdmin($request->query->get('searchFree'));

        return new JsonResponse($results);
    }

    public function rechecherPaiementsAdminProjetAction(Request $request)
    {

        $results = $this->getDoctrine()->getRepository(Paiement::class)->rechercheParProjetPaiementAdmin($request->query->get('searchProj'));

        return new JsonResponse($results);
    }

    public function rechecherPaiementsAdminMontantAction(Request $request)
    {

        $results = $this->getDoctrine()->getRepository(Paiement::class)->rechercheParMontantPaiementAdmin($request->query->get('montantMax'));

        return new JsonResponse($results);
    }

    public function rechecherPaiementsAdminDateAction(Request $request)
    {

        $results = $this->getDoctrine()->getRepository(Paiement::class)->rechercheParDatePaiementAdmin($request->query->get('dateDebut'));

        return new JsonResponse($results);
    }
}
