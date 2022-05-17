<?php

namespace Users;

class Controller_Login extends \Controller_Base_Public {

	public static $login_redirect = 'admin/dashboard';
	// public static $linked_redirect = '/';
	// public static $register_redirect = '/users/register';
	// public static $registered_redirect = '/users/profile';

	public function before() {

		// already logged in?
		if (\Auth::check()) {

			\Message::danger('Vous êtes déjà connecté');
			\Response::redirect('/');

		}

		parent::before();
	}

	public function action_index() {

		$title = 'Log In';

		if ( ! \Auth::check()) {

			$fieldset = \Fieldset::forge('login');

			$fieldset->add('username', 'Utilisateur', array('class' => 'form-control'), array('maxlength' => 50), array(array('required')))
					 ->add('password', 'Mot de passe', array('class' => 'form-control', 'type' => 'password'), array('type' => 'password', 'maxlength' => 255), array(array('required')));

			$form = $fieldset->form();
	   		$form->add('submit', '', array('type' => 'submit', 'value' => 'Envoyez', 'class' => 'btn btn-primary login'));
	   		// en multilangue on fera 'value' => \Lang::get('login')

			if (\Input::post()) {
				
				if ( ! $fieldset->validation()->run()) {

					\Message::danger('Des erreurs sont survenues, veuillez corriger les champs correspondants :');

				} else {

					// create an Auth instance
					$auth = \Auth::instance();

					// check the credentials.
					if ($auth->login(\Input::param('username'), \Input::param('password'))) {

						\Message::success('Bonjour ' . \Auth::get_screen_name() . ', vous vous êtes connecté avec succès !');

						\Response::redirect(static::$login_redirect);

					} else {

						\Message::danger('Le nom d\'utilisateur ou/et le mot de passe sont incorrect(s)');
						\Response::redirect('login');

					}
				}
				
			}

			\Theme::instance()->get_template()->set('title', $title);
			
			// set the login page content partial
			\Theme::instance()->set_partial('content', 'users/login/index')->set('form', $form->build(), false);

		}

	}

}
