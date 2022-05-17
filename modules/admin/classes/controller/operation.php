<?php

namespace Admin;


class Controller_Operation extends \Controller_Base_Admin {

	public $title = 'Opérations CRUD';

	public function before() {

		if ( ! \Auth::has_access('admin.list[modify]')) {
			\Message::success('Vous n\'avez pas les autorisations d\'accès.');
			\Response::redirect('admin/clients');
		}

		// \Theme::instance()->asset->css(array('dashboard.css'), array(), 'header', false);
		\Theme::instance()->asset->js(array('custom/dashboard.js'), array(), 'footer', false);

		return parent::before();

	}

	public function action_index() {
		$data['butdom_operations'] = Model_Butdom_Operation::find('all');

		$view = \Theme::instance()->view('admin/operation/index', $data);
		\Theme::instance()->get_template()->set('title', $this->title);
		\Theme::instance()->set_partial('content', $view);

	}

	public function action_view($id = null) {

		\Theme::instance()->asset->css(array('font-awesome.min.css'), array(), 'header', false);

		$operation = Model_Butdom_Operation::find($id);

		is_null($id) and \Response::redirect('admin/operation');

		if ( ! $data['butdom_operation'] = Model_Butdom_Operation::find($id)) {

			\Session::set_flash('error', 'Could not find butdom_operation #'.$id);
			\Response::redirect('admin/operation');
		}

		\Config::load('but_mclist');
		$departements 	= \Config::get('but_groups');
		
		$statsfetch = Model_Butdom_Operationmeta::query()->where('operation_id',$id)->get();

		foreach ($statsfetch as $stat) {

			$x = explode('_', $stat->key);
			$k = $x[0];
			$d = $x[1];

			// store desc stats keys
			$departements[$d]['stats'][$k] = $stat->value;

		}

		$data['statinfo'] = \Config::get('stats_jeux');
		$data['departements'] = $departements;
		$data['jeu'] = \Uri::base(false).'jeu'.DIRECTORY_SEPARATOR.$operation->name.DIRECTORY_SEPARATOR.$operation->departement;

		$view = \Theme::instance()->view('admin/operation/view', $data);
		\Theme::instance()->get_template()->set('title', $this->title);
		\Theme::instance()->set_partial('content', $view);

	}

	public function action_create() {

		if (\Input::method() == 'POST') {

			$operation_model 	= Model_Butdom_Operation::forge();
			$val 				= \Fieldset::forge('registration')->add_model($operation_model)->repopulate();

			if ($val->validation()->run()) {

				$butdom_operation = Model_Butdom_Operation::forge(array(
					'name' 			=> \Input::post('name'),
					'departement' 	=> \Input::post('departement'),
					'title' 		=> \Input::post('title'),
					'event' 		=> \Input::post('event'),
				));

				if ($butdom_operation and $butdom_operation->save()) {

					\Session::set_flash('success', 'Added butdom_operation #'.$butdom_operation->id.'.');

					\Response::redirect('admin/operation');
				} else {
					\Session::set_flash('error', 'Could not save butdom_operation.');
				}
			} else {
				\Session::set_flash('error', $val->error());
			}
		}

		$view = \Theme::instance()->view('admin/operation/create');
		$view->set('departements', Model_Butdom_Operation::get_departements_form());
		\Theme::instance()->get_template()->set('title', $this->title);
		\Theme::instance()->set_partial('content', $view);

	}

	public function action_edit($id = null) {

		is_null($id) and \Response::redirect('admin/operation');

		if ( ! $butdom_operation = Model_Butdom_Operation::find($id)) {
			\Session::set_flash('error', 'Could not find butdom_operation #'.$id);
			\Response::redirect('admin/operation');
		}

		// $val = Model_Butdom_Operation::validate('edit');
		$model = Model_Butdom_Operation::forge();
		$val = \Validation::forge('edit');
		$val->add_model($model);

		if ($val->run()) {
			$butdom_operation->name = \Input::post('name');
			$butdom_operation->departement = \Input::post('departement');
			$butdom_operation->title = \Input::post('title');
			$butdom_operation->event = \Input::post('event');
			$butdom_operation->event = \Input::post('event');

			if ($butdom_operation->save()) {
				\Session::set_flash('success', 'Updated butdom_operation #' . $id);

				\Response::redirect('admin/operation');
			} else {
				\Session::set_flash('error', 'Could not update butdom_operation #' . $id);
			}
		} else {
			if (\Input::method() == 'POST') {

				$butdom_operation->name = $val->validated('name');
				$butdom_operation->departement = $val->validated('departement');
				$butdom_operation->title = \Input::post('title');
				$butdom_operation->event = \Input::post('event');

				\Session::set_flash('error', $val->error());
			}

			\Theme::instance()->get_template()->set_global('butdom_operation', $butdom_operation, false);
		}

		$view = \Theme::instance()->view('admin/operation/edit');
		$view->set('departements', Model_Butdom_Operation::get_departements_form());
		\Theme::instance()->get_template()->set('title', $this->title);
		\Theme::instance()->set_partial('content', $view);

	}

	public function action_delete($id = null) {

		is_null($id) and \Response::redirect('admin/operation');

		if ($butdom_operation = Model_Butdom_Operation::find($id)) {
			$butdom_operation->delete();

			\Session::set_flash('success', 'Deleted butdom_operation #'.$id);
		} else {
			\Session::set_flash('error', 'Could not delete butdom_operation #'.$id);
		}

		\Response::redirect('admin/operation');

	}


}
