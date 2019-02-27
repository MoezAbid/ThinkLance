<?php

namespace ProfileBundle\Controller;

use ProfileBundle\Entity\PortFolio;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PortfolioController extends Controller
{
    //Supprimer PortFolio

    public function supprimerportfolioAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $portfolio = $em->getRepository(PortFolio::class)->find($id);
        $em->remove($portfolio);
        $em->flush();
        return $this->redirectToRoute('visualiser_profile_page');
    }
}
