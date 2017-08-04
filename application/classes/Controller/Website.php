<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Website extends Controller_Template {

	public $template = 'site';

	public function before()
	{
		parent::before();
		$this->template->title = '';
		$this->template->content = '';
		return;
	}

}
