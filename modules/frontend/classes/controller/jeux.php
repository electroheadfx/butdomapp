<?php

namespace Frontend;

class Controller_Jeux extends \Controller_Base_Public {

	public $group_interest;
	public $departement;
	public $mailchimpID;
	public $operation;
	public $active_departement;

	public function before() {

		\Config::load('but_mclist');
		$this->group_interest = \Config::get('but_group_interest');
		$this->mailchimpID = \Config::get('but_list_id');
		return parent::before();
	}

	#################################################################################################################
	#																												#
	#																												#
	#   												Jeu 														#
	#																												#
	#																												#
	#################################################################################################################

	/**
	 * [ Jeu Opé with tracking subscribe stats per departement and form/data backup]
	 * @param  string $ope [name of ope (uri), cf table butdom_operation]
	 * @param  string $departement [tous, martinique, reunion, guadeloupe, guyane, stmartin]
	 * @param  string $code_parrain_id [Client ID for email transactionnal activation to parrain]
	 * @return Template response           
	 */
	
	public function action_index($ope, $departement = 'tous', $code_parrain_id = NULL) {
		
		# Found the operation in table 'operations'
		$this->operation = \Admin\Model_Butdom_Operation::query()->where('name', '=', $ope)->get_one();

		# test if $departement and $this->operation are legal
		if (empty($this->operation)) {
			return \Response::redirect('frontend/404');
		}

		$this->departement 		= in_array($departement, array_keys(\Admin\Model_Butdom_Operation::get_departements_form())) ? $departement : 'tous';

		# Setup Operation data
		$action 				= ucfirst($ope);
		$title 					= $this->operation->title . ' : ' . $this->operation->event;
		$subject_subscribed 	= 'Confirmation d\'inscription : ' . $this->operation->title;
		$subject_filleul		= 'Votre parrain vous invite au jeu : ' . $this->operation->title;
		$source 				= "assets/img/$ope/";
		$background_body		= \Uri::base(false).$source.'bg.jpg';
		$inscription_body		= \Uri::base(false).$source.'inscription.png';

		# Setup date for view and email
		$data['action'] 		= 'Remplissez le formulaire d\'inscription';
		$data['title'] 			= $title;
		$data['source'] 		= $source;
		$data['ope'] 			= $ope;
		$data['departement'] 	= $this->departement;

		# Setup transactionnal emails view and page view
		$subscribed_view 		= \Theme::instance()->view('frontend/jeux/subscribed');
		$view 					= \Theme::instance()->view('frontend/operation/jeu')->set('inscription_body', $inscription_body);

#### -----> Here put the TEST (in end file) for verify Transactionnal emails from browser


#### <-------

		# Setup Here CSS/JS and template
		\Theme::instance()->asset->css(array('jeu.css'), array(), 'header', false);

		## Create fieldsets and additionnal forms
		$client_m 	= \Admin\Model_Butdom_Client::forge();
		$fieldset 	= \Fieldset::forge('registration');
		$fieldset->form()->add_csrf();
		$fieldset->add_model($client_m);

#!		#### -> Will do a foreach of EAV fields to add, same for EAV model save
		// $form->add('address', 'Adresse postale : ', array('type' => 'text'))->add_rule('required')->set_error_message('required','L\'adresse doit être connue pour le jeu.')->set_value(\Input::post('address'));
		
		$fieldset->add('legendfilleul')->set_template('<h4 class="but">Augmentez vos chances de gagner<br/>en parrainant vos amis<sup>(1)</sup> :</h4>');
		$fieldset->add('filleul[0]', 'E-mail ami 1 : doublez vos chances :', array('type' => 'email', 'class' => 'parrain'), array('valid_email'))->set_error_message('valid_email','L\'email doit être valide.');
		$fieldset->add('filleul[1]', 'E-mail ami 2 : triplez vos chances :', array('type' => 'email', 'class' => 'parrain'), array('valid_email'))->set_error_message('valid_email','L\'email doit être valide.');
		$fieldset->add('filleul[2]', 'E-mail ami 3 : quadruplez vos chances :', array('type' => 'email', 'class' => 'parrain'), array('valid_email'))->set_error_message('valid_email','L\'email doit être valide.');

		$fieldset->field('email')->set_error_message('required','L\'email doit être connu.')->set_error_message('valid_email','L\'email doit être valide.');
		$fieldset->add_after('confirm_email', 'Confirmez votre email : ', array('type' => 'email'), array(), 'email')->add_rule('match_field','email')->set_error_message('match_field','L\'email de confirmation doit être identique à l\'email.');
		$fieldset->field('name')->add_rule('required')->set_error_message('required','Le nom doit être connu.');
		$fieldset->field('surname')->add_rule('required')->set_error_message('required','Le prénom doit être connu.');
		$fieldset->field('telephone')->add_rule('required')->set_error_message('match_pattern','Le champs téléphone doit contenir 10 chiffres.')->set_error_message('required','Le champs téléphone doit être connu.');
		
#!		#### Added set template for hidden class the field, later do a if condition : display departement if equal to tous, hidden for specific departement
		if ($this->departement == 'tous' || $this->departement == '') {
			$fieldset->field('departement')->set_attribute(array('class' => 'form-control'));
		} else {
			$fieldset->field('departement')->set_attribute(array('class' => 'form-control'))->set_value($this->departement); //->set_type('hidden'); // Hide setup Select 
		}
		
		#### Setup additionnal checkbox here
		$fieldset->add('checkbox', '', array('type' => 'checkbox', 'class' => 'optin', 'options' => array("J’accepte de recevoir les actualités des magasins Cafom"), 'value' => null )); //, 'disabled' => 'disabled' // not allow to change
		$fieldset->add('submit', '', array('type' => 'submit', 'value' => 'Je m\'inscris', 'class' => 'btn btn-primary'));

		$fieldset->repopulate();

		#### POST Logic
		if (\Input::param() != array() && \Input::post()) {

			# Fieldset is running
			if ($fieldset->validation()->run()) {

				$fields = $fieldset->validated();

				$parrain_from_filleul = '';

				if ($code_parrain_id) {

					$parrain_from_filleul = \Admin\Model_Butdom_Parrain::query()
							->where('operation_id','=',$this->operation->id)
							->where('used','!=', $this->operation->id)
							->where('parrain_id', '=', $code_parrain_id)
							->get_one();
				}

				// Store the departement from departement field form
				$this->active_departement = $fields['departement'];

				#####################################################
				#													#
				#					"OptinActif"					#
				#---------------------------------------------------#
				#	 	Total optin actif par département			#
				#													#
				#####################################################
				$this->save_ope_meta('OptinActif');
				#####################################################

				try {
					$client_m->email 		= $fields['email'];
					$client_m->name 		= $fields['name'];
					$client_m->surname 		= $fields['surname'];
					$client_m->departement 	= $this->active_departement;
					$client_m->telephone 	=  preg_replace("/[\s\-\/\:]/","", $fields['telephone']);

					### if needed to add new client datas use EAV attributes on butdom_meta						
					###  e.g. for add address : $client_m->address = $fields['address'];

					// Test if exist in mailchimp base, is true $confirmed = "approved" else not "pending"
					$client_m->confirmed 	= \Admin\Model_Butdom_Client::is_mailchimp($fields['email']) === true ? 'approved' : 'pending';
					$client_m->token 		= \Security::generate_token();
					
					#### send Mailchimp double optin
					if ($client_m->confirmed != 'approved' && $fields['checkbox'] !== NULL ) {
						$this->register_to_mailchimp($client_m->surname, $client_m->name, $client_m->email,$client_m->departement, true);
					}

					$query_client = \Admin\Model_Butdom_Client::query()->from_cache(false)->where('email', '=', $fields['email'])->where('departement', '=', $this->active_departement)->related('butdom_inscriptions')->get_one();
					$save_client = false;

					#### Logic if client exist already or not
					if (isset($query_client)) {
						
						// Client exists
						
					   	################################################################################
					   	# 																			   #
					   	# 							"OptinNewOpe"									   #
					   	#------------------------------------------------------------------------------#
					   	#	  		Total optin New sur le jeu en cours par département 			   #
					   	# 																			   #
					   	################################################################################
						$inscription = \Admin\Model_butdom_inscription::query()->from_cache(false)->where('client_id', $query_client->id)->where('operation_id',$this->operation->id)->get_one();

							// client doesn't exist for this ope (no inscriptions)
							if ($inscription === NULL) {

									$this->save_ope_meta('OptinNewOpe');

									if ($client_m->confirmed == 'approved') {
										
										############################################################################
										#																		   #
										#							"OptinMailchimp"							   #
										#--------------------------------------------------------------------------#
									   	# 			   Total optin mailchimp clients par département 			   #
									   	#																		   #
									   	############################################################################
											$this->save_ope_meta('OptinMailchimp');
										############################################################################
									}
							}

						################################################################################

						$query_client->name 		= $client_m->name;
						$query_client->surname 		= $client_m->surname;
						$query_client->telephone 	= $client_m->telephone;
						
						if ($client_m->confirmed == 'approved' && $query_client->confirmed != 'approved') {
							$query_client->confirmed = 'approved';
						}
						if ($query_client->save()) {
							
							$save_client = true;
							$clientID = $query_client->id;
						}

					} else {

						// Client didn't exist no anywhere !
						
						// client is new in jeu/opé and Mailchimp so we count him to stats
						if ($client_m->confirmed == 'pending') {

							##########################################################################
							#																		 #
							#							"OptinNewMailchimp"							 #
							#------------------------------------------------------------------------#
						   	# 			Total optin New client mailchimp par département			 #
						   	#																		 #
						   	##########################################################################
								$this->save_ope_meta('OptinNewMailchimp');

						} else if ($client_m->confirmed == 'approved') {
										
							############################################################################
							#																		   #
							#							"OptinMailchimp"							   #
							#--------------------------------------------------------------------------#
						   	# 			   Total optin mailchimp clients par département 			   #
						   	#																		   #
						   	############################################################################
								$this->save_ope_meta('OptinMailchimp');
							############################################################################
						}
						
						if ($client_m->save()) {

							################################################################################################
							#																							   #
							#							"OptinNew"	&& "OptinNewOpe"									   #
							#----------------------------------------------------------------------------------------------#
						   	# 				Total optin New à tous les jeux/opé depuis le début par département 		   #
						   	# 				Total optin New sur le jeu en cours par département							   #
						   	#																							   #
						   	################################################################################################
								// $this->save_ope_meta('OptinNew');
								$this->save_ope_meta('OptinNewOpe');
							################################################################################################
							
							$save_client = true;
							$clientID = $client_m->id;

						}
					}

					// Save client

					if ($save_client) {

						// Get data for views and transactionnal emails
						$data['email'] 		= $fields['email'];
						$data['name'] 		= ucfirst($fields['name']);
						$data['surname'] 	= ucfirst($fields['surname']);

						#### Logic inscription
						$inscription_passed = false;
						$query_inscription = \Admin\Model_Butdom_Inscription::query()->from_cache(false)->where('client_id', '=', $clientID)->where('operation_id', '=', $this->operation->id)->get_one();
						if (isset($query_inscription)) {
							$inscription_passed = true;
							if ($code_parrain_id) {
								\Message::info('Merci d\'avoir confirmer votre inscription à l\'offre. Vous allez recevoir un email.');
							} else {
								\Message::info('Merci, vous vous êtes déjà inscrit à l\'offre.');
							}
							$view = 'frontend/operation/success';

						} else {

							$inscription = \Admin\Model_Butdom_Inscription::forge();
							$inscription->client_id 	= $clientID;
							$inscription->operation_id 	= $this->operation->id;

							if ($inscription->save()) {
								$inscription_passed = true;
								\Message::info('Merci de votre participation, vous allez recevoir un email de la confirmation de votre inscription.');
								$view = 'frontend/operation/success';

							} else {
								\Message::danger('Une erreur s\'est produite dans l\'enregistrement, contacter <a href="mailto:dev@b2see.com">ici support</a>.');
							}
						}

						/* Retrieve here img views for transactionnals emails */
						
							$img_path = 'img/newsletter/jeu'.DIRECTORY_SEPARATOR.$ope.DIRECTORY_SEPARATOR.$this->departement.DIRECTORY_SEPARATOR;
							$theme = \Theme::instance();
							$img[01] = \Uri::base(false).$theme->asset_path($img_path.'01.jpg');
													
							!file_exists(\Uri::base(false).$img[01]) and $img_path = 'img/newsletter/jeu'.DIRECTORY_SEPARATOR.$ope.DIRECTORY_SEPARATOR.$this->operation->departement.DIRECTORY_SEPARATOR;

							$img[01] = \Uri::base(false).$theme->asset_path($img_path.'01.jpg');
							$img[02] = \Uri::base(false).$theme->asset_path($img_path.'02.jpg');

						/* end */
						
						if ($inscription_passed) {

							$email = \Email::forge();
							$email->from('contact@butdom.com','Votre magasin BUT');
							$email->to($fields['email'], $data['name']);
							$email->subject($subject_subscribed);
							$content = \Theme::instance()->view('frontend/jeux/subscribed_content')->set($data);
							$email->html_body($subscribed_view->set('content',$content, false)
															  ->set('title', $title)
															  ->set('img', $img)
															  ->set('ope', $ope)
							);
							$email->send();
						}

						#### Setup img 02.jpg for filleul transactionnal emails
						$img[02] = \Uri::base(false).$theme->asset_path($img_path.'02-filleul.jpg');

						##### make filleuls unique and not empty and remove self
						$filleuls = array_diff(array_filter(array_unique($fields['filleul'])), array($fields['email']));

						#### Logic emails filleul, foreach in filleuls fields and test them
						foreach ($filleuls as $filleul_mail) {

							$filleul_url 		= urldecode(\Uri::base(false).'jeu'.DIRECTORY_SEPARATOR.$ope.DIRECTORY_SEPARATOR.$this->active_departement.DIRECTORY_SEPARATOR.$clientID);
							$data['filleul'] 	= $filleul_mail;
							$data['filleul_url']= $filleul_url;
							$email_fil 			= \Email::forge();
							$email_fil->from('contact@butdom.com','Votre magasin BUT');
							$email_fil->to( $filleul_mail , $filleul_mail);
							$email_fil->subject($subject_filleul); 
							$content = \Theme::instance()->view('frontend/jeux/filleul_content')->set($data);
							$email_fil->html_body($subscribed_view->set('content',$content, false)
																  ->set('img', $img)
																  // ->set('title', $title)
																  // ->set('ope', $ope)
							);

							// get if filleul exist
							$query_fil = \Admin\Model_Butdom_Client::query()->where('email', '=', $filleul_mail)->get_one();
							
							// filleul doesn't exist need to create it
							if (!isset($query_fil)) {
								// store filleul in DB
								$filleul 				= \Admin\Model_Butdom_Client::forge();
								$id_filleul 			= $filleul->id;
								$filleul->email 		= $filleul_mail;
								$filleul->departement 	= $this->active_departement;
								$filleul->confirmed 	= \Admin\Model_Butdom_Client::is_mailchimp($filleul_mail) === true ? 'approved' : 'pending';
								$filleul->token 		= \Security::generate_token();
								$filleul->save();
								$id_filleul = $filleul->id;
							} else {
							// filleul exist
								$id_filleul = $query_fil->id;
							}

							$query_filleul_test = \Admin\Model_Butdom_Parrain::query()->from_cache(false)->where('parrain_id', $clientID)->where('filleul_id', $id_filleul)->where('operation_id', $this->operation->id)->get_one();
							$email_not_parrain  = empty($query_filleul_test) ? TRUE : FALSE;

							if ($email_not_parrain) {
								// save parrain relationship with filleul
								$parrain 			 	= \Admin\Model_Butdom_Parrain::forge();
								$parrain->filleul_id 	= $id_filleul;
								$parrain->parrain_id 	= $clientID;
								$parrain->operation_id 	= $this->operation->id;
								$parrain->used 		 	= 0;
								if ($parrain->save()) {
									$email_fil->send();
								}
							} else {
								\Message::info("L'email ami $filleul_mail a été déjà utilisé, il ne sera pas pris en compte.");
							}
							
						} // end foreach $filleuil emails

						#### Logic if user is parrain
						if ($parrain_from_filleul) {
							# code parain is valid, we store it
							$parrain_from_filleul->used = $this->operation->id;
							$parrain_from_filleul->save();
						}
						
						\Theme::instance()->get_template()->set('title', $title)->set('ope', $background_body);
						\Theme::instance()->set_partial('content', $view )->set($data);
						return NULL;

					} // end save client

				} catch (\Orm\ValidationFailed $e) {

					// Une erreur s'est produite dans les models
					\Message::danger('Une erreur s\'est produite dans l\'enregistrement, contacter <a href="mailto:dev@b2see.com">ici support</a>.');
					\Log::warning('Un erreur s\'est produite en frontend : FuelException throw an Error :' .$e->getMessage() );

				}

			// end Fieldset validation
			} else {

				// if unknown error in form
				\Message::danger('Erreur dans les champs, veuillez les vérifier, merci.');
			}

		} // end POST test

		#### Output fieldsets to view
		$view->set('form', $fieldset->build(), false);
		# Setup the template
		\Theme::instance()->get_template()->set('title', $title)->set('ope', $background_body);
		\Theme::instance()->set_partial('content', $view )->set($data);

	}

