<?php

namespace Api;

class Controller_Emailverify extends \Controller_Rest {

	public function get_index($email = NULL) {

		$this->format="json";

		if( \Request::is_hmvc() && $email) {
			

			\Config::load('listwise_api', 'api');
			$key  = \Config::get('api.key');
			$user = \Config::get('api.user');
			

				$curl = \Request::forge('https://api.listwisehq.com/clean.php', 'curl')->set_method('post');
				$curl->set_params(array('api_user' => $user, 'api_key' => $key, 'email' => $email));

				// $curl->set_options(array(
				// 	CURLOPT_TIMEOUT => 60,
				// 	CURLOPT_SSL_VERIFYPEER => 1,
				// 	CURLOPT_SSL_VERIFYHOST => 1,
				// ));

				$reponse 	= $curl->execute();
				$requete 	= $curl->response()->body;

			if (isset($requete['api_error'])) {

				return $this->response(
					array(
						'is_clean'	=> "Error API",
						)
				);
			
			} else {

				return $this->response(
						array(
							'email' 		=> $requete['email'],
							'email_status'	=> $requete['email_status'],
							'bad_mx'		=> $requete['bad_mx'],
							'free_mail'		=> $requete['free_mail'],
							'no_reply'		=> $requete['no_reply'],
							'typo_fixed'	=> $requete['typo_fixed'],
							'is_clean'		=> $requete['is_clean']
						)
					);

			}
			

		} else {

			\Response::redirect(404);
		}

	}

	public function get_test($email = NULL) {

		$this->format="json";

		if($email) {

			\Config::load('listwise_api', 'api');
			$key  = \Config::get('api.key');
			$user = \Config::get('api.user');
			
			$curl = \Request::forge('https://api.listwisehq.com/clean.php', 'curl')->set_method('post');
			$curl->set_params(array('api_user' => $user, 'api_key' => $key, 'email' => $email));
			
			
			$reponse 	= $curl->execute();

			$requete 	= $curl->response()->body;

			if (isset($requete['api_error'])) {

				return $this->response(
					array(
						'is_clean'	=> "Error API",
						)
				);
			
			} else {

				return $this->response(
						array(
							'email' 		=> $requete['email'],
							'email_status'	=> $requete['email_status'],
							'bad_mx'		=> $requete['bad_mx'],
							'free_mail'		=> $requete['free_mail'],
							'no_reply'		=> $requete['no_reply'],
							'typo_fixed'	=> $requete['typo_fixed'],
							'is_clean'		=> $requete['is_clean']
						)
					);

			}
			

		} else {

			return $this->response(
						array(
							'email' 		=> $requete['email'],
							'email_status'	=> 'error',
						)
					);
		}

	}

	public function get_setup() {

		$redis = \Redis_Db::forge('default');
		$redis->rpush('particles', 'protonx');
		$redis->rpush('particles', 'electronx');
		$redis->rpush('particles', 'neutronx');
		return $this->response(
					array(
						'setredis' 	=> 'success !',
						'data'		=> $redis->lrange('particles', 0, -1),
					)
				);
	}

	public function get_redis() {

		return $this->response(
					array(
						'getredis' 	=> 'success !',
						'data'		=> \Redis_Db::instance('default')->lrange('particles', 0, -1),
					)
				);
	}

	public function after($response) {

		if ($this->response->status == 405) {
			\Response::redirect(404);
		}

		return parent::after($response);

	}


}
