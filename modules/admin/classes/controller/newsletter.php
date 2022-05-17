<?php

namespace Admin;

class Controller_Newsletter extends \Controller_Base_Admin {

	public function before() {

		\Theme::instance()->asset->css(array('list.css'), array(), 'header', false);
		// \Theme::instance()->asset->js(array('bootstrap3/alert.js'), array(), 'footer', false);

		parent::before();

	}
	
	public function action_index() {

		// if ( \Auth::has_access('admin.list[delete]')) {
	 //        echo 'you have access ...';
	 //    }
		$title = 'Newsletter Setup But Dom';

		\Theme::instance()->asset->js(array('custom/loterie.js'), array(), 'footer', false);
		// \Theme::instance()->asset->css(array('datepicker.css'), array(), 'header', false);
		// echo 'module : '.$this->request->module . '/ uri : ' . $this->request->uri . '/ action : ' . $this->request->action;
		// die();
		$view = \Theme::instance()->view($this->view);

		\Theme::instance()->get_template()->set('title', $title);
		\Theme::instance()->set_partial('content', $view);

	}



}
