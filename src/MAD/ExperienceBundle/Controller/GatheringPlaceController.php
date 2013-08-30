<?php

namespace MAD\ExperienceBundle\Controller;

use MAD\ExperienceBundle\Entity\Question;
use MAD\ExperienceBundle\Form\Type\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MAD\ExperienceBundle\Entity\Experience;
use MAD\ExperienceBundle\Form\Type\ExperienceType;
use Symfony\Component\HttpFoundation\Request;
use MAD\UserBundle\Entity\Group;

class GatheringPlaceController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction()
    {
        return $this->render('MADExperienceBundle:Home:index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myExperiencesAction()
    {

        $subjectsList = $this->getDoctrine()->getRepository('MADExperienceBundle:Subject')->findAll();

        $freeExperiences = $this->getDoctrine()->getRepository('MADExperienceBundle:Experience')->findUserFreeExperiences($this->get('security.context')->getToken()->getUser()->getId());

        return $this->render('MADExperienceBundle:Home:my_experiences.html.twig', array(
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

        return $this->render('MADExperienceBundle:Home:questions.html.twig', array(
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

        return $this->render('MADExperienceBundle:Home:write_experience.html.twig', array(
        	'form' => $form->createView()
        ));
    }

    public function editExperienceAction(Request $request, $experienceId)
    {
        // TODO Check that is own experience or user is researcher

        $experience = $this->getDoctrine()->getRepository('MADExperienceBundle:Experience')->find($experienceId);

        $question = $this->getDoctrine()->getRepository('MADExperienceBundle:Question')->find($experience->getQuestion()->getId());

        $experience->setQuestion($question);

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

        return $this->render('MADExperienceBundle:Home:edit_experience.html.twig', array(
            'form' => $form->createView(),
            'question' => $question,
            'experience' => $experience
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

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     */
    public function askExperienceAction(Request $request)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_RESEARCHER')) {
            throw new AccessDeniedException();
        }

        $question = new Question();

        $form = $this->createForm(new QuestionType(), $question);

        if ($request->isMethod('POST')) {
            $form->submit($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                foreach ($question->getGroups() as $group) {
                    $group->addQuestion($question);
                }

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

    /**
     * @param integer $questionId
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

        return $this->render('MADExperienceBundle:Home:answer_question.html.twig', array(
            'form' => $form->createView(),
            'question' => $question,
        ));
    }

    public function setGroupsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $group = new Group();
        $group->setName('Artistas');
        $group->setRoles(array('ROLE_ARTIST'));
        $em->persist($group);

        $group = new Group();
        $group->setName('Docentes');
        $group->setRoles(array('ROLE_TEACHER'));
        $em->persist($group);

        $group = new Group();
        $group->setName('ArtistasDocentes');
        $group->setRoles(array('ROLE_ARTIST_TEACHER'));
        $em->persist($group);


        $em->flush();
    }
}
