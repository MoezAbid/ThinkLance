<?php

namespace ArticleBundle\Controller;

use AppBundle\Entity\User;
use ArticleBundle\Entity\Article;
use ArticleBundle\Entity\TypeArticle;
use ArticleBundle\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use FOS\UserBundle\Controller\SecurityController as FOSController;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ArticleController extends FOSController
{

    private $tokenManager;

    public function __construct(CsrfTokenManagerInterface $tokenManager = null)
    {
        $this->tokenManager = $tokenManager;
    }

    public function listeArticlesApiAction()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->articlesOrdonnes();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($articles);
        return new JsonResponse($formatted);
    }

    public function listeArticlesAction(Request $request)
    {
        //Vérifier le role de l'utilisareuer qui est connecté :
        $authChecker = $this->container->get('security.authorization_checker');

        $articles = $this->getDoctrine()->getRepository(Article::class)->articlesOrdonnes();

        if ($authChecker->isGranted('ROLE_ADMIN')) {
            return $this->render('@Article\Default\liste_articles.html.twig', array(
                "articles" => $articles
            ));
        } else if ($authChecker->isGranted('ROLE_FREELANCER')) {
            return $this->render('@Article\Default\liste_articles.html.twig', array(
                "articles" => $articles
            ));
        } else if ($authChecker->isGranted('ROLE_EMPLOYEUR')) {
            return $this->render('@Article\Default\liste_articles.html.twig', array(
                "articles" => $articles
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

    public function maListeArticlesApiAction($idUserConnecte, Request $request)
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->mesArticles($idUserConnecte);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($articles);
        return new JsonResponse($formatted);
    }

    public function maListeArticlesAction(Request $request)
    {
        //Vérifier le role de l'utilisareuer qui est connecté :
        $authChecker = $this->container->get('security.authorization_checker');

        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        if ($authChecker->isGranted('ROLE_ADMIN') || $authChecker->isGranted('ROLE_EMPLOYEUR') || $authChecker->isGranted('ROLE_FREELANCER')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $articles = $this->getDoctrine()->getRepository(Article::class)->mesArticles($user->getId());
            return $this->render('ArticleBundle:Default:mes_articles.html.twig', array(
                "articles" => $articles
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

    public function ajouterArticleApiAction($idUser, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($idUser);
        $typeArticle = $this->getDoctrine()->getRepository(TypeArticle::class)->find($request->request->get('type'));
        $article = new Article();
        $article->setUtilisateur($user);
        $article->setDateHeure(new \DateTime('now'));
        $article->setDescription($request->request->get('description'));
        $article->setTexte($request->request->get('texte'));
        $article->setTitre($request->request->get('titre'));
        $article->setType($typeArticle);
        //Image de l'article
        /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $request->files->get('photo');
        $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
        // Move the file to the directory where articlePhotos are stored
        try {
            $file->move(
                $this->getParameter('articles_directory'),
                $fileName
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        $article->setPhotoArticle($fileName);
        //Image de l'article Fin

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($article);
        return new JsonResponse($formatted);
    }

    public function modifierArticleApiAction($id, Request $request)
    {
        $typeArticle = $this->getDoctrine()->getRepository(TypeArticle::class)->find($request->request->get('type'));
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->find($id);
        $article->setDescription($request->request->get('description'));
        $article->setTexte($request->request->get('texte'));
        $article->setTitre($request->request->get('titre'));
        $article->setType($typeArticle);
        //Image de l'article
        /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $request->files->get('photo');
        $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
        // Move the file to the directory where articlePhotos are stored
        try {
            $file->move(
                $this->getParameter('articles_directory'),
                $fileName
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        $article->setPhotoArticle($fileName);
        //Image de l'article Fin

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($article);
        return new JsonResponse($formatted);
    }

    public function ajouterArticleAction(Request $request)
    {
        //Vérifier le role de l'utilisareuer qui est connecté :
        $authChecker = $this->container->get('security.authorization_checker');

        if ($authChecker->isGranted('ROLE_ADMIN') || $authChecker->isGranted('ROLE_EMPLOYEUR') || $authChecker->isGranted('ROLE_FREELANCER')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            //Etape 1 : Préparation d'un objet vide.
            $article = new Article();
            $article->setUtilisateur($user);
            $article->setDateHeure(new \DateTime('now'));
            //Etape 2 : Création de formulaire
            $form = $this->createForm(ArticleType::class, $article);
            //Etape 4 : Récuperation des données.
            $form = $form->handleRequest($request);
            //Etape 5 : Validation du formulaire
            if ($form->isValid()) {
                if ($article->getPhotoArticle() != null) {
                    //Image de l'article
                    /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
                    $file = $article->getPhotoArticle();
                    $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                    // Move the file to the directory where articlePhotos are stored
                    try {
                        $file->move(
                            $this->getParameter('articles_directory'),
                            $fileName
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $article->setPhotoArticle($fileName);
                    //Image de l'article Fin
                }

                //Etape 6 : Création de l'entity manager
                $em = $this->getDoctrine()->getManager();
                //Etape 7 : Persister les données dans l'ORM
                $em->persist($article);
                //Etape 8 : Sauvegarde des données dans la base des données
                $em->flush();
                return $this->redirectToRoute('mes_articles');
            }
            //Etape 3 : Envoi du formulaire.
            return $this->render('@Article\Default\ajouter_article.html.twig', array(
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

    public function modifierArticleAction($id, Request $request)
    {
        //Vérifier le role de l'utilisareuer qui est connecté :
        $authChecker = $this->container->get('security.authorization_checker');

        if ($authChecker->isGranted('ROLE_ADMIN') || $authChecker->isGranted('ROLE_EMPLOYEUR') || $authChecker->isGranted('ROLE_FREELANCER')) {
            //Etape 1 : Prépartion de l'entity manager
            $em = $this->getDoctrine()->getManager();
            //Etape 2 : Préparation de notre objet
            $article = $em->getRepository(Article::class)->find($id);
            $oldPhoto = $article->getPhotoArticle();
            //Etape 3 : Préparation de notre form
            $form = $this->createForm(ArticleType::class, $article);
            //Etape 5 : Récupération du formulaire
            $form = $form->handleRequest($request);

            //Etape 6 : Validation du formulaire :
            if ($form->isValid()) {
                if ($article->getPhotoArticle() != null) {
                    /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
                    $file = $article->getPhotoArticle();
                    $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                    // Move the file to the directory where articlePhotos are stored
                    try {
                        $file->move(
                            $this->getParameter('articles_directory'),
                            $fileName
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $article->setPhotoArticle($fileName);
                } else {
                    $article->setPhotoArticle($oldPhoto);
                }

                //Etape 7 : Update dans la BD
                $em->flush();
                //Etape 8 : Redirection
                return $this->redirect($this->generateUrl('mes_articles', array(
                    'id' => $id)));
            }
            return $this->render('@Article\Default\modifier_article.html.twig', array(
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

    public function supprimerArticleApiAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->find($id);
        $em->remove($article);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($article);
        return new JsonResponse($formatted);
    }
    public function supprimerArticleAction($id, Request $request)
    {        //Vérifier le role de l'utilisareuer qui est connecté :
        $authChecker = $this->container->get('security.authorization_checker');

        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        if ($authChecker->isGranted('ROLE_ADMIN') || $authChecker->isGranted('ROLE_EMPLOYEUR') || $authChecker->isGranted('ROLE_FREELANCER')) {
            $em = $this->getDoctrine()->getManager();
            $article = $em->getRepository(Article::class)->find($id);
            $em->remove($article);
            $em->flush();
            return $this->redirectToRoute('mes_articles');
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

    public function afficherArticleApiAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($article);
        return new JsonResponse($formatted);
    }

    public function afficherArticleAction($id, Request $request)
    {
        //Vérifier le role de l'utilisareuer qui est connecté :
        $authChecker = $this->container->get('security.authorization_checker');

        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        if ($authChecker->isGranted('ROLE_ADMIN') || $authChecker->isGranted('ROLE_EMPLOYEUR') || $authChecker->isGranted('ROLE_FREELANCER')) {
            $em = $this->getDoctrine()->getManager();
            $article = $em->getRepository(Article::class)->find($id);
            return $this->render('@Article\Default\afficher_article.html.twig', array(
                "article" => $article
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

    public function rechecherArticleParNomAction(Request $request)
    {

        $results = $this->getDoctrine()->getRepository(Article::class)->rechercheParNom($request->query->get('searchNomArticle'));

        return new JsonResponse($results);
    }

    public function rechecherArticleParNomApiAction($nomMotCle, Request $request)
    {

        $results = $this->getDoctrine()->getRepository(Article::class)->rechercheParNom($nomMotCle);

        return new JsonResponse($results);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