	# end jeu opé
	# 
	
	
	#################################################################################################################
	#																												#
	#																												#
	#   				JEU Loterie Magasins																		#
	#																												#
	#																												#
	#################################################################################################################


	public function action_loterie() {
		
		$title = 'Loterie remboursement achat';
		$data['departement'] = '';
		\Config::load('but_mclist');
		$group_interest = \Config::get('but_group_interest');

	// Test si le formulaire a été soumis
		if (\Input::param() != array()) {

			// récupére les posts
			$data['email'] 		= \Input::post('email');
			$data['name'] 		= \Input::post('name');
			$data['surname'] 	= \Input::post('surname');
			$data['telephone'] 	= \Input::post('telephone');
			$data['departement']= \Input::post('departement');
			$data['invoice'] 	= \Input::post('invoice');
			$data['dateinvoice']= \Date::create_from_string( \Input::post('dateinvoice'), "eu")->get_timestamp();

		// create the mode inject the data with Try
			try {

				$client_m 				= \Admin\Model_Butdom_Client::forge();
				$client_m->email 		= $data['email'];
				$client_m->name 		= $data['name'];
				$client_m->surname 		= $data['surname'];
				$client_m->telephone 	= $data['telephone'];
				$client_m->departement 	= $data['departement'];
				$client_m->confirmed 	= \Admin\Model_Butdom_Client::is_mailchimp($data['email']) === true ? 'approved' : 'pending';

				$invoice_m 			= \Admin\Model_Butdom_Invoice::forge();
				$invoice_m->number 	= $data['invoice'];
				$invoice_m->date 	= $data['dateinvoice'];

				// data for mailchimp
				$merge_vars 		= array(
						  'FNAME'		=> $client_m->surname,
						  'LNAME'		=> $client_m->name,
						  'PARTENAIRE' 	=> 'non', //'non' ou 'oui'
						  'GROUPINGS'	=>array(
						            		array('name' => 'Votre département', 'groups'=> $group_interest[$client_m->departement])
						       			)
				);

				$query_client = \Admin\Model_Butdom_Client::query()->related('butdom_invoices')->where('email', '=', $data['email'])->get_one();
				$query_invoice = \Admin\Model_Butdom_Invoice::query()->where('number', '=', $data['invoice'])->get_one();

			// Si le client existe déja
				if (isset($query_client)) {

					if (!isset($query_invoice)) {
						$query_client->butdom_invoices[] = $invoice_m;
					}

					if ($client_m->confirmed == 'approved') {

						$query_client->confirmed = 'approved';

					} else {

						// send Mailchimp double optin
						\TinyChimp::listSubscribe( array(
							'id' 				=> \Config::get('but_list_id') ,
							'email_address' 	=> $client_m->email,
							'merge_vars' 		=> $merge_vars,
							'email_type' 		=> 'html',
							'double_optin' 		=> true,
							'update_existing' 	=> true,
							'replace_interests' => true
						));

					}

					if ($query_client->save()) {

						if (isset($query_invoice)) {
							\Message::info('Le compte ' . $data['email'] . ' est déjà existant et la facture N°'.$data['invoice'].' a déjà été enregistré sur ce compte.');
						} else {
							\Message::info('Le compte ' . $data['email'] . ' est déjà existant et la facture a bien été enregistré sur ce compte.');
						}

					}

			// si le client n'existe pas
				} else {

					if (!isset($query_invoice)) {
						$client_m->butdom_invoices[] = $invoice_m;
					}
					$client_m->token 		= \Security::generate_token();

					if ($client_m->confirmed != 'approved') {
						// send Mailchimp double optin
						\TinyChimp::listSubscribe( array(
							'id' 				=> \Config::get('but_list_id') ,
							'email_address' 	=> $client_m->email,
							'merge_vars' 		=> $merge_vars,
							'email_type' 		=> 'html',
							'double_optin' 		=> true,
							'update_existing' 	=> true,
							'replace_interests' => true
						));
					}

					if ($client_m->save()) {
						
						if (isset($query_invoice)) {
							\Message::info('Le compte ' . $data['email'] . ' sera valider, et la facture N°'.$data['invoice'].' a déjà été enregistré sur ce compte.');
						} else {
							\Message::info('Le compte ' . $data['email'] . ' sera valider, en attendant la facture a bien été enregistré.');
						}

					}

				}

				$data = array();
				$data['departement'] = '';

		// Une erreur s'est produite dans les models
			} catch (\Orm\ValidationFailed $e) {

				// \Debug::Dump($e->getMessage());
				// \Message::danger($e->getMessage());
				// \Message::warning($data['dateinvoice']);
				\Message::danger('Une erreur s\'est produite, contacter <a href="mailto:dev@b2see.com">ici support</a>.');

			}

		} // end Posts test


		$view = \Theme::instance()->view('frontend/jeux/loterie');

		\Theme::instance()->asset->js(array('parsley/i18n/messages.fr.js','parsley/parsley.js','bootstrap3/dropdown.js','datepicker/bootstrap-datepicker.js', 'datepicker/locales/bootstrap-datepicker.fr.js', 'custom/loterie.js'), array(), 'footer', false);
		\Theme::instance()->asset->css(array('datepicker.css','loterie.css'), array(), 'header', false);
		\Theme::instance()->get_template()->set('title', $title);
		\Theme::instance()->set_partial('content', $view )->set('error', $data);

	}

