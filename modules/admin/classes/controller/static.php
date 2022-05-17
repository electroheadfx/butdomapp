<?php

namespace Admin;

class Controller_Static extends \Controller_Base_User {
	
	public function action_support() {

		$title = 'Support';
		\Theme::instance()->get_template()->set('title', $title);
		
		\Theme::instance()->asset->css(array('support.css'), array(), 'header', false);

		$view = \Theme::instance()->view('admin/static/support');
		\Theme::instance()->set_partial('content', $view );

	}

}
