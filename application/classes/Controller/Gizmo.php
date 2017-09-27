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
                $gizmo->prevdata = $gizmo-data;
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
                if ($hasChanged) {
                    foreach($rules as $rule) {
                        if (isset($query[$rule->if]) &&  $query[$rule->if] == $rule->this) {
                            switch ($rule->then) {
                                case "progress":
                                    if ($group->progress < $rule->that) {
                                        $group->progress = $rule->that;
                                        $group->save();
                                    }
                                    break;
                            }
                        }
                    }
                }
                $this->response->body($group->progress);
            } else {
                $this->response->body('off');
            }
        }
    }

}
