<?php

namespace GrapheBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GrapheBundle:Default:read.html.twig');
    }
}
