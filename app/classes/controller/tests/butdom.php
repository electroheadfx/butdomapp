<?php

class Controller_Tests_Butdom extends \Controller_Base_Admin {

	
	public function action_clientsparrains() {

		// Has_many related query : clients has_many parrains

		# Get all clients on departement 'guyane' with filleuls
		# specify in the query() : related 'butdom_parrains' table
		$clients = \Admin\Model_Butdom_Client::query()
			->where('departement', 'guyane')
			->related('butdom_parrains')
			->get();
		
		echo '<pre><br/>';

		# Foreach in array of clients query ()
		foreach ($clients as $client) {

			# Fetch butdom_parrains table (join)
			$filleuls = $client->butdom_parrains;

			# Look if $parrains array has data (has filleuls or not)
			if (count($filleuls) > 0) {

				echo $client->email . ' : ';
				echo '<br/>';

				# foreach in filleuls
				foreach ($filleuls as $filleul) {
					# get filleul id
					echo 'Filleul : ' . $filleul->filleul_id;
					echo '<br/>';
				}
				echo '<br/>';
			}

		}

		die();

	}

	public function action_clientsinscriptions() {

		$clients = \Admin\Model_Butdom_Client::query()
			->where('id', '36')
			->related('butdom_inscriptions')
			->get_one();
		
		echo '<pre><br/>';

		echo 'Client : '.$clients->email;

		echo '<br/>';
		
		echo 'Operation: '.\Admin\Model_Butdom_Operation::find($clients->butdom_inscriptions->operation_id)->name;
		
		echo '<br/>';

		// var_dump($clients->butdom_inscriptions);

		die();

	}

	public function action_inscriptionsclients() {

		$inscription = \Admin\Model_Butdom_Inscription::query()
			->where('client_id', '36')
			->related('butdom_clients')
			->get_one();
		
		echo '<pre><br/>';

		echo 'Client : '.$inscription->butdom_clients->email;

		echo '<br/>';
		
		echo 'Operation: '.\Admin\Model_Butdom_Operation::find($inscription->operation_id)->name;
		
		echo '<br/>';

		// var_dump($clients->butdom_inscriptions);

		die();

	}

	public function action_operationsinscriptions() {

		$operation = \Admin\Model_Butdom_Operation::query()
			->where('name', 'ouverture')
			->related('butdom_inscriptions')
			->get_one();
		
		echo '<pre><br/>';

		echo 'Operation : '.$operation->name . ' en ' . $operation->departement . ' (id:'.$operation->id.')';

		echo '<br/>';
		$client = array_shift($operation->butdom_inscriptions);

		echo 'Client email : '.\Admin\Model_Butdom_Client::find($client->client_id)->email;

		echo '<br/>';

		// var_dump($clients->butdom_inscriptions);

		die();

	}


	// $c=\Admin\Model_Butdom_Client::find(37);
	// $c->address="toto";
	// // unset($c->ope2054); // delete ope2054 si cascade = false
	// $c->save();
	// echo '<pre>';
	// var_dump($c->address);
	// die();



}
