<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Cuarto extends Controller_Website {

    public function action_ready()
    {
        $roomId = $this->request->param('id');
        $room = ORM::factory('Room', $roomId);
        $groups = ORM::factory('Group')
            ->where('room_id', '=', $roomId)
            ->and_where('status', '>', 0)
            ->find();
        if (!$groups->loaded()) {
            //there are no groups
            $newGroup = ORM::factory('Group');
            $newGroup->date = time();
            $newGroup->room_id = $roomId;
            $newGroup->status = 3; //currently, ready room is 3, playing is 2, done is 1, archived is 0
            $newGroup->people = 1;
            $newGroup->puzzles = $room->puzzles;
            $newGroup->team_name = 'Cuarto '.$roomId;
            $newGroup->team_type = 'Normal';
            $newGroup->save();
        }
        header('Location: /');
        exit();
    }

    public function action_modificar()
    {
        $roomId = $this->request->param('id');
        $room = ORM::factory('Room', $roomId);
        $this->template->content = View::factory('rooms/editroom')->bind('room', $room);

    }
    public function action_checklist()
    {
        $roomId = $this->request->param('id');
        $room = ORM::factory('Room', $roomId);
        $this->template->content = View::factory('rooms/checklist')->bind('room', $room);

    }
    public function action_statistics()
    {
        $roomId = $this->request->param('id');
        $room = ORM::factory('Room', $roomId);
        $this->template->content = View::factory('rooms/statistics')->bind('roomId',$roomId)->bind('room', $room);

    }
}