	##############################################
	#											 #
	# Function for ouverture : send doubleoptin  #
	#											 #
	##############################################
	 
	protected function register_to_mailchimp($surname, $name, $email, $departement, $double_optin = false) {

			$merge_vars = array(
					  'FNAME'		=> $surname,
					  'LNAME'		=> $name,
					  'PARTENAIRE' 	=> 'non', //'non' ou 'oui'
					  'GROUPINGS'	=>array(
					            		array('name' => 'Votre département', 'groups'=> $this->group_interest[$departement])
					       			)
			);

			\TinyChimp::listSubscribe( array(
				'id' 				=> $this->mailchimpID ,
				'email_address' 	=> $email,
				'merge_vars' 		=> $merge_vars,
				'email_type' 		=> 'html',
				'double_optin' 		=> $double_optin,
				'update_existing' 	=> true,
				'replace_interests' => true
			));

	}

	/**
	 * [save_ope_meta create meta data for operation model]
	 * @param  string $data		send meta string
	 * @return string       	writted value
	 */
	protected function save_ope_meta($data) {

		(empty($this->active_departement)) and $this->active_departement = 'unknown';

		$key = $data.'_'.$this->active_departement;

		if (empty($this->operation->$key)) {
			$this->operation->$key = 0;
		}

		$this->operation->$key ++;

		$this->operation->save();

		return $this->operation->$key;

	}


}


