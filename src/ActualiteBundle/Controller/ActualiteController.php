<?php

namespace ActualiteBundle\Controller;

use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use FOS\UserBundle\Controller\SecurityController as FOSController;

//require 'vendor/autoload.php';

class ActualiteController extends FOSController
{

    private $tokenManager;

    public function __construct(CsrfTokenManagerInterface $tokenManager = null)
    {
        $this->tokenManager = $tokenManager;
    }

    public function nouveautesActualitesAction(Request $request)
    {
        //Vérifier le role de l'utilisareuer qui est connecté :
        $authChecker = $this->container->get('security.authorization_checker');

        if ($authChecker->isGranted('ROLE_ADMIN') || $authChecker->isGranted('ROLE_EMPLOYEUR') || $authChecker->isGranted('ROLE_FREELANCER')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $client = new Client();
            $resTech = $client->request('GET', 'http://newsapi.org/v2/top-headlines?sources=techcrunch&apiKey=548006773cb541218d1f486de78f76f1')->getBody();
            $resProg = $client->request('GET', 'http://newsapi.org/v2/everything?q=programming&sortBy=popularity&apiKey=548006773cb541218d1f486de78f76f1')->getBody();
            $resFreelance = $client->request('GET', 'http://newsapi.org/v2/everything?q=freelance&sortBy=popularity&apiKey=548006773cb541218d1f486de78f76f1')->getBody();

            $resTech = json_decode($resTech);
            $resProg = json_decode($resProg);
            $resFreelance = json_decode($resFreelance);

            $arrayTech = [];
            $arrayProg = [];
            $arrayFreelance = [];

            foreach ($resTech->articles as $article) {
                array_push($arrayTech, $article);
            }

            foreach ($resProg->articles as $article) {
                array_push($arrayProg, $article);
            }

            foreach ($resFreelance->articles as $article) {
                array_push($arrayFreelance, $article);
            }

            return $this->render('@Actualite\Default\nouveautes.html.twig', array(
                "tech" => $arrayTech,
                "prog" => $arrayProg,
                "freelance" => $arrayFreelance
            ));

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
}
