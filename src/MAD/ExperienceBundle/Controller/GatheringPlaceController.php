<?php

namespace MAD\ExperienceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GatheringPlaceController extends Controller
{
    public function homeAction()
    {
        return $this->render('MADExperienceBundle:Home:index.html.twig');
    }
}
