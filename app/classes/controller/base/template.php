<?php


class Controller_Base_Template extends Controller_Hybrid {
	
	public $template = 'templates/layout';

	public $date_format = 'eu';

	public $view;

	public $theme;

	public function before() {

		if (\Input::is_ajax()) {
			return parent::before();
		}

		$this->view 	= $this->request->module . '/' . $this->request->uri . '/' . $this->request->action;
		$this->theme 	= $this->request->module . '/' . $this->request->uri . '/';

		// define the theme template to use for this page, set a default if needed
		\Theme::instance()->set_template($this->template);

		// define the navbar partial and add the navbar data
		// \Theme::instance()->set_partial('navbar', 'templates/navbar')->set('navitems', $this->navbar);
		// setup the chrome body for content
		// \Theme::instance()->set_chrome('content', 'chrome/cms', 'body');

	}

	/**
	 * After controller method has run, render the theme template
	 *
	 * @param  Response  $response
	 */

	public function after($response) {

		\Theme::instance()->get_template()->set('messages', \Message::get(), false);

		if ( ! \Input::is_ajax()) {

			// If nothing was returned set the theme instance as the response
			if (empty($response)) {
				$response = \Response::forge(\Theme::instance());
			}

			if ( ! $response instanceof Response) {
				$response = \Response::forge($response);
			}

			return $response;
		}



		return parent::after($response);
	}

}
