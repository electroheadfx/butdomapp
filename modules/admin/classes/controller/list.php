<?php

namespace Admin;

class Controller_List extends \Controller_Base_Admin {

	protected $status = array('all' => 'Tous','approved' => 'Actif', 'pending'=>'En cours', 'refused'=>'Rejet' );
	protected $buttonstatus = array('all' => 'primary', 'approved' => 'success', 'pending'=>'warning', 'refused'=>'danger' );

	public function before() {

		\Theme::instance()->asset->css(array('list.css'), array(), 'header', false);
		\Theme::instance()->asset->js(array('bootstrap3/modal.js', 'custom/delete.js'), array(), 'footer', false); // 'bootstrap3/alert.js'

		parent::before();

	}

	public function action_index() {


	}
	
	public function action_clients($departement = "all", $action = "", $page = 1) {

		$title 				= 'List But Dom';
		$ramdon 			= false;
		$search 			= false;
		$confirmed 			= 'approved';
		$ope_query = Model_Butdom_Operation::query();

		$operation_id = \Input::get('id');
		$data['operation_id'] = 0;

		if ($action == 'operation') {

			if (isset($ope_query) && isset($operation_id)) {
				$actual_ope = $ope_query->where('id', '=', $operation_id)->get_one();
				if (isset($actual_ope)) {
					$departement = ($actual_ope->departement == 'tous') ? 'all' : $actual_ope->departement;
					$data['actual_ope'] 	= $actual_ope;
					$data['operation_id'] 	= $actual_ope->id;
					$data['operation_name'] = $actual_ope->name;
					$data['operation_date'] = \Date::forge($actual_ope->created_at)->format("%d/%m/%Y");
					\Session::set('operation_id', 	$data['operation_id']);
					\Session::set('operation_name', $data['operation_name']);
					\Session::set('operation_date', $data['operation_date']);
				}
			}
			
		} else {
			\Session::delete('operation_id');
			\Session::delete('operation_name');
		}

		$data['operations'] = $ope_query->get();
		$data['startdate'] 	= '';
		$data['enddate'] 	= '';
		$data['search'] 	= '';
		$data['filter'] 	= 'name';
		$data['departement']= $departement;
		$data['page'] 		= $page;
		$data['action'] 	= $action;

		if (\Input::post()) {

			\Input::post('ramdon') 	and $ramdon = true;
			\Input::post('search') 	and $search = true;
			\Input::post('filter') 	and \Session::set('filter', \Input::post('filter'));
			\Input::post('action') 	and \Session::set('action', \Input::post('action'));
			
			$startdate 		= \Input::post('startdate');
			$enddate 		= \Input::post('enddate');
			$data['search'] = \Input::post('search');

			$confirmed = \Session::get('confirmed') ? \Session::get('confirmed') : "approved";

			\Input::post('confirmed') and $confirmed = \Input::post('confirmed');
			
			\Session::set('search', 	$data['search']);
			\Session::set('confirmed', 	$confirmed);
			\Session::set('startdate', 	$startdate);
			\Session::set('enddate', 	$enddate);
		}
		
		\Session::get('confirmed') 		and $confirmed 				= \Session::get('confirmed');
		\Session::get('startdate') 		and $data['startdate'] 		= \Session::get('startdate');
		\Session::get('enddate') 		and $data['enddate'] 		= \Session::get('enddate');
		\Session::get('search') 		and $data['search'] 		= \Session::get('search');
		\Session::get('filter') 		and $data['filter'] 		= \Session::get('filter');
		\Session::get('action') 		and $data['action'] 		= \Session::get('action');
		\Session::get('operation_name') and $data['operation_name'] = \Session::get('operation_name');
		\Session::get('operation_id') 	and $data['operation_id'] 	= \Session::get('operation_id');
		\Session::get('operation_date') and $data['operation_date'] = \Session::get('operation_date');
		
		$total = \DB::select(\DB::expr('COUNT(*) as result_count'))->from('butdom_clients');

		if ($departement == "all") {

			$order_by = array( 'departement' => 'asc', 'name' => 'asc');

			if ($confirmed == 'all') {

				$where_dep = array(array('confirmed','!=', 'refused'));
				$total = $total->where('confirmed', '!=', 'refused');

			} else {

				$where_dep = array(array('confirmed', $confirmed));
				$total = $total->where('confirmed', '=', $confirmed);
			}

		} else {

			$order_by = array( 'name' => 'asc');
			
			if ($confirmed == 'all') {

				$where_dep = array(array('confirmed','!=', 'refused'), array('departement', $departement));
				$total = $total->where('confirmed', '!=', 'refused')->where('departement', $departement);

			} else {

				$where_dep = array(array('confirmed', $confirmed), array('departement', $departement));
				$total = $total->where('confirmed', '=', $confirmed)->where('departement', $departement);

			}

		}

		// $total = (int) $total->execute()->get('result_count');

		$data['search'] != '' and $where_dep[] = array($data['filter'],'like', $data['search'].'%');			

		# Loto list
		if ($ramdon && $confirmed != 'refused' ) {

			# remove $invoices
			$invoices = \DB::select('client_id')->from('butdom_invoices')->distinct(true)->execute()->as_array();
			$loto_client_id = '';

			if ($invoices) {
				
				$startdate 	= $data['startdate'] ? \Date::create_from_string($startdate , "eu")->get_timestamp() : '';
				$enddate 	= $data['enddate'] ? \Date::create_from_string($enddate , "eu")->get_timestamp() : '';

				// query clients with invoices there
				$query = Model_Butdom_Client::query()->related('butdom_invoices', array('where' => array(array('client_id', 'in', $invoices)) ));
				# query with : $clients->related('butdom_parrains', array('where' => array(array('id', '>', 0)) ))->get()

				// query client with status or all
				$query = ($confirmed == 'all') ? $query->where('confirmed','!=', 'refused') : $query->where('confirmed', $confirmed);

				// Final get query with departement or all
				$clients = ($departement == "all") ? $query->get() : $query->where('departement', $departement)->get();
				
				$loto_client = Model_Butdom::clients_by_invoices_date(array('list' => $clients, 'start' => $startdate, 'end' => $enddate, 'result' => 'one'));

				$loto_client_id = (string) empty($loto_client) ? '' : $loto_client->id;

				\Session::set_flash('startdate', $startdate);
				\Session::set_flash('enddate', $enddate);

			}

			return \Response::redirect('admin/ramdon/' . $loto_client_id );

		} else {

			# Simple or operation list
			
			# Operations List -> select operation
			if ($action == 'operation' && $data['operation_id'] < 1 || $action == 'operation_closed') {
				
				$data['state'] = $this->buttonstatus[$confirmed];

				$data['departement'] 	= $departement;
				$data['confirmed'] 		= $confirmed;
				$data['status']			= $this->status;
				$view = \Theme::instance()->view('admin/list/clients')->set($data);
				\Theme::instance()->asset->js(array('parsley/i18n/messages.fr.js','parsley/parsley.js','bootstrap3/dropdown.js','datepicker/bootstrap-datepicker.js', 'datepicker/locales/bootstrap-datepicker.fr.js','custom/loterie.js'), array(), 'footer', false);
				\Theme::instance()->asset->css(array('datepicker.css'), array(), 'header', false);

				\Theme::instance()->get_template()->set('title', $title)->set('action', $action);
				\Theme::instance()->set_partial('content', $view);
				return NULL;

			}

			$clients = Model_Butdom_Client::query()->where($where_dep);

			if (isset($actual_ope) || $data['operation_id'] > 0) {
				// setup here the related where
				$clients = $clients->related('butdom_inscriptions', array('where' => array(array('operation_id', '=', $data['operation_id'])) ));
				$total = $total->join('butdom_inscriptions', 'right')->on('butdom_inscriptions.client_id', '=', 'butdom_clients.id')->where('butdom_inscriptions.operation_id', '=', $data['operation_id']);
			}

			if ($action  == 'loto') {
				$clients = $clients->related('butdom_invoices', array('where' => array(array('number', '>', 1))));
				$total = $total->join('butdom_invoices', 'right')->on('butdom_invoices.client_id', '=', 'butdom_clients.id')->where('butdom_invoices.number', '>', 1);

	    	}

	    	$total = (int) $total->execute()->get('result_count');
			
			// $total = $clients->count();

			$pagination = \Pagination::forge('butpagination', array(
			    'pagination_url' => \Uri::base(false) . "admin/clients/$departement/$action/",
			    'uri_segment' 	 => 5,
			    'total_items' 	 => $total,
			    'per_page' 		 => 20,
			    'num_links'		 => 10,
			    'show_first'	 => true,
			    'show_last'		 => true,
			    'link_offset'	 => 0.5,
			));

// removed : ->related('butdom_parrains')
			$clients = $clients ->rows_offset($pagination->offset)
								->rows_limit($pagination->per_page)
								->order_by($order_by)
								->get();
			// echo '<pre>';
			// var_dump($clients);
			// die();

			// $total_by_dep = array_count_values(\Arr::pluck($clients, 'departement'));

    		# Show filleuls
			$filleuls = Model_Butdom::get_filleuls($clients);

		}

		$view = \Theme::instance()->view('admin/list/clients');

		$data['state'] = $this->buttonstatus[$confirmed];

		$data['departement'] 	= $departement;
		$data['confirmed'] 		= $confirmed;
		// $data['total_by_dep'] 	= $total_by_dep;
		$data['total'] 			= $total;
		$data['status']			= $this->status;

		$view->set('clients', $clients, false)
			 ->set('filleuls', $filleuls, false)
			 ->set('pagination', $pagination->render(), false)
			 ->set($data);

		\Theme::instance()->asset->js(array('parsley/i18n/messages.fr.js','parsley/parsley.js','bootstrap3/dropdown.js','datepicker/bootstrap-datepicker.js', 'datepicker/locales/bootstrap-datepicker.fr.js','custom/loterie.js'), array(), 'footer', false);
		\Theme::instance()->asset->css(array('datepicker.css'), array(), 'header', false);

		\Theme::instance()->get_template()->set('title', $title)->set('action', $action);
		\Theme::instance()->set_partial('content', $view);

	}


