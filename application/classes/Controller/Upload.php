<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Upload extends Controller_Website {

    public function action_index()
    {

        if ($this->request->method() == Request::POST)
        {
            if (isset($_FILES['video']))
            {
                move_uploaded_file($_FILES['video']["tmp_name"], DOCROOT.'assets/video/videoGrupo.mp4');
            }
        }

        $this->template->title = 'Subir video';
        $this->template->content = View::factory('upload/index');

    }

} // End
