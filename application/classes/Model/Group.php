<?php defined('SYSPATH') or die('No direct script access.');

class Model_Group extends ORM
{
    protected $_belongs_to = array(
        'room' => array(
            'foreign_key' => 'room_id',
        ),
    );

    public function endgame($death = false) {
        if ($this->status != 1) {
            $this->status = 1;
            $this->finished = time();
            if ($death) $this->finished += 3600;
        }
        $this->time = $this->finished - $this->start;
        $this->save();
    }

    public function archive() {
        if ($this->people>1) {
            $this->status = 0;
            $this->save();
        } else {
            $this->delete();
        }
    }
}
