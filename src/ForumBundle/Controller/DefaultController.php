<?php

namespace ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ForumBundle:Default:index.html.twig');
    }
    public function rAction()
    {
        return $this->render('ForumBundle:question:create.html.twig');
    }
}
