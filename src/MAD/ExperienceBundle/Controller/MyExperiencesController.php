<?php

namespace MAD\ExperienceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MAD\ExperienceBundle\Entity\Experience;
use MAD\ExperienceBundle\Form\Type\ExperienceType;
use Symfony\Component\HttpFoundation\Request;

class MyExperiencesController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myExperiencesAction()
    {

        $subjectsList = $this->getDoctrine()->getRepository('MADExperienceBundle:Subject')->findAll();

        $freeExperiences = $this->getDoctrine()->getRepository('MADExperienceBundle:Experience')->findUserFreeExperiences($this->get('security.context')->getToken()->getUser()->getId());

        return $this->render('MADExperienceBundle:MyExperiences:my_experiences.html.twig', array(
            'freeExperiences' => $freeExperiences,
            'subjectsList' => $subjectsList,
        ));
    }

    public function showQuestionsAction($subjectId)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $userGroups = $this->getDoctrine()->getRepository('MADUserBundle:Group')->findGroupsByUser($user->getId());

        $subject = $this->getDoctrine()->getRepository('MADExperienceBundle:Subject')->find($subjectId);

        $questions = $this->getDoctrine()->getRepository('MADExperienceBundle:Question')->findQuestionsAndAnswersByGroupAndSubject($userGroups, $subjectId, $user->getId());

        return $this->render('MADExperienceBundle:MyExperiences:questions.html.twig', array(
            'questions' => $questions,
            'subject' => $subject,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function writeExperienceAction(Request $request)
    {
    	$experience = new Experience();
        $experience->setUser($this->get('security.context')->getToken()->getUser());

    	$form = $this->createForm(new ExperienceType(), $experience);

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($experience);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Experiencia guardada!'
                );
            }
        }

        return $this->render('MADExperienceBundle:MyExperiences:write_experience.html.twig', array(
        	'form' => $form->createView()
        ));
    }

    public function editExperienceAction(Request $request, $experienceId)
    {
        // TODO Check that is own experience or user is researcher

        $experience = $this->getDoctrine()->getRepository('MADExperienceBundle:Experience')->find($experienceId);

        $form = $this->createForm(new ExperienceType(), $experience);

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {

                // TODO this could be a listener
                if (false !== strpos($request->get('share'), 'todas')) $experience->setSharedWithAll(true);

                $em = $this->getDoctrine()->getManager();
                $em->persist($experience);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Experiencia editada!'
                );
            }
        }

        return $this->render('MADExperienceBundle:MyExperiences:edit_experience.html.twig', array(
            'form' => $form->createView(),
            'question' => $experience->getQuestion(),
            'experience' => $experience
        ));
    }

    public function readExperienceAction($experienceId)
    {
        // TODO Check if current user is researcher or if experience is shared with all

        $experience = $this->getDoctrine()->getRepository('MADExperienceBundle:Experience')->find($experienceId);

        return $this->render('MADExperienceBundle:MyExperiences:read_experience.html.twig', array(
            'experience' => $experience,
        ));

    }

    public function answerQuestionAction($questionId, Request $request)
    {
        // TODO check if is a question for your group

        $question = $this->getDoctrine()->getRepository('MADExperienceBundle:Question')->find($questionId);

        $experience = new Experience();
        $experience->setQuestion($question);
        $experience->setUser($this->get('security.context')->getToken()->getUser());

        $form = $this->createForm(new ExperienceType(), $experience);

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {

                if (false !== strpos($request->get('share'), 'todas')) $experience->setSharedWithAll(true);

                $em = $this->getDoctrine()->getManager();
                $em->persist($experience);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Experiencia guardada!'
                );
            }
        }

        return $this->render('MADExperienceBundle:MyExperiences:answer_question.html.twig', array(
            'form' => $form->createView(),
            'question' => $question,
        ));
    }

}
