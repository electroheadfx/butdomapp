<?php

namespace Users;

class Controller_Logout extends \Controller_Base_User {

	public function action_index() {

		\Auth::logout();
		\Session::delete('state');
		\Message::success('Déconnection réussie');
		\Response::redirect('/login');
	}

}
