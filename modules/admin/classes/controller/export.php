<?php

namespace Admin;

class Controller_Export extends \Controller_Rest {

	public function before() {

		if (! \Auth::get_groups()) {
			\Message::danger('Connectez-vous pour accéder à l\'administration');
			\Response::redirect('login');
		}

		parent::before();

	}

	public function action_index($departement, $confirmed, $operation_id = 0) {

		if ($operation_id > 0) {
			$file = 'departement'.ucfirst($departement).'-validation'.ucfirst($confirmed).'-operation'.ucfirst(Model_Butdom_Operation::query()->where('id', $operation_id)->get_one()->name);
		} else {
			$file = 'departement_'.$departement.'-validation_'.$confirmed;
		}

		$response = new \Response();
		$response->set_header('Content-Type', 'text/csv');
		$response->set_header('Content-Disposition', 'attachment; filename="'.$file.'.csv"');

		$response->set_header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
		$response->set_header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
		$response->set_header('Pragma', 'no-cache');

		$clients = Model_Butdom_Client::get_clients($departement, $confirmed, $operation_id);

		echo "\"departement\",\"email\",\"name\",\"surname\",\"telephone\"\n";

		foreach ($clients as $client) {

			echo "\"$client->departement\",\"$client->email\",\"$client->name\",\"$client->surname\",\"$client->telephone\"\n";
			
		}

		return $response;

	}

	public function action_print($departement, $confirmed, $operation_id = 0, $parrain = true) {

		$response = new \Response();
		$response->set_header('Content-Type', 'text/html; charset=utf-8');
		$response->set_header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate');
		$response->set_header('Expires', 'Mon, 26 Jul 1997 05:00:00 GMT');
		$response->set_header('Pragma', 'no-cache');

		$clients = Model_Butdom_Client::get_clients($departement, $confirmed, $operation_id);

		$content = '';

		$operation = Model_Butdom_Operation::find($operation_id);

		$title = $operation->title;
		$event = $operation->event;

		foreach ($clients as $client) {

			$data['phone'] 		= preg_replace('/^([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$/', '$1 $2 $3 $4 $5', $client->telephone);
			$data['name']		= $client->name;
			$data['surname'] 	= $client->surname;
			$data['email']		= $client->email;

			if ($parrain) {

				$parrains = Model_Butdom_Parrain::query()
					->where('parrain_id', '=', $client->id)
					->where('operation_id', '=', $operation_id)
					->where('used', '=', $operation_id)->get();
				
				$data['parrain'] = 1;

				foreach ($parrains as $parrain) {
					$content .= \Theme::instance()->view('admin/list/export', $data);
					$data['parrain']++;
				}
				$data['parrain'] = NULL;
			}

			$data['title'] = $title;
			$data['event'] = $event;

			$content .= \Theme::instance()->view('admin/list/export', $data);
			
		}

		echo \Theme::instance()->view('templates/export')->set('content', $content, false);

		return $response;

	}

	// public function action_csv($departement, $confirmed, $operation_id = 0) {
	// 	return Model_Butdom_Client::get_clients($departement, $confirmed, $operation_id);
	// }

	# for get csv data :
	// $array = Format::forge($csvstring, 'csv')->to_array();

	// with classic php :
	// $arrResult = array();
	// $arrLines = file('data.csv');
	// foreach($arrLines as $line) {
	// $arrResult[] = explode( ',', $line);
	// } 


}
