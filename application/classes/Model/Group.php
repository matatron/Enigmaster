<?php defined('SYSPATH') or die('No direct script access.');

class Model_Group extends ORM
{
	protected $_belongs_to = array(
		'room' => array(
		'foreign_key' => 'room_id',
	),
	);
}
