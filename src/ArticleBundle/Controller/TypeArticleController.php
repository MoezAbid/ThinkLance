<?php

namespace ArticleBundle\Controller;

use ArticleBundle\Entity\TypeArticle;
use ArticleBundle\Form\TypeArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use FOS\UserBundle\Controller\SecurityController as FOSController;

class TypeArticleController extends FOSController
{
    private $tokenManager;

    public function __construct(CsrfTokenManagerInterface $tokenManager = null)
    {
        $this->tokenManager = $tokenManager;
    }

    public function ajouterTypeArticleAction(Request $request)
    {
        //Vérifier le role de l'utilisareuer qui est connecté :
        $authChecker = $this->container->get('security.authorization_checker');

        if ($authChecker->isGranted('ROLE_ADMIN')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            //Etape 1 : Préparation d'un objet vide.
            $typeArticle = new TypeArticle();
            //Etape 2 : Création de formulaire
            $form = $this->createForm(TypeArticleType::class, $typeArticle);
            //Etape 4 : Récuperation des données.
            $form = $form->handleRequest($request);
            //Etape 5 : Validation du formulaire
            if ($form->isValid()) {
                //Etape 6 : Création de l'entity manager
                $em = $this->getDoctrine()->getManager();
                //Etape 7 : Persister les données dans l'ORM
                $em->persist($typeArticle);
                //Etape 8 : Sauvegarde des données dans la base des données
                $em->flush();
                return $this->redirectToRoute('lister_types_articles');
            }
            //Etape 3 : Envoi du formulaire.
            return $this->render('@Article\TypeArticle\ajouter_type_article.html.twig', array(
                "form" => $form->createView()
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

    public function modifierTypeArticleAction($id, Request $request)
    {

        //Vérifier le role de l'utilisareuer qui est connecté :
        $authChecker = $this->container->get('security.authorization_checker');

        if ($authChecker->isGranted('ROLE_ADMIN')) {
            //Etape 1 : Prépartion de l'entity manager
            $em = $this->getDoctrine()->getManager();
            //Etape 2 : Préparation de notre objet
            $typeArticle = $em->getRepository(TypeArticle::class)->find($id);
            //Etape 3 : Préparation de notre form
            $form = $this->createForm(TypeArticleType::class, $typeArticle);
            //Etape 5 : Récupération du formulaire
            $form = $form->handleRequest($request);

            //Etape 6 : Validation du formulaire :
            if ($form->isValid()) {
                //Etape 7 : Update dans la BD
                $em->flush();
                //Etape 8 : Redirection
                return $this->redirect($this->generateUrl('lister_types_articles', array(
                    'id' => $id)));
            }
            return $this->render('@Article\TypeArticle\modifier_type_article.html.twig', array(
                //Etape 4 : Envoi du form à l'utilisateur
                'form' => $form->createView()
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
        //End
    }

    public function supprimerTypeArticleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $typeArticle = $em->getRepository(TypeArticle::class)->find($id);
        $em->remove($typeArticle);
        $em->flush();
        return $this->redirectToRoute('lister_types_articles');


        return $this->render('ArticleBundle:TypeArticle:supprimer_type_article.html.twig', array(// ...
        ));
    }

    public function listerTypesArticlesAction(Request $request)
    {

        //Vérifier le role de l'utilisareuer qui est connecté :
        $authChecker = $this->container->get('security.authorization_checker');

        $typesArticles = $this->getDoctrine()->getRepository(TypeArticle::class)->findAll();

        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return $this->render('@Article\TypeArticle\lister_types_articles.html.twig', array(
                "typesArticles" => $typesArticles
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
        //End
        return $this->render('ArticleBundle:TypeArticle:.html.twig', array(// ...
        ));
    }

}
