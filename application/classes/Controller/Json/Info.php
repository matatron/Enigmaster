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
            ->and_where('status', '>', 0)
            ->limit(1)
            ->order_by('id', 'DESC')
            ->find();
        if ($group->loaded()) {
            $json = array(
                'status'=> intval($group->status),
                'start'=> $group->start*1000, //- $group->total_clues*$group->room->minPerClue*60000
                'time'=> $group->time*1000,
                'total_clues'=> intval($group->total_clues),
                'free_clues'=> intval($group->free_clues),
                'minutesxclue'=> intval($group->minutesxclue),
                'punishment'=> intval($group->punishment),
                'team_name'=> $group->team_name,
                'lang'=> $group->language,
                'clue'=> json_decode($group->clues)[0],
                'progress'=> intval($group->progress),
            );
        } else {
            $json = array(
                'status'=> 0,
                'start'=> 0,
                'time'=> 0,
                'total_clues'=> 0,
                'free_clues'=> 0,
                'minutesxclue'=> 0,
                'punishment'=> 0,
                'team_name'=> 0,
                'lang'=> 'es',
                'clue'=> "",
                'progress'=> 0
            );
        }
        $this->data = $json;
    }

    public function action_roompuzzles()
    {
        $roomId = $this->request->param('id');
        $group = ORM::factory('Group')
            ->where('room_id', '=', $roomId)
            ->and_where('status', '>', 0)
            ->limit(1)
            ->order_by('id', 'DESC')
            ->find();
        $json = null;
        if ($group->loaded()) {
            $json = array(
                'status'=> intval($group->status),
                'team_name'=> $group->team_name,
                'progress'=> intval($group->progress),
                'puzzles'=> []
            );
            $puzzles = json_decode($group->puzzles);
            foreach($puzzles as $p) {
                $json["puzzles"][] = $p->complete;
            }
        } else {
            $json = array(
                'status'=> 0,
                'team_name'=> 0,
                'progress'=> 0,
                'puzzles'=> []
            );
        }
        $this->data = $json;
    }

    public function action_statistics()
    {
        $roomId = $this->request->param('id');
        $groups = ORM::factory('Group')
            ->where('room_id', '=', $roomId)
            ->and_where('status', '=', 0)
            ->order_by('start', 'DESC')
            ->find_all();
        $json = array();
        $json["groups"] = [];
        $json["stats"] = [];
        $min = 3600;
        $max = 0;
        $count = 0;
        $clueSum = 0;
        $timeSum = 0;
        $peopleSum = 0;
        $winSum =0;

        foreach ($groups as $group)
        {
            $group->time = intval($group->time);
            $count++;
            $min = min($min, $group->time);
            $max = max($max, $group->time);
            $clueSum += $group->total_clues;
            $timeSum += $group->time;
            $peopleSum += $group->people;
            if ($group->time<3600) $winSum++;
            $json["groups"][] = array(
                'id' => $group->id,
                'start'=> $group->start*1000,
                'time'=> $group->time*1000,
                'people'=> $group->people,
                'total_clues'=> $group->total_clues,
                'team_name'=> $group->team_name,
                'team_type'=> $group->team_type
            );

        }
        $json["stats"]["min"] = $min;
        $json["stats"]["max"] = $max;
        $json["stats"]["count"] = $count;
        $json["stats"]["countPeople"] = $peopleSum;
        $json["stats"]["wins"] = $winSum;
        $json["stats"]["clueMean"] = $clueSum/$count;
        $json["stats"]["timeMean"] = $timeSum/$count;
        $json["stats"]["peopleMean"] = $peopleSum/$count;
        $this->data = $json;
    }
    public function action_deletegroup()
    {
        $groupId = $this->request->param('id');
        $group = ORM::factory('Group', $groupId);
        $roomId =$group->room_id;
        $group->delete();
        $this->data = [1];
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

    public function action_progress()
    {
        $now = time();
        $roomId = $this->request->param('id');
        $gizmos = ORM::factory('Gizmo')->where('room', '=', $roomId)->find_all()->as_array();
        $group = ORM::factory('Group')
            ->where('room_id', '=', $roomId)
            ->and_where('status', '>', 0)
            ->limit(1)
            ->order_by('id', 'DESC')
            ->find();

        $json = array();
        $json["gizmos"] = array();
        $json["puzzles"] = array();
        $json["status"] = $group->status;
        if ($group->loaded()) {
            $json["progress"] = $group->progress;
            foreach(json_decode($group->puzzles) as $pid => $puzzle) {
                $json["puzzles"][$pid] = intval($puzzle->complete);
            }
        } else {
            $json["progress"] = "off";
        }
        foreach ($gizmos as $gizmo)
        {
            $json["gizmos"][$gizmo->id] = array(
                'active'=> ($gizmo->lastActive > $now-10),
                'data'=> json_decode($gizmo->data)
            );

        }
        $this->data = $json;
    }

    public function action_gizmo()
    {
        $uid = $this->request->param('id');
        $gizmo = ORM::factory('Gizmo')
            ->where('uid', '=', $uid)
            ->find();
        $this->data = $gizmo->as_array();
    }

    public function action_savegizmo()
    {
        $uid = $this->request->param('id');
        $gizmo = ORM::factory('Gizmo')
            ->where('uid', '=', $uid)
            ->find();
        $payload = json_decode($this->request->body(), true);
        $gizmo->values($payload);
        $gizmo->save();
        $this->data = $gizmo->as_array();
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
