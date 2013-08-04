<?php

namespace MAD\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MADUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
