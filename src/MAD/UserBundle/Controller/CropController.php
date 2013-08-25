<?php

namespace MAD\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class CropController extends Controller
{
    public function uploadCroppedAvatarAction()
    {
        $path = __DIR__ . '/../../../../web/uploads/avatars/previews/';
        $newImagePath = __DIR__ . '/../../../../web/uploads/avatars/';

        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $targ_w = $targ_h = 150;
            $jpeg_quality = 90;

            $src = $path.$_POST['fileName'];
            $img_r = imagecreatefromjpeg($src);
            $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

            imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
                $targ_w,$targ_h,$_POST['w'],$_POST['h']);

            header('Content-type: image/jpeg');
            imagejpeg($dst_r, $newImagePath.'prova.jpg',$jpeg_quality);

            return $this->redirect($this->generateUrl('mad_experience_homepage'));
        }

    }

    public function uploadAvatarPreviewAction()
    {
        $path = __DIR__ . '/../../../../web/uploads/avatars/previews/';
        $valid_formats = array("jpg", "png", "gif", "bmp");
        if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
        {
            $name = $_FILES['photoimg']['name'];
            $size = $_FILES['photoimg']['size'];

            if(strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                if(in_array($ext,$valid_formats)) {
                    if($size<(1024*1024)) {
                        $actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
                        $tmp = $_FILES['photoimg']['tmp_name'];
                        if(move_uploaded_file($tmp, $path.$actual_image_name))
                        {
                            // TODO resize de picture
                            $result = "<img src='/uploads/avatars/previews/".$actual_image_name."' id='preview-avatar'  class='preview'>";
                        }
                        else
                            $result = "failed";
                    }
                    else
                        $result = "Image file size max 1 MB";
                }
                else
                    $result = "Invalid file format..";
            }

            else
                $result = "Please select image..!";
        }

        return new JsonResponse(array(
            'image' => array(
                'html' => $result,
                'fileName' => $actual_image_name
            )
        ));
    }
}
