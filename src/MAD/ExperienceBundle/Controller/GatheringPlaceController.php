<?php

namespace MAD\ExperienceBundle\Controller;

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
        return $this->render('MADExperienceBundle:Home:my_experiences.html.twig');
    }

    public function writeExperienceAction()
    {
    	$experience = new Experience();

    	$form = $this->createForm(new ExperienceType(), $experience);

        return $this->render('MADExperienceBundle:Home:write_experience.html.twig', array(
        	'form' => $form->createView()
        ));
    }
}
