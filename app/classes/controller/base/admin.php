<?php

class Controller_Base_Admin extends Controller_Base_Template {

	public function before() {

		$result = array();

		if (\Auth::get_groups()) {
			$group = \Arr::pluck(\Auth::get_groups(),1);
			$group_id = (int) $group[0]->id;

			if ( $group_id < 4) {
				$result = array(
					'message' => 'Vous avez besoin d\'être connecté pour accéder à cette page.',
					'url' => '/users/login',
				);
			}
		} else {
			\Message::danger('Connectez-vous pour accéder à l\'administration');
			\Response::redirect('login');
		}

		if ( ! empty($result)) {

			if (\Input::is_ajax()) {
				$this->response(array($result['message']), 403);
			} else {
				\Message::danger($result['message']);
				\Response::redirect($result['url']);
			}
		}

		parent::before();
	}
}
