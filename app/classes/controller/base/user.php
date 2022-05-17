<?php

class Controller_Base_User extends Controller_Base_Template {

	public function before() {

		if ( ! Auth::check() and ! Auth::guest_login() ) {

			\Message::danger('Accès refusé. Connectez-vous');
			\Response::redirect('/users/login');
			
		}

		parent::before();
	}

}
