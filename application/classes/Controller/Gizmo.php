<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Gizmo extends Controller {

    public function action_report()
    {
        $gizmoId = $this->request->param('id');
        $data = json_encode($this->request->query());
        $gizmo = ORM::factory('Gizmo')->where('uid', '=', $gizmoId)->find();
        if ($gizmo->loaded()) {
            $gizmo->lastActive = time();
            $gizmo->data = $data;
            $gizmo->save();
        }else{
            $gizmo = ORM::factory('Gizmo');
            $gizmo->uid = $gizmoId;
            $gizmo->name = 'Gizmo '.$gizmoId;
            $gizmo->description = '';
            $gizmo->data = $data;
            $gizmo->lastActive = time();
            $gizmo->save();
        }
        if($gizmo->room) {
            $group = ORM::factory('Group')
                ->where('room_id', '=', $gizmo->room)
                ->and_where('status', '>', 0)
                ->find();
            if ($group->loaded()) {
                $this->response->body($group->progress);
            } else {
                $this->response->body('off');
            }
        }
    }

}
