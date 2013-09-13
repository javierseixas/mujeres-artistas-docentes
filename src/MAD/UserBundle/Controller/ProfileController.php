<?php

namespace MAD\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    public function editPictureAction()
    {
        return $this->render('MADUserBundle:Profile:picture.html.twig');
    }
}
