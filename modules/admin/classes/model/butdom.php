<?php

namespace Admin;

class Model_Butdom extends \Orm\Model {

	public static function clients_by_invoices_date(array $data) {

		$list = $data['list'];
		$startdate = $data['start'] ? $data['start'] : 315529200;
		$enddate = $data['end'] ? $data['end'] : 4102441200;
		$result = $data['result'] ? $data['result'] : 'all';

		$goodclients = array();

		foreach ($list as $obj) {

			$flag = false;

			foreach ($obj->butdom_invoices as $invoice) {

				($invoice->date >= $startdate && $invoice->date <= $enddate ) and $flag = true;

			}

			$flag and $goodclients[] = $obj;
		}

		if ($result == "all") {

			return $goodclients;

		} else {

			if(empty($goodclients)) {

				return $goodclients;
			}
				
			return $goodclients[array_rand($goodclients)];
		}

	}

	public static function get_filleuls($clients) {

		if (empty($clients)) {

			return array();
		}

		$filleuls = array();

		foreach ($clients as $client) {

			$parrain = $client->butdom_parrains;

			if (!empty($parrain)) {

				foreach ($parrain as $filleul) {

		    		$filleuls[$client->id][] = Model_Butdom_Client::find($filleul->filleul_id);

		    	}
				
			}
		}


	    if (empty($filleuls)) {

			return array();
		}

		return $filleuls;

	}

	
}
