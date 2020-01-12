<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Gizmo extends Controller {

    private function getGizmo($gizmoId) {
        $gizmo = ORM::factory('Gizmo')->where('uid', '=', $gizmoId)->find();
        if (!$gizmo->loaded()) {
            $gizmo = ORM::factory('Gizmo');
            $gizmo->uid = $gizmoId;
            $gizmo->name = 'Gizmo '.$gizmoId;
            $gizmo->description = '';
            $gizmo->prevdata = '';
            $gizmo->data = '';
            $gizmo->save();
        }
        $gizmo->lastActive = time();
        $gizmo->save();
        return $gizmo;
    }

    public function action_report()
    {
        $gizmoId = $this->request->param('id');

        $gizmo = $this->getGizmo($gizmoId);
        if($gizmo->room) {
            $group = ORM::factory('Group')
                ->where('room_id', '=', $gizmo->room)
                ->and_where('status', '>', 0)
                ->find();
            if ($group->loaded()) {
                if ($group->status == 3) { // not started
                    echo "onn\r";
                } else {
                    $this->checkRules($gizmo, $group);
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
        $message = '';
        $gizmoId = $this->request->param('id');
        $gizmo = $this->getGizmo($gizmoId);

        if($gizmo->room) {
            $group = ORM::factory('Group')
                ->where('room_id', '=', $gizmo->room)
                ->and_where('status', '>', 0)
                ->find();
            if ($group->loaded()) {
                if ($group->status == 3) { // not started
                    echo "onn\r";
                } else {
                    $this->checkRules($gizmo, $group);
                    $puzzles = json_decode($group->puzzles);
                    $s = "";
                    foreach($puzzles as $p) {
                        $s .= 0+$p->complete;
                    }
                    echo $s.$message."\r";
                }
            } else {
                //$this->response->body("off\r");
                echo "off\r";
            }
        }
        die();
    }

    public function action_reportjson()
    {
        $json = [
            "status" => 0,
            "puzzles" => ""
        ];
        $gizmoId = $this->request->param('id');
        $gizmo = $this->getGizmo($gizmoId);
        if($gizmo->room) {
            $group = ORM::factory('Group')
                ->where('room_id', '=', $gizmo->room)
                ->and_where('status', '>', 0)
                ->find();
            if ($group->loaded()) {
                $json["status"] = $group->status;
                $json["players"] = $group->people;
                if ($group->status == 2) { // not started
                    $json["times"] = $this->checkRules($gizmo, $group);
                    if ($gizmo->params) {
                        $json["params"] = new stdClass();
                        $requestedParams = preg_split('/[\,\ ]+/', $gizmo->params);
                        $groupParams = json_decode($group->params);
                        foreach($requestedParams as $p) {
                            $json["params"]->$p = (isset($groupParams->$p)) ? $groupParams->$p : 0;
                        }
                    }
                    $puzzles = json_decode($group->puzzles);
                    $s = "";
                    foreach($puzzles as $p) {
                        $s .= 0+$p->complete;
                    }
                    $json["puzzles"] = $s;
                }
            }
            echo json_encode($json);

            echo "\r";
        }
        die();
    }


    private function checkRules(&$gizmo, &$group) {
        $start = microtime(true);
        $times = [];

        $times[] = 1;
        //$times[] = microtime(true) - $start; //////////////////////////////////////
        $query = $this->request->query();
        $newData = json_encode($query);
        if ($gizmo->data !== $newData) {
            $gizmo->prevdata = $gizmo->data;
            $gizmo->data = $newData;


            $params = $group->params ? json_decode($group->params) : new stdClass();
            foreach($query as $key => $value) {
                $params->$key = $value;
            }
            $group->params = json_encode($params);

            $rules = json_decode($gizmo->ifttt);
            $times[] = 2;
            //$times[] = microtime(true) - $start; //////////////////////////////////////
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
                            break;
                        case "on":
                            $puzzles = json_decode($group->puzzles);
                            $puzzles[$rule->that-1]->complete = 0;
                            $group->puzzles = json_encode($puzzles);
                        case "off":
                            $puzzles = json_decode($group->puzzles);
                            $puzzles[$rule->that-1]->complete = 1;
                            $group->puzzles = json_encode($puzzles);
                            break;
                        case "endgame":
                            $group->endgame(($rule->that == "lost"));
                            break;
                        case "punish":
                            $group->punishment = intval($group->punishment) + intval($rule->that);
                            break;

                    }
                }
            }
            $times[] = 3;
            //$times[] = microtime(true) - $start; //////////////////////////////////////
            $group->save();
            $gizmo->save();
            $times[] = 4;
            //$times[] = microtime(true) - $start; //////////////////////////////////////
        }
        return $times;
    }

    public function action_config()
    {
        $gizmoId = $this->request->param('id');

        $gizmo = $this->getGizmo($gizmoId);

        echo $gizmo->config."\r";
    }

}
