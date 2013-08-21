<?php

namespace MAD\ExperienceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SharedExperiencesController extends Controller
{

    public function listAction()
    {
        $subjectsList = $this->getDoctrine()->getRepository('MADExperienceBundle:Subject')->findSubjectQuestionsAndSharedAnswers($this->get('security.context')->getToken()->getUser()->getId());

        $freeExperiences = $this->getDoctrine()->getRepository('MADExperienceBundle:Experience')->findSharedFreeExperiences($this->get('security.context')->getToken()->getUser()->getId());

        return $this->render('MADExperienceBundle:SharedExperiences:list.html.twig', array(
            'freeExperiences' => $freeExperiences,
            'subjectsList' => $subjectsList,
        ));
    }
}
