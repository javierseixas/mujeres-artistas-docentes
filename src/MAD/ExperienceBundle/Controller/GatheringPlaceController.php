<?php

namespace MAD\ExperienceBundle\Controller;

use MAD\ExperienceBundle\Entity\Question;
use MAD\ExperienceBundle\Form\Type\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MAD\ExperienceBundle\Entity\Experience;
use MAD\ExperienceBundle\Form\Type\ExperienceType;;

class GatheringPlaceController extends Controller
{
    public function homeAction()
    {
        return $this->render('MADExperienceBundle:Home:index.html.twig');
    }

    public function myExperiencesAction()
    {

        $freeExperiences = $this->getDoctrine()->getRepository('MADExperienceBundle:Experience')->findUserFreeExperiences($this->get('security.context')->getToken()->getUser()->getId());

        return $this->render('MADExperienceBundle:Home:my_experiences.html.twig', array(
            'freeExperiences' => $freeExperiences,
        ));
    }

    public function writeExperienceAction()
    {
    	$experience = new Experience();

    	$form = $this->createForm(new ExperienceType(), $experience);

        $request = $this->getRequest();

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {
                $experience->setUser($this->get('security.context')->getToken()->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($experience);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Experiencia guardada!'
                );
            }
        }

        return $this->render('MADExperienceBundle:Home:write_experience.html.twig', array(
        	'form' => $form->createView()
        ));
    }

    public function readExperienceAction($experienceId)
    {
        // TODO Check if current user is researcher or if experience is shared with all

        $experience = $this->getDoctrine()->getRepository('MADExperienceBundle:Experience')->find($experienceId);

        return $this->render('MADExperienceBundle:Home:read_experience.html.twig', array(
            'experience' => $experience,
        ));

    }

    public function askExperienceAction()
    {
        // TODO Check if current user is researcher

        $question = new Question();

        $form = $this->createForm(new QuestionType(), $question);

        $request = $this->getRequest();

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($question);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Pregunta enviada!'
                );

                $teachers = $this->getDoctrine()->getRepository('MADUserBundle:User')->findUserByRole('ROLE_TEACHER');


                foreach ($teachers as $teacher) {
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Ross te pregunta sobre: '.$question->getTitle())
                        ->setFrom('experiencias@mujeresartistasdocentes.org')
                        ->setTo($teacher->getEmail())
                        ->setBody(
                            $this->renderView(
                                'MADExperienceBundle:Email:notice_new_question.html.twig',
                                array(
                                    'name' => $teacher->getUsername(),
                                    'question' => $question,
                                )
                            )
                        )
                        ->setContentType("text/html")
                    ;
                    $this->get('mailer')->send($message);
                }

            }
        }

        return $this->render('MADExperienceBundle:Home:ask_experience.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function answerQuestionAction($questionId)
    {
        $question = $this->getDoctrine()->getRepository('MADExperienceBundle:Question')->find($questionId);

        $experience = new Experience();
        $experience->setQuestion($question);

        $form = $this->createForm(new ExperienceType(), $experience);

        $request = $this->getRequest();

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {
                $experience->setUser($this->get('security.context')->getToken()->getUser());
                $em = $this->getDoctrine()->getManager();
                $em->persist($experience);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'notice',
                    'Experiencia guardada!'
                );
            }
        }

        return $this->render('MADExperienceBundle:Home:answer_question.html.twig', array(
            'form' => $form->createView(),
            'question' => $question,
        ));
    }
}
