<?php

namespace Admin;

class Controller_Import extends \Controller_Base_Admin {

	protected static $status = array('all' => 'Tous','approved' => 'Actif', 'pending'=>'En cours', 'refused'=>'Rejet' );
	protected static $buttonstatus = array('approved' => 'success', 'pending'=>'warning', 'refused'=>'danger' );
	protected $content = "";

	public function before() {

		\Theme::instance()->asset->css(array('list.css'), array(), 'header', false);

		parent::before();

	}
	
	public function action_index($confirmed = NULL ) {

		if ( \Auth::has_access('admin.list[modify]')) {

			$title = 'Import But Dom List';
			$path = 'upload/clean_lists';
			$view = \Theme::instance()->view('admin/import/index');
			$data['submit'] = false;

			$confirmed = $confirmed ? : 'approved';
			! array_key_exists($confirmed, static::$status) and $confirmed = 'approved';

			$data['state'] = static::$buttonstatus[$confirmed];

			if (\Input::param() != array()) {

				$data['submit'] = true;
				
				$file_content = \File::read(DOCROOT.$path.'/'.\Input::post('render'), true);
				$list = \Format::forge($file_content, 'csv')->to_array();
				
				$this->content 	 = '<br/>';
				$this->content 	.= '<div class="dump" >';
				$this->content 	.= "<h1>Passage des clients &agrave; : <b>".static::$status[$confirmed]."</b></h1>";

				foreach ($list as $client) {

					$departement = $client['departement'];
					$client = $client['email'];

					$this->content 	.= '<p><span class="glyphicon glyphicon-user"></span> '.$client;
					$this->content 	.= ' <b>'.$departement . '</b> : ';

					$query_client = \Admin\Model_Butdom_Client::query()->from_cache(false)->where('email', '=', $client)->get_one();
					
					if (isset($query_client)) {

						if ($query_client->confirmed != $confirmed ) {

							$query_client->confirmed = $confirmed;

							if ($confirmed == "approved" && ! \Admin\Model_Butdom_Client::is_mailchimp($query_client->email) ) {
								# Prépare mailchimp
								$group_interest = \Config::get('but_group_interest');
								$merge_vars = array(
								  'FNAME'		=> $query_client->surname,
								  'LNAME'		=> $query_client->name,
								  'PARTENAIRE' 	=> 'non', //'non' ou 'oui'
								  'GROUPINGS'	=> array(
								            		array('name' => 'Votre département', 'groups'=> $group_interest[$query_client->departement])
								       			   )
								);
								// send Mailchimp double optin
								\TinyChimp::listSubscribe( array(
									'id' 				=> \Config::get('but_list_id') ,
									'email_address' 	=> $query_client->email,
									'merge_vars' 		=> $merge_vars,
									'email_type' 		=> 'html',
									'double_optin' 		=> false,
									'update_existing' 	=> true,
									'replace_interests' => true
								));
								
							}

							if ($query_client->save()) {

								$this->show_processing($client, $departement, 'success', static::$status[$confirmed]);

							} else {
								$this->show_processing($client, $departement, 'warning', 'Erreur db');
							}

						} else {
							$this->show_processing($client, $departement, 'info', 'D&eacute;ja '.$confirmed);
						}

					} else {
						$this->show_processing($client, $departement, 'danger', 'Inexistant');
					}
				}
				$this->content 	.= '</div><br/>';

			}

			$dirContent = \File::read_dir(DOCROOT.$path, 2, array(
			    '!^\.', 				// no hidden files/dirs
			    '!^private' => 'dir', 	// no private dirs
			    '\.csv$' 	=> 'file', 	// only get CSV's
			    '\.CSV$' 	=> 'file', 	// only get CSV's
			    '!^_', 					// exclude everything that starts with an underscore.
			));

			$data['confirmed'] = $confirmed;

			$view->set('dirContent', $dirContent)
				 ->set('path', $path)
				 ->set('title', $title)
				 ->set('status', static::$status)
				 ->set($data)
			;

			// \Theme::instance()->asset->js(array('bootstrap3/button.js','bootstrap3/dropdown.js','custom/import.js'), array(), 'footer', false);
			\Theme::instance()->asset->js(array('bootstrap3/modal.js', 'custom/import.js'), array(), 'footer', false);
			
			// \Theme::instance()->asset->css(array('list.css'), array(), 'header', false);

			\Theme::instance()->get_template()->set('title', $title);
			\Theme::instance()->set_partial('content', $view)->set('content', $this->content, false);

		}

	}

	private function show_processing($email, $departement, $status, $message) {
		$this->content .= '<button type="button" class="btn btn-'.$status.' btn-xs">'.$message.'</button>';
	}


}