//  TEST --->
		// 
		/*	
				$this->departement = $departement;
				$img_path = 'img/newsletter/jeu'.DIRECTORY_SEPARATOR.$ope.DIRECTORY_SEPARATOR.$this->departement.DIRECTORY_SEPARATOR;
				// $theme = \Uri::base(false).\Theme::instance();
				$theme = \Theme::instance();
				$img[01] = \Uri::base(false).$theme->asset_path($img_path.'01.jpg');
										
				if (! file_exists(\Uri::base(false).$img[01])) {
					
					$img_path = 'img/newsletter/jeu'.DIRECTORY_SEPARATOR.$ope.DIRECTORY_SEPARATOR.$this->operation->departement.DIRECTORY_SEPARATOR;
					$img[01] = \Uri::base(false).$theme->asset_path($img_path.'01.jpg');

				}

				$img[02] = \Uri::base(false).$theme->asset_path($img_path.'02-filleul.jpg');
				$img[02] = \Uri::base(false).$theme->asset_path($img_path.'02.jpg');

				
				$data['surname'] = "laurent";
				$data['name'] = "marques";
				$data['filleul_url'] = "mimie@popo.fr";
				$content = \Theme::instance()->view('frontend/jeux/subscribed_content')->set($data);
				$subscribed_view->set('content',$content, false)
								->set('departement', $this->departement)
								->set('title', $title)
								->set('img', $img)
								->set('ope', $ope);

				echo "L'inscrit : ";
				echo $subscribed_view;
				echo '<br/><br/><br/>';

				$img[02] = \Uri::base(false).$theme->asset_path($img_path.'02-filleul.jpg');

				$content = \Theme::instance()->view('frontend/jeux/filleul_content')->set($data);
				$subscribed_view->set('content',$content, false)
							   ->set('departement', $this->departement)
							   ->set('title', $title)
							   ->set('img', $img)
							   ->set('ope', $ope);
				echo 'Le filleul : ';
				echo $subscribed_view;
				die();
		*/
		// <---- TEST