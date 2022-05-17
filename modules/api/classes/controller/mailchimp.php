<?php

namespace Api;

class Controller_Mailchimp extends \Controller {

	public function action_index() {

		$whitelisted_IP = array('217.109.85.204'); // array of IP to allow

		$IP = \Input::ip();

		// if (in_array($IP, $whitelisted_IP)) {
		// if ( $IP == '217.109.85.204' ) {

			if (\Input::get('key') == "8OpQz4CvaErqPm69poCvwE8AzP5s3A" && \Input::post() ) {

				switch(\Input::post('type')){
					case 'subscribe'  : $this->subscribe(\Input::post('data'));   break;
					case 'unsubscribe': $this->unsubscribe(\Input::post('data')); break;
					case 'cleaned'    : $this->unsubscribe(\Input::post('data')); break;
					case 'profile'    : $this->profile(\Input::post('data'));     break;
					case 'upemail'    : $this->upemail(\Input::post('data'));     break;
					default:
						$this->alert('IP:'.$IP.' -> Illegal tentative request on Mailchimp webhooks !');
				}

			} else {
				
				$this->alert('IP:'.$IP.' -> Illegal tentative request on Mailchimp webhooks !');
			}

		/* } else {

			$this->alert('IP:'.$IP.' BLACKLISTED !');
		} */

	}

	private function alert($msg) {

		\Log::warning($msg);
		$email = \Email::forge();
		$email->from('contact@butdom.com','Votre magasin BUT');
		$email->to('dev@b2see.com', 'dev');
		$email->subject('ACCESS ILLEGAL MAILCHIMP WEBHOOK');
		$email->body($msg);
		$email->send();
		\Response::redirect('http://butdom.com');
	}

	private function subscribe($data){

		// Subscribes if not exist

		$entry = \Admin\Model_Butdom_Client::query()->where('email', '=', $data['email'])->get_one();

		if (isset($entry)) {

			if ($entry->confirmed != 'approved') {

				$entry->confirmed = 'approved';

				try {

					if ($entry->save()) {

						\Log::info($data['email'] . ' just subscribed : object exist and modified with approved status !');
					} else {
						\Log::warning($data['email'] . ' just subscribed, model exist but error model : object not modified !');
					}

				} catch (\Fuel\Core\FuelException $e) {

					\Log::warning($data['email'] . ' just subscribed, but FuelException throw an Error :' .$e->getMessage() );

				}

			}

		} else {

		    $client_m 				= \Admin\Model_Butdom_Client::forge();
		    $client_m->email 		= $data['email'];
		    $client_m->name 		= empty($data['merges']['FNAME']) ? '' : $data['merges']['FNAME'];
		    $client_m->surname 		= empty($data['merges']['LNAME']) ? '' : $data['merges']['LNAME'];
		    $client_m->departement 	= $data['merges']['INTERESTS'];
		    $client_m->token 		= \Security::generate_token();
		    $client_m->confirmed 	= 'approved';

		    try {

		    	if ($client_m->save()) {

		    		\Log::info($data['email'] . ' just subscribed : object added !');
		    	} else {
		    		\Log::warning($data['email'] . ' just subscribed, model error : object not saved !');
		    	}

		    } catch (\Fuel\Core\FuelException $e) {

		    	\Log::warning($data['email'] . ' just subscribed, but FuelException throw an Error :' .$e->getMessage() );
		    	
		    }

		}

	    

	}

	private function unsubscribe($data){

	    // Unsubscribes
	    // "action" will either be "unsub" or "delete". The reason will be "manual" unless caused by a spam complaint - then it will be "abuse"

	    $entry = \Admin\Model_Butdom_Client::query()->where('email', '=', $data['email'])->get_one();

	    if (isset($entry)) {

	    	try {

	    		if ($entry->delete()) {

	    			\Log::info($data['email'] . ' just unsubscribed : object removed !');
	    		} else {
	    			\Log::warning($data['email'] . ' just unsubscribed, model error : object not removed !');
	    		}

	    	} catch (\Fuel\Core\FuelException $e) {

	    		\Log::warning($data['email'] . ' just unsubscribed, but FuelException throw an Error :' .$e->getMessage() );
	    		
	    	}

	    } else {
	    	
	    	\Log::info($data['email'] . ' just unsubscribed, model error : object not exist !');
	    }

	}

	private function profile($data){

		// Profile Updates

	    $entry = \Admin\Model_Butdom_Client::query()->where('email', '=', $data['email'])->get_one();

	    if (isset($entry)) {
	    	
	    	!empty($data['merges']['FNAME']) and $entry->name = $data['merges']['FNAME'];
	    	!empty($data['merges']['LNAME']) and $entry->surname = $data['merges']['LNAME'];
	    	!empty($data['merges']['INTERESTS']) and $entry->departement = $data['merges']['INTERESTS'];
	    	$entry->email = $data['email'];

	    	$entry->confirmed 	= 'approved';

	    	try {

	    		if ($entry->save()) {

	    			\Log::info($data['email'] . ' updated their profile : object saved !');
	    		} else {
	    			\Log::warning($data['email'] . ' updated their profile, model error : object not saved !');
	    		}

	    	} catch (\Fuel\Core\FuelException $e) {

	    		\Log::warning($data['email'] . ' updated their profile, but FuelException throw an Error :' .$e->getMessage() );
	    		
	    	}
	    	
	    } else {

	    	$client_m 				= \Admin\Model_Butdom_Client::forge();
	    	$client_m->email 		= $data['email'];
	    	$client_m->name 		= empty($data['merges']['FNAME']) ? '' : $data['merges']['FNAME'];
	    	$client_m->surname 		= empty($data['merges']['LNAME']) ? '' : $data['merges']['LNAME'];
	    	$client_m->token 		= \Security::generate_token();
	    	$client_m->departement 	= $data['merges']['INTERESTS'];
	    	$client_m->confirmed 	= 'approved';

	    	try {

	    		if ($client_m->save()) {

	    			\Log::info($data['email'] . ' updated their profile, object not exist : so it was created !');
	    		} else {
	    			\Log::warning($data['email'] . ' updated their profile, object not exist, model error : object not saved !');
	    		}

	    	} catch (\Fuel\Core\FuelException $e) {

	    		\Log::warning($data['email'] . ' updated their profile, but FuelException throw an Error :' .$e->getMessage() );
	    		
	    	}
	    	
	    }

	}


	private function upemail($data){

		// Email Address Changes so I delete it, change profile store the new one

	    $entry = \Admin\Model_Butdom_Client::query()->where('email', '=', $data['old_email'])->get_one();

	    if (isset($entry)) {

	    	try {

	    		if ($entry->delete()) {

	    			\Log::info($data['old_email'] . ' changed their email address to '. $data['new_email']. ' : old email '.$data['old_email'].' was removed !');
	    		} else {
	    			\Log::warning($data['old_email'] . ' changed their email address to '. $data['new_email']. ' , model error : old email '.$data['old_email'].' was not removed !');
	    		}

	    	} catch (\Fuel\Core\FuelException $e) {

	    		\Log::warning($data['old_email'] . ' changed their email address to '. $data['new_email']. ' , but FuelException throw an Error : ' . $e->getMessage());
	    		
	    	}

	    } else {
	    	
	    	\Log::info($data['old_email'] . ' changed their email address to '. $data['new_email']. ' : object not exist !');
	    }


	}


}
