<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Playerview extends Controller_Template {

    public $template = 'playerview';

    public function before()
    {
        parent::before();
        $this->template->title = '';
        $this->template->content = '';
        return;
    }

    public function action_index()
    {
        $roomId = $this->request->param('id');

        $room = ORM::factory('Room', $roomId);

        $group = ORM::factory('Group')
            ->where('room_id', '=', $roomId)
            ->limit(1)
            ->order_by('id', 'DESC')
            ->find();

        $puzzles =  json_decode($room->puzzles);
        $music = [];
        $sound = [];
        $lastMusic = '';
        foreach($puzzles as $puzzle) {
            if (isset($puzzle->music) && $puzzle->music!="" && $puzzle->music != $lastMusic) {
                $lastMusic = $puzzle->music;
            }
            $music[] = $lastMusic;
            $sound[] = (isset($puzzle->sound))? $puzzle->sound : '';
        }

        $this->template->roomId = $roomId;
        $view = $this->request->param('view', $group->room->view);
        if ($view == '') $view = 'basic';

        $this->template->bind('music', $music);
        $this->template->bind('sound', $sound);
        $this->template->content = View::factory('playerviews/'.$view)->bind('group', $group)->bind('roomId', $roomId);
    }

    public function action_lasttime()
    {
        $roomId = $this->request->param('id');

        if ($roomId) {
            $group = ORM::factory('Group')
                ->where('room_id', '=', $roomId)
                ->limit(1)
                ->order_by('finished', 'DESC')
                ->and_where('status', '<', 2    )
                ->find();
        } else{
            $group = ORM::factory('Group')
                ->limit(1)
                ->order_by('finished', 'DESC')
                ->and_where('status', '<', 2    )
                ->find();
        }

        $this->template->title = 'Ultimo tiempo';
        $this->template->content = View::factory('rooms/lasttime')->bind('group', $group);

    }

}
