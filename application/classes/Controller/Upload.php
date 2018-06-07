<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Upload extends Controller_Website {

    public function action_index()
    {

        if ($this->request->method() == Request::POST)
        {
            var_dump($_FILES);
            if (isset($_FILES['video']))
            {
                rename($_FILES['video']["tmp_name"], DOCROOT.'assets/video/videoGrupo.mp4');
            }
        }

        $this->template->title = 'Subir video';
        $this->template->content = View::factory('upload/index');

    }

} // End
