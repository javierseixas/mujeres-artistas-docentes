<?php

namespace MAD\ExperienceBundle\Controller;

use MAD\ExperienceBundle\Entity\Question;
use MAD\ExperienceBundle\Entity\Subject;
use MAD\ExperienceBundle\Form\Type\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use MAD\UserBundle\Entity\Group;

class ForumController extends Controller
{
    public function homeAction()
    {
        return $this->render('MADExperienceBundle:Forum:home.html.twig');
    }
}