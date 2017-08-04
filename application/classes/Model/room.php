<?php defined('SYSPATH') or die('No direct script access.');


class Model_Room extends ORM
{
	public $group = null;

	protected $_has_many = array(
		'groups' => array(
		'model'       => 'Group',
		'foreign_key' => 'id',
	),
	);
}
