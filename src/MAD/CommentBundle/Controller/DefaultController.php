<?php

namespace MAD\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MADCommentBundle:Default:index.html.twig', array('name' => $name));
    }
}