	public function action_invoice() {

		$id = \Uri::segment(3) ? \Uri::segment(3) : NULL;
		if (\Uri::segment(2) == 'invoices') {
			$title = 'Facture';
			$view = \Theme::instance()->view('admin/list/invoices');
		} else {
			$title = 'Tirage au sort';
			$view = \Theme::instance()->view('admin/list/ramdon');
		}

		$data['startdate'] = \Session::get_flash('startdate') ? \Session::get_flash('startdate') : 0;
		$data['enddate'] = \Session::get_flash('enddate') ? \Session::get_flash('enddate') : 0;
		$data['str_startdate'] 	= ($data['startdate'] == 0) ? '' : \Date::forge($data['startdate'])->format("%d/%m/%Y");
		$data['str_enddate'] 	= ($data['enddate'] == 0) ? '' : \Date::forge($data['enddate'])->format("%d/%m/%Y");
		$data['status']		= array('approved' => 'Actif', 'pending' => 'En cours', 'refused' => 'Rejet', 'none' => '');

		if (isset($id)) {
			$client = Model_Butdom_Client::find('first', array(
				'related' 	=> array('butdom_invoices'),
				'where'		=> array( array('id', $id)),
				)
			);
			$invoices = \Arr::sort($client->butdom_invoices, 'date', 'desc');
			$view->set('invoices', $invoices, false);
			$view->set('client', $client, false);
		}

		\Theme::instance()->get_template()->set('title', $title);
		\Theme::instance()->set_partial('content', $view)->set($data);

	}

	public function action_delete($id) {

		$client = Model_Butdom_Client::find($id);

		if ($client) {

			$email = $client->email;
			$client->delete();
		}

		\Message::success('Vous avez effacer le client avec succès.');
		
		\Response::redirect('admin/clients', 'refresh');

	}


}
