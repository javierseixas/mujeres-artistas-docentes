<?php

namespace MAD\ExperienceBundle\Controller;

use MAD\ExperienceBundle\Entity\Question;
use MAD\ExperienceBundle\Entity\Subject;
use MAD\ExperienceBundle\Form\Type\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
                    //$this->get('mailer')->send($message);
                }

            }
        }

        return $this->render('MADExperienceBundle:Home:ask_experience.html.twig', array(
            'form' => $form->createView(),
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

    public function setSubjectsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $subject = new Subject();
        $subject->setTitle('Antes que todo soy mujer');
        $em->persist($subject);

        $subject = new Subject();
        $subject->setTitle('Soy mujer artista');
        $em->persist($subject);

        $subject = new Subject();
        $subject->setTitle('Soy mujer docente');
        $em->persist($subject);

        $subject = new Subject();
        $subject->setTitle('Soy mujer artista docente');
        $em->persist($subject);

        $em->flush();
    }

    private function getTeachers($groups)
    {
        $users = array();
        foreach ($groups as $group) {
        }

    }
}
