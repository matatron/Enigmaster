<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Gizmo extends Controller {

    public function action_report()
    {
        $hasChanged = false;
        $query = $this->request->query();
        $gizmoId = $this->request->param('id');
        $data = json_encode($query);
        $gizmo = ORM::factory('Gizmo')->where('uid', '=', $gizmoId)->find();
        if ($gizmo->loaded()) {
            $gizmo->lastActive = time();
            if ($data != $gizmo->data) {
                $gizmo->prevdata = $gizmo->data;
                $gizmo->data = $data;
                $hasChanged = true;
            }
            $gizmo->save();
        }else{
            $hasChanged = true;
            $gizmo = ORM::factory('Gizmo');
            $gizmo->uid = $gizmoId;
            $gizmo->name = 'Gizmo '.$gizmoId;
            $gizmo->description = '';
            $gizmo->data = $data;
            $gizmo->lastActive = time();
            $gizmo->save();
        }

        $rules = json_decode($gizmo->ifttt);

        if($gizmo->room) {
            $group = ORM::factory('Group')
                ->where('room_id', '=', $gizmo->room)
                ->and_where('status', '>', 0)
                ->find();
            if ($group->loaded()) {
                if ($group->status == 3) { // not started
                    //$this->response->body("0\r");
                    echo "onn\r";
                } else {
                    if ($hasChanged) {
                        foreach($rules as $rule) {
                            if (isset($query[$rule->if]) &&  strtolower($query[$rule->if]) == strtolower($rule->this)) {
                                switch (strtolower($rule->then)) {
                                    case "progress":
                                    case "progreso":
                                        $puzzles = json_decode($group->puzzles);
                                        $puzzles[$rule->that-1]->complete = 1;
                                        $group->puzzles = json_encode($puzzles);
                                        if ($group->progress < $rule->that) {
                                            $group->progress = $rule->that;
                                        }
                                        $group->save();
                                        break;
                                    case "endgame":
                                        $group->endgame(($rule->that == "lost"));
                                        break;
                                    case "punish":
                                        $group->punish += $rule->that;
                                        break;

                                }
                            }
                        }
                    }
                    //$this->response->body($group->progress."\r");
                    echo $group->progress." \r";
                }
            } else {
                //$this->response->body("off\r");
                echo "off\r";
            }
        }
        die();
    }

    public function action_reportbin()
    {
        $hasChanged = false;
        $query = $this->request->query();
        $gizmoId = $this->request->param('id');
        $data = json_encode($query);
        $gizmo = ORM::factory('Gizmo')->where('uid', '=', $gizmoId)->find();
        if ($gizmo->loaded()) {
            $gizmo->lastActive = time();
            if ($data != $gizmo->data) {
                $gizmo->prevdata = $gizmo->data;
                $gizmo->data = $data;
                $hasChanged = true;
            }
            $gizmo->save();
        }else{
            $hasChanged = true;
            $gizmo = ORM::factory('Gizmo');
            $gizmo->uid = $gizmoId;
            $gizmo->name = 'Gizmo '.$gizmoId;
            $gizmo->description = '';
            $gizmo->data = $data;
            $gizmo->lastActive = time();
            $gizmo->save();
        }

        $rules = json_decode($gizmo->ifttt);

        if($gizmo->room) {
            $group = ORM::factory('Group')
                ->where('room_id', '=', $gizmo->room)
                ->and_where('status', '>', 0)
                ->find();
            if ($group->loaded()) {
                if ($group->status == 3) { // not started
                    //$this->response->body("0\r");
                    echo "on\r";
                } else {
                    if ($hasChanged) {
                        foreach($rules as $rule) {
                            if (isset($query[$rule->if]) &&  $query[$rule->if] == $rule->this) {
                                switch ($rule->then) {
                                    case "progress":
                                    case "progreso":
                                        $puzzles = json_decode($group->puzzles);
                                        $puzzles[$rule->that-1]->complete = 1;
                                        $group->puzzles = json_encode($puzzles);
                                        if ($group->progress < $rule->that) {
                                            $group->progress = $rule->that;
                                        }
                                        $group->save();
                                        break;
                                    case "endgame":
                                        $group->endgame(($rule->that == "lost"));
                                        break;
                                    case "punish":
                                        $group->punish += $rule->that;
                                        break;

                                }
                            }
                        }
                    }
                    $puzzles = json_decode($group->puzzles);
                    $s = "";
                    foreach($puzzles as $p) {
                        $s .= 0+$p->complete;
                    }
                    echo $s."\r";
                }
            } else {
                //$this->response->body("off\r");
                echo "off\r";
            }
        }
        die();
    }

    public function action_config()
    {
        $gizmoId = $this->request->param('id');
        $gizmo = ORM::factory('Gizmo')->where('uid', '=', $gizmoId)->find();
        if (!$gizmo->loaded()) {
            $gizmo = ORM::factory('Gizmo');
            $gizmo->uid = $gizmoId;
            $gizmo->name = 'Gizmo '.$gizmoId;
            $gizmo->description = '';
            $gizmo->config = '';
            $gizmo->data = '[]';
            $gizmo->lastActive = time();
            $gizmo->save();
        }

        echo $gizmo->config."\r";
    }

}
