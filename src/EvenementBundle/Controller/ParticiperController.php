<?php

namespace EvenementBundle\Controller;

use EvenementBundle\Entity\Evenement;
use EvenementBundle\Entity\Participer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
class ParticiperController extends Controller
{
    public function participerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $participer = new Participer();
        $participer->setEvent($request->get('event'));
        $participer->setUser($request->get('user'));
        $em->persist($participer);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($participer);
        return new JsonResponse($formatted);
    }
    public function participercAction()
    {
        $Participer = $this->getDoctrine()->getManager()
            ->getRepository('EvenementBundle:Participer')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Participer);
        return new JsonResponse($formatted);
    }

}
