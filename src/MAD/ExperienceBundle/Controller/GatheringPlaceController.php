<?php

namespace MAD\ExperienceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GatheringPlaceController extends Controller
{
    public function homeAction()
    {
        return $this->render('MADExperienceBundle:Home:index.html.twig');
    }

    public function myExperiencesAction()
    {
        return $this->render('MADExperienceBundle:Home:my_experiences.html.twig');
    }
}
