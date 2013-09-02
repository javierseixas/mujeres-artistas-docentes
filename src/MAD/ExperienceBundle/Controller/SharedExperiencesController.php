<?php

namespace MAD\ExperienceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SharedExperiencesController extends Controller
{

    public function listAction()
    {
        $subjectsList = $this->getDoctrine()->getRepository('MADExperienceBundle:Subject')->findAll();

        $freeExperiences = $this->getDoctrine()->getRepository('MADExperienceBundle:Experience')->findSharedFreeExperiences($this->get('security.context')->getToken()->getUser()->getId());

        return $this->render('MADExperienceBundle:SharedExperiences:subjects_list.html.twig', array(
            'freeExperiences' => $freeExperiences,
            'subjectsList' => $subjectsList,
        ));
    }

    public function showExperiencesAction($subjectId)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $userGroups = $this->getDoctrine()->getRepository('MADUserBundle:Group')->findGroupsByUser($user->getId());
        $experiences = $this->getDoctrine()->getRepository('MADExperienceBundle:Question')->findQuestionsAndSharedAnswersByGroups($userGroups, $subjectId);

        $subject = $this->getDoctrine()->getRepository('MADExperienceBundle:Subject')->find($subjectId);

        return $this->render('MADExperienceBundle:SharedExperiences:experiences.html.twig', array(
            'experiences' => $experiences,
            'subject' => $subject,
        ));

    }

    public function readExperienceAction($experienceId)
    {
        // TODO Check if current user is researcher or if experience is shared with all

        $experience = $this->getDoctrine()->getRepository('MADExperienceBundle:Experience')->find($experienceId);

        return $this->render('MADExperienceBundle:MyExperiences:read_shared_experience.html.twig', array(
            'experience' => $experience,
        ));

    }
}
