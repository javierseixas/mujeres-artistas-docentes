<?php

namespace MAD\ExperienceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function homeAction($name)
    {
        return $this->render('MADExperienceBundle:Home:index.html.twig');
    }
}
