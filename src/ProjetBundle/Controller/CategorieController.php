<?php
namespace ProjetBundle\Controller;

use ProjetBundle\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CategorieController extends Controller
{

    public function getAllCategorieAction()
    {
        $tasks = $this->getDoctrine()->getManager()
            ->getRepository('ProjetBundle:Categorie')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tasks);
        return new JsonResponse($formatted);

    }


    public function newCategorieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $task = new Categorie();
        $task->setTitreCategorie($request->get('titreCategorie'));
        $em->persist($task);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($task);
        return new JsonResponse($formatted);
    }

}