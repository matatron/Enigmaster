<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Json extends Controller {

	public $data = null;

	public function before()
	{
		$this->response->headers('Content-Type', 'application/json; charset=utf-8');
	}

	public function after()
	{
		$this->response->body(json_encode($this->data));
	}
}
