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
        $user = $this->get('security.context')->getToken()->getUser();

        $path = __DIR__ . '/../../../../web/media/cache/resized_avatar/uploads/avatars/previews/';
//        $path = __DIR__ . '/../../../../web/uploads/avatars/previews/';
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

            imagejpeg($dst_r, $newImagePath.$user->getUsername().'.jpg',$jpeg_quality);

            return $this->redirect($this->generateUrl('mad_experience_homepage'));
        }

    }

    public function uploadAvatarPreviewAction()
    {
        $path = __DIR__ . '/../../../../web/uploads/avatars/previews/';
        $valid_formats = array("jpg", "jpeg");
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

                            $imagemanagerResponse = $this->container
                                ->get('liip_imagine.controller')
                                ->filterAction(
                                    $this->getRequest(),
                                    'uploads/avatars/previews/'.$actual_image_name,      // original image you want to apply a filter to
                                    'resized_avatar'              // filter defined in config.yml
                                );

                            $cacheManager = $this->container->get('liip_imagine.cache.manager');
                            $srcPath = $cacheManager->getBrowserPath('uploads/avatars/previews/'.$actual_image_name, 'resized_avatar');

                            $result = "<img src='".$srcPath."' id='preview-avatar'  class='preview' />";
//                            $result = "<img src='/uploads/avatars/previews/".$actual_image_name."' id='preview-avatar'  class='preview'>";

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
