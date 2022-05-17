<?php

namespace Frontend;

class Controller_404 extends \Controller_Base_Public {

	
	public function action_index() {
		

		$messages = array('Mince !', 'Olala !', 'Uh Oh!', 'Non, c\'est pas ici.', 'Hmmm ?');
		$this->title = $messages[array_rand($messages)];
		$title = 'Erreur 404';

		\Theme::instance()->get_template()->set('title', $title);
		$view = \Theme::instance()->view('frontend/404');
		\Theme::instance()->set_partial('content', $view )->set('title', $messages[array_rand($messages)]);

	}

	public function action_expiration() {

		$title = 'Fin de l\'opération';
		\Theme::instance()->get_template()->set('title', $title);
		$view = \Theme::instance()->view('frontend/404');
		\Theme::instance()->set_partial('content', $view )->set('title', $title);

	}



}
