<?php

namespace AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Auth\Default\index.html.twig');
    }

    public function customLoginAction()
    {
        return $this->render('@Auth\Default\custom_login.html.twig');
    }
}
