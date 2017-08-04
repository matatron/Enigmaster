<?php defined('SYSPATH') or die('No direct script access.');

class Controller_System extends Controller_Website {

	public function action_skin()
	{

		$skin = $this->request->param('id');
		$session = Session::instance()->set('skin', $skin);
		header('Location: '.$this->request->referrer());
		exit();
	}

} // End
