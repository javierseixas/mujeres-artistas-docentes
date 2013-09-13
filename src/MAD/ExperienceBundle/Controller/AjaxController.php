<?php

namespace MAD\ExperienceBundle\Controller;

use MAD\ExperienceBundle\Entity\Question;
use MAD\ExperienceBundle\Form\Type\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MAD\ExperienceBundle\Entity\Experience;
use MAD\ExperienceBundle\Form\Type\ExperienceType;
use Symfony\Component\HttpFoundation\JsonResponse;

;

class AjaxController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function uploadImageAction()
    {
        $uploaddir = __DIR__.'/../../../../web/uploads/';
        $uploadfile = $uploaddir . basename($_FILES['file']['name']);

        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
//            echo "File is valid, and was successfully uploaded.\n";
        }
        return new JsonResponse(array('filelink' => '/uploads/'.basename($_FILES['file']['name'])));
    }

}
