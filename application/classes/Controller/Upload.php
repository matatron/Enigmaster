<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Upload extends Controller_Website {

    public function action_index()
    {

        $message = "";
        if ($this->request->method() == Request::POST)
        {
            $message = "Datos recibidos: ".$_FILES['video']["name"];
            if (isset($_FILES['video']))
            {
                move_uploaded_file($_FILES['video']["tmp_name"], DOCROOT.'assets/video/videoGrupo.mp4');
                $message = "Video subido";
            }
        }

        $this->template->title = 'Subir video';
        $this->template->content = View::factory('upload/index')->bind("message", $message);

    }

} // End
