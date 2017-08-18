<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Json_Info extends Controller_Json {

    public function action_room()
    {
        $roomId = $this->request->param('id');
        $room = ORM::factory('Room', $roomId);
        $this->data = $room->as_array();
    }

    public function action_roomcompact()
    {
        $roomId = $this->request->param('id');
        $group = ORM::factory('Group')
            ->where('room_id', '=', $roomId)
            ->limit(1)
            ->order_by('id', 'DESC')
            ->find();
        $json = array(
            'status'=> $group->status,
            'end'=> $group->end*1000, //- $group->total_clues*$group->room->minPerClue*60000
            'time'=> $group->time*1000,
            'total_clues'=> $group->total_clues,
            'team_name'=> $group->team_name,
            'clue'=> json_decode($group->clues)[0],
        );
        $this->data = $json;
    }

    public function action_statistics()
    {
        $roomId = $this->request->param('id');
        $groups = ORM::factory('Group')
            ->where('room_id', '=', $roomId)
            ->and_where('status', '=', 0)
            ->find_all();
        $json = array();
        foreach ($groups as $group)
        {
            $json[] = array(
                'start'=> $group->start*1000,
                'time'=> $group->time*1000,
                'people'=> $group->people,
                'total_clues'=> $group->total_clues,
                'team_name'=> $group->team_name,
                'team_type'=> $group->team_type
            );

        }
        $this->data = $json;
    }

    public function action_group()
    {
        $roomId = $this->request->param('id');
        $group = ORM::factory('Group')
            ->where('room_id', '=', $roomId)
            ->limit(1)
            ->order_by('id', 'DESC')
            ->find();
        $group->room->loaded();
        $this->data = $group->as_array();
    }

    public function action_saveroom()
    {
        $roomId = $this->request->param('id');
        $room = ORM::factory('Room',$roomId);
        $payload = json_decode($this->request->body(), true);
        $room->values($payload);

        if ( $room->check() )  //Validation Check
        {
            if ( $room->save() )
            {
                $this->data = array("status"=>"success");
            }
            else
            {
                $this->data = array("status"=>"saveerror");
            }
        }
        else
        {
            $this->data = array("status"=>"novalidate");
        }

    }
    public function action_savegroup()
    {
        $roomId = $this->request->param('id');
        $group = ORM::factory('Group')
            ->where('room_id', '=', $roomId)
            ->limit(1)
            ->order_by('id', 'DESC')
            ->find();
        $payload = json_decode($this->request->body(), true);
        $group->values($payload);

        if ( $group->check() )  //Validation Check
        {
            if ( $group->save() )
            {
                $this->data = array("status"=>"success");
            }
            else
            {
                $this->data = array("status"=>"saveerror");
            }
        }
        else
        {
            $this->data = array("status"=>"novalidate");
        }
    }

    public function action_gizmos()
    {
        $now = time();
        $roomId = $this->request->param('id');
        $gizmos = ORM::factory('Gizmo')->where('room', '=', $roomId)->find_all()->as_array();
        $json = array();
        foreach ($gizmos as $gizmo)
        {
            $json[$gizmo->id] = array(
                'active'=> ($gizmo->lastActive > $now-10),
                'data'=> json_decode($gizmo->data)
            );

        }
        $this->data = $json;

    }

    public function action_savepostgroup()
    {
        $groupId = $this->request->param('id');
        $group = ORM::factory('Group', $groupId);
        $payload = json_decode($this->request->body(), true);
        $group->values($payload);
        if ( $group->check() )  //Validation Check
        {
            if ( $group->save() )
            {
                $this->data = array("status"=>"success");
            }
            else
            {
                $this->data = array("status"=>"saveerror");
            }
        }
        else
        {
            $this->data = array("status"=>"novalidate");
        }
    }


}
