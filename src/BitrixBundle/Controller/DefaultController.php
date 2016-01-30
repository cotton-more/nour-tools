<?php

namespace BitrixBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BitrixBundle:Default:index.html.twig');
    }
}
