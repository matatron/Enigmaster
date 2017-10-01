<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller_Website {

    public function action_index()
    {

        $rooms = ORM::factory('Room')->find_all()->as_array();

        foreach($rooms as &$room) {
            $group = ORM::factory('Group')
                    ->where('room_id', '=', $room->id)
                    ->and_where('status', '>', 0)
                    ->limit(1)
                    ->order_by('id', 'DESC')
                    ->find();
            $room->group = $group;
        }

        $this->template->title = 'Cuartos';
        $this->template->content = View::factory('rooms/list')->bind('rooms', $rooms);

    }

    public function action_pregame()
    {
        $roomId = $this->request->param('id');
        $group = ORM::factory('Group')
            ->where('room_id', '=', $roomId)
            ->and_where('status', '>', 0)
            ->find();
        $this->template->title = 'Room '.$roomId;
        $this->template->content = View::factory('rooms/pregame')->bind('group', $group)->bind('roomId', $roomId);

    }

    public function action_postgame()
    {
        $groupId = $this->request->param('id');
        $group = ORM::factory('Group', $groupId);
        $group->room->loaded();
        $this->template->title = 'Post juego';
        $this->template->content = View::factory('rooms/postgame')->bind('group', $group);

    }

    public function action_game()
    {
        $roomId = $this->request->param('id');
        $group = ORM::factory('Group')
            ->where('room_id', '=', $roomId)
            ->and_where('status', '>', 0)
            ->find();
        $gizmos = ORM::factory('Gizmo')
            ->where('room', '=', $roomId)
            ->order_by('name', 'asc')
            ->find_all();
        if (!$group->loaded()) {
            header('Location: /');
            exit();
        }
        $this->template->title = 'Room '.$roomId;
        $this->template->content = View::factory('rooms/game')
            ->bind('group', $group)
            ->bind('gizmos', $gizmos)
            ->bind('roomId', $roomId);

    }

    public function action_finish()
    {
        $roomId = $this->request->param('id');
        $group = ORM::factory('Group')
            ->where('room_id', '=', $roomId)
            ->and_where('status', '>', 0)
            ->find();
        if ($group->loaded()) {
            $group->status = 0;
            $group->finished = time();
            $group->time = $group->finished - $group->start;
            $group->save();
            header('Location: /main/postgame/'.$group->id);
            exit();
        } else {
            header('Location: /');
            exit();
        }
    }

    public function action_gizmos() {
        $gizmos = ORM::factory("Gizmo")->find_all();
        $this->template->title = 'Gizmos';
        $this->template->content = View::factory('gizmos')
            ->bind('gizmos', $gizmos);

    }

} // End
