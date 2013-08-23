<?php

namespace MAD\ExperienceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InvestigationController extends Controller
{
    public function indexAction()
    {
        return $this->render('MADExperienceBundle:Investigation:index.html.twig');
    }

    public function projectAction()
    {
        return $this->render('MADExperienceBundle:Investigation:project.html.twig');
    }

    public function feminismAction()
    {
        return $this->render('MADExperienceBundle:Investigation:feminism.html.twig');
    }

    public function narrativeAction()
    {
        return $this->render('MADExperienceBundle:Investigation:narrative.html.twig');
    }

    public function otherSubjectsAction()
    {
        return $this->render('MADExperienceBundle:Investigation:other_subjects.html.twig');
    }

}
