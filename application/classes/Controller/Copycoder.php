<?php defined("SYSPATH") or die('No direct script access.');

class Controller_Copycoder extends Controller_Website {

    public function action_index()
    {

        $message = "";
        $path = "";
        if ($this->request->method() == Request::POST)
        {
            $message = "Imagen recibidos: ".$_FILES["image"]["name"];
            if (isset($_FILES["image"]))
            {
                move_uploaded_file($_FILES["image"]["tmp_name"], 
                                   DOCROOT."assets/copycoder/original/".$_FILES["image"]["name"]);
                $message = "Imagen subida";
                header("Location: /copycoder/?path=".$_FILES["image"]["name"]);
                exit();
            }
        }
        if ($this->request->query('path')) {
            $path = "/assets/copycoder/original/".$this->request->query('path');
        }

        $this->template->title = "Subir imagen";
        $this->template->content = View::factory("copycoder/index")->bind("message", $message)->bind("path", $path);

    }

    public function action_codify() {
        if ($_POST) {
            $path = $_POST["path"];
            $pixels = (int)$_POST["pixels"];
            if ($pixels < 10) $pixels = 10;
            $newpath = str_replace("copycoder/original", "copycoder/coded", $path);

            $info   = getimagesize(DOCROOT.$path);
            $mime   = $info['mime']; // mime-type as string for ex. "image/jpeg" etc.
            $width  = $info[0];      // width as integer for ex. 512
            $height = $info[1];

            if ($mime == "image/jpeg") {
                $origen = imagecreatefromjpeg(DOCROOT.$path);
            }
            if ($mime == "image/png") {
                $origen = imagecreatefrompng(DOCROOT.$path);
            }
            $rotar = imagerotate($origen, 40, 16777215);
            $newwidth = imagesx($rotar);
            $newheight = imagesy($rotar);
            $canvas = imagecreatetruecolor(($newwidth), $newheight);
            for ($i = 0; $i < $newwidth; $i++) {
                for ($j = 0; $j < $newheight; $j++) {
                    $mod = $i % $pixels;
                    $limit = (floor($i/$pixels)+1)*$pixels-1;
                    $newX = min($limit - $mod, $newwidth-1);
                    imagesetpixel($canvas, $i, $j, imagecolorat($rotar, $newX, $j));
                }
            }
            $rotar = imagerotate($canvas, -40, 16777215);

            $newwidth = imagesx($rotar);
            $newheight = imagesy($rotar);

            imagecopyresampled($origen, $rotar, 0, 0, ($newwidth-$width)/2, ($newheight-$height)/2, $width, $height, $width, $height);
            //imageresolution ($origen, round($pixels*7.257), round($pixels*7.257));
            if ($mime == "image/jpeg") {
                imagejpeg($origen, DOCROOT.$newpath);
            }
            if ($mime == "image/png") {
                imagepng($origen, DOCROOT.$newpath);
            }

            echo $newpath;
            imagedestroy($canvas);
            imagedestroy($origen);
            imagedestroy($rotar);
        }

        die();
    }

} // End
