<?php

namespace Frontend;

class Controller_Operation extends \Controller_Base_Public {

	public $group_interest;
	public $departement;
	public $mailchimpID;
	public $operation_id;

	public function before() {

		\Config::load('but_mclist');
		$this->group_interest = \Config::get('but_group_interest');
		$this->mailchimpID = \Config::get('but_list_id');
		return parent::before();
	}


#################################################################################################################
#																												#
#																												#
#																												#
# 					    OPERATION COMMUNE sans suivi client parrains/filleul 									#
#																												#
#																												#
#																												#
#################################################################################################################

	public function action_index($action = NULL, $departement = NULL) {

		$this->departement = $departement;

		(strpos("reunion,guadeloupe,guyane,martinique,stmartin", $departement) === false) and $departement = "";
		$depname = ($departement == '') ? '-tous' : '-'.$departement;

		$this->view = ($departement == '' || ! file_exists(\Theme::instance()->find('default')."templates/email/frontend/operation/$departement/index.php") ) ? "templates/email/frontend/operation/index" : "templates/email/frontend/operation/$departement/index";
		$departement_field = $departement;
		$departement == 'reunion' and $departement = 'réunion';

		$action = ucfirst($action) ?: 'Inscription';
		$action .= ' '.ucfirst($departement) ?: '';

		$parrainage = ($action == 'Parrainage') ? true : false;

		$newsletter_view = \Theme::instance()->view($this->view);
		
		####
		$extract_title = explode("title>", $newsletter_view);
		$title = str_replace('</', '', $extract_title[1]);
		####

		$antilles = array('guyane','martinique', 'guadeloupe');

		$client_m 	= \Admin\Model_Butdom_Client::forge();
		$fieldset 	= \Fieldset::forge('registration')->add_model($client_m)->repopulate();
		$fieldset->disable('telephone');
		$fieldset->field('departement')->set_value($departement_field);

		$form = $fieldset->form();

		if (!$parrainage) {
			$form->add('filleul', 'Parrainer un ami (email)', array('type' => 'email', 'class' => 'parrain'), array('valid_email') );
			$form->add('checkbox', '', array('type' => 'checkbox', 'class' => 'optin', 'options' => array("J’accepte de recevoir les actualités des magasins Cafom"), 'value' => null, 'disabled' => 'disabled' ));
			$form->add('submit', '', array('type' => 'submit', 'value' => 'Je m\'inscris', 'class' => 'btn btn-primary'));
		} else {
			$form->add('submit', '', array('type' => 'submit', 'value' => 'Je parraine', 'class' => 'btn btn-primary'));
		}

		// $form->field('telephone')->delete_rule('required');

		if (\Input::param() != array() && \Input::post()) {

			// Test si le formulaire a été soumis
			if ($fieldset->validation()->run()) {

				// create the mode inject the data with Try
				try {

					$fields = $fieldset->validated();
					$client_m->email 		= $fields['email'];
					$client_m->name 		= $fields['name'];
					$client_m->surname 		= $fields['surname'];
					$client_m->departement 	= $fields['departement'];

					// Test if exist in mailchimp base, is true $confirmed = "approved" else not "pending"
					$client_m->confirmed 	= \Admin\Model_Butdom_Client::is_mailchimp($fields['email']) === true ? 'approved' : 'pending';
					
					$client_m->token 		= \Security::generate_token();
					$query_client 			= \Admin\Model_Butdom_Client::query()->where('email', '=', $fields['email'])->get_one();

					# préparer l'objet newsletter ici pour envoyer ci-après :
					$email = \Email::forge();
					$email->from('contact@butdom.com','Votre magasin BUT');
					$email->to($fields['email'], $fields['name']);
					// $email->subject("Préparez la coupe du monde avec -20% sur les fauteuils de relaxation !");
					$email->subject($title);
					// $data = $fields;
					// $data['region'] = in_array($fields['departement'], $antilles) ? 'antilles' : $fields['departement'];
					// $data['token'] = $client_m->token;
					// $newsletter_view->set($data);
					$email->html_body($newsletter_view);

					# Prépare mailchimp
					$merge_vars 		= array(
							  'FNAME'		=> $client_m->surname,
							  'LNAME'		=> $client_m->name,
							  'PARTENAIRE' 	=> 'non', //'non' ou 'oui'
							  'GROUPINGS'	=>array(
							            		array('name' => 'Votre département', 'groups'=> $this->group_interest[$client_m->departement])
							       			)
					);

					#### TEST >
						// return \Theme::instance()->view($this->view, $data);
						// return $newsletter_view;
						// die();
					#### < TEST

					// Si le client existe déja
					if (isset($query_client)) {

						\Message::info('Le compte ' . $client_m->email . ' a déjà été créé. Vous allez recevoir la newsletter pour bénéficier de l\'offre.');
						$vue = 'frontend/jeux/octobre/success';
						$email->send();
						$id = $query_client->id;

						if ($client_m->confirmed == 'approved' ) {

							if ($query_client->confirmed != 'approved') {
								$query_client->confirmed = 'approved';
								$query_client->save();
							}

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

					// si le client n'existe pas
					} else {

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
							
							\Message::info('Merci de vous être inscrit(e), vous allez recevoir la newsletter pour bénéficier de l\'offre.');
							$vue = 'frontend/jeux/octobre/success';
							$email->send();
							$id = $client_m->id;
						}

					}

					# Si c'est pas la page de parrainage, envoyez ici la newsletter au filleul et sauve son model si il y a un filleul
					if (!$parrainage) {
						if ( \Input::post('filleul') ) {

							$filleul_mail 	= \Input::post('filleul');
							$email_fil 		= \Email::forge();
							$email_fil->from('contact@butdom.com','Votre magasin BUT');
							$email_fil->to( $filleul_mail , $filleul_mail);
							$email_fil->subject($fields['name'] . ' vous recommande : ' . $title);
							// $data_fil = $fields;
							// $data_fil['region'] = in_array($fields['departement'], $antilles) ? 'antilles' : $fields['departement'];
							// $data_fil['filleul_mail'] = $filleul_mail;

							// $newsletter_view->set($data_fil);
							$email_fil->html_body($newsletter_view);
							$email_fil->send();

							#### TEST >
								// return \Theme::instance()->view($this->view);
								// return $newsletter_view;
								// die();
							#### < TEST

							// test if filleul exist
							$query_fil = \Admin\Model_Butdom_Client::query()->where('email', '=', $filleul_mail)->get_one();
							
							// filleul n'existe pas
							if (!isset($query_fil)) {

							// store filleul in DB
								$filleul 				= \Admin\Model_Butdom_Client::forge();
								$filleul->email 		= $filleul_mail;
								$filleul->departement 	= $fields['departement'];
								$filleul->confirmed 	= \Admin\Model_Butdom_Client::is_mailchimp($filleul_mail) === true ? 'approved' : 'pending';
								$filleul->token 		= \Security::generate_token();

								// send Mailchimp double optin
								\TinyChimp::listSubscribe( array(
									'id' 				=> \Config::get('but_list_id') ,
									'email_address' 	=> $filleul->email,
									'merge_vars' 		=> $merge_vars,
									'email_type' 		=> 'html',
									'double_optin' 		=> true,
									'update_existing' 	=> true,
									'replace_interests' => true
								));
								// save filleul model in client table
								$filleul->save();
								// save parrain relationship with filleul
								$parrain 			 = \Admin\Model_Butdom_Parrain::forge();
								$parrain->filleul_id = $filleul->id;
							// Added Fix here for operation_id
								$parrain->operation_id 	= 0;
								$parrain->used 		 	= 0;
								$parrain->parrain_id 	= $id;
								$parrain->save();
							}
						}
					}

				// Une erreur s'est produite dans les models
				} catch (\Orm\ValidationFailed $e) {

					\Message::danger('Une erreur s\'est produite dans l\'enregistrement, contacter <a href="mailto:dev@b2see.com">ici support</a>.');
					\Log::warning('Un erreur s\'est produite en frontend : FuelException throw an Error :' .$e->getMessage() );
				}
			// end Posts test
			} else {

				// des erreurs dans le formulaire
				\Message::danger('Erreur dans les champs, veuillez les vérifier, merci.');

			}

		} // end \Input::post test

		$view = \Theme::instance()->view('frontend/operation/index');

		$img = \Theme::instance()->asset_path('img/ope-inscription'.$depname.'.jpg');
		$img_all = \Theme::instance()->asset_path('img/ope-inscription-tous.jpg');

		! file_exists($img_all) and $img_all = '';

		$source = file_exists($img) ? $img : $img_all;

		$view->set('form', $form->build(), false)->set('departement', $departement)->set('source', $source);

		\Theme::instance()->asset->js(array('bootstrap3/dropdown.js','datepicker/bootstrap-datepicker.js'), array(), 'footer', false);
		\Theme::instance()->asset->css(array('cartecadeau.css'), array(), 'header', false);
		\Theme::instance()->get_template()->set('title', $title);
		\Theme::instance()->set_partial('content', $view )->set('action', $action);

	}



#################################################################################################################
#																												#
#																												#
#																												#
#   JEU Special Martinique 'OUVERTURE' ou futur Operations qui a besoin d'un suivi client parrains/filleul 		#
#																												#
#																												#
#																												#
#################################################################################################################

	/**
	 * [action_ouverture description]
	 * @param  string $departement [martinique, reunion, guadeloupe, stmartin, guyane]
	 * @param  string $operation [voir ID butdom_operations]
	 * @return Template response           
	 */
	
	public function action_ouverture($action, $code_parrain_id = NULL) {

		# Select the operation ID in table and the departement
		$this->operation_id 	= 1;
		$this->departement 		= 'martinique';

		# Setup Operation data
		$action 				= ucfirst($action);
		$title 					= 'Gagnez une voiture : Grand jeu du 17 février au 1er mars 2014';
		$subject_subscribed 	= 'Gagnez une voiture : confirmation d\'inscription.';
		$subject_filleul		= 'Votre parrain vous invite à gagner une voiture chez BUT';
		$ope 					= 'ouverture';
		$source 				= '/assets/img/';

		# Setup date for view and email
		$data['action'] 		= 'Remplissez le formulaire d\'inscription';
		$data['title'] 			= $title;
		$data['source'] 		= $source;
		$data['ope'] 			= $ope;
		$data['departement'] 	= $this->departement;

		# Setup transactionnal emails view and page view
		$subscribed_view 		= \Theme::instance()->view('frontend/jeux/subscribed');
		$filleul_view 			= \Theme::instance()->view('frontend/jeux/filleul');
		$view 					= \Theme::instance()->view('frontend/operation/ouverture');

		# Setup Here CSS/JS and template
		\Theme::instance()->asset->css(array('ouverture.css'), array(), 'header', false);

		## Create fieldsets and additionnal forms
		$client_m 	= \Admin\Model_Butdom_Client::forge();
		$fieldset 	= \Fieldset::forge('registration');
		$fieldset->form()->add_csrf();
		$fieldset->add_model($client_m);

#!		#### -> Will do a foreach of EAV fields to add, same for EAV model save
		// $form->add('address', 'Adresse postale : ', array('type' => 'text'))->add_rule('required')->set_error_message('required','L\'adresse doit être connue pour le jeu.')->set_value(\Input::post('address'));
		
		$fieldset->add('legendfilleul')->set_template('<h4 class="but">Augmentez vos chances de gagner<br/>en parrainant vos amis<sup>(1)</sup> :</h4>');
		$fieldset->add('filleul[0]', 'Email ami 1 : doublez vos chances :', array('type' => 'email', 'class' => 'parrain'), array('valid_email'))->set_error_message('valid_email','L\'email doit être valide.');
		$fieldset->add('filleul[1]', 'Email ami 2 : triplez vos chances :', array('type' => 'email', 'class' => 'parrain'), array('valid_email'))->set_error_message('valid_email','L\'email doit être valide.');
		$fieldset->add('filleul[2]', 'Email ami 3 : quadruplez vos chances :', array('type' => 'email', 'class' => 'parrain'), array('valid_email'))->set_error_message('valid_email','L\'email doit être valide.');

		$fieldset->field('email')->set_error_message('required','L\'email doit être connu.')->set_error_message('valid_email','L\'email doit être valide.');
		$fieldset->add_after('confirm_email', 'Confirmez votre email : ', array('type' => 'email'), array(), 'email')->add_rule('match_field','email')->set_error_message('match_field','L\'email de confirmation doit être identique à l\'email.');
		$fieldset->field('name')->add_rule('required')->set_error_message('required','Le nom doit être connu.');
		$fieldset->field('surname')->add_rule('required')->set_error_message('required','Le prénom doit être connu.');
		$fieldset->field('telephone')->add_rule('required')->set_error_message('match_pattern','Le champs téléphone doit contenir 10 chiffres.')->set_error_message('required','Le champs téléphone doit être connu.');
		
#!		#### Added set template for hidden class the field, later do a if condition : display departement if equal to tous, hidden for specific departement
		$fieldset->field('departement')->set_template("\t\t<div class=\"control-group hidden\">\n\t\t\t<div class=\"control-label\">{label}{required}</div>\n\t\t\t<div class=\"controls\">{field} {description} {error_msg}</div></div>\n\t\t\n")->set_value($this->departement);
		
		#### Setup additionnal checkbox here
		$fieldset->add('checkbox', '', array('type' => 'checkbox', 'class' => 'optin', 'options' => array("J’accepte de recevoir les actualités des magasins Cafom"), 'value' => null, 'disabled' => 'disabled' ));
		$fieldset->add('submit', '', array('type' => 'submit', 'value' => 'Je m\'inscris', 'class' => 'btn btn-primary'));

		$fieldset->repopulate();

		#### POST Logic
		if (\Input::param() != array() && \Input::post()) {

			# Fieldset is running
			if ($fieldset->validation()->run()) {

				$fields = $fieldset->validated();

				try {
					$client_m->email 		= $fields['email'];
					$client_m->name 		= $fields['name'];
					$client_m->surname 		= $fields['surname'];
					$client_m->departement 	= $fields['departement'];
					$client_m->telephone 	=  preg_replace("/[\s\-\/\:]/","", $fields['telephone']);

#!					#### -> Will Do a foreach of EAV attributes to add if there
					// $client_m->address 		= $fields['address'];

					// Test if exist in mailchimp base, is true $confirmed = "approved" else not "pending"
					$client_m->confirmed 	= \Admin\Model_Butdom_Client::is_mailchimp($fields['email']) === true ? 'approved' : 'pending';
					$client_m->token 		= \Security::generate_token();
					
					#### send Mailchimp double optin
					if ($client_m->confirmed != 'approved') {
						$this->register_to_mailchimp($client_m->surname, $client_m->name, $client_m->email, true);
					}

					$query_client = \Admin\Model_Butdom_Client::query()->where('email', '=', $fields['email'])->where('departement', '=', $fields['departement'])->get_one();
					$save_client = false;

					#### Logic if client exist already or not
					if (isset($query_client)) {
						$query_client->name 		= $client_m->name;
						$query_client->surname 		= $client_m->surname;
						$query_client->telephone 	= $client_m->telephone;

#!						### -> Will Do a foreach of EAV attributes to add if there						
						// $query_client->address 		= $client_m->address;
						if ($client_m->confirmed == 'approved' && $query_client->confirmed != 'approved') {
							$query_client->confirmed = 'approved';
						}
						if ($query_client->save()) {
							$save_client = true;
							$clientID = $query_client->id;
						}

					} else {

						if ($client_m->save()) {
							$save_client = true;
							$clientID = $client_m->id;
						}
					}

					if ($save_client) {

						// Get data for views and transactionnal emails
						$data['email'] 		= $fields['email'];
						$data['name'] 		= ucfirst($fields['name']);
						$data['surname'] 	= ucfirst($fields['surname']);

						#### Logic inscription
						$inscription_passed = false;
						$query_inscription = \Admin\Model_Butdom_Inscription::query()->where('client_id', '=', $clientID)->where('operation_id', '=', $this->operation_id)->get_one();
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
							$inscription->operation_id 	= $this->operation_id;

							if ($inscription->save()) {
								$inscription_passed = true;
								\Message::info(ucfirst($fields['name']) .' '. ucfirst($fields['surname']).', vous allez recevoir un email de la confirmation de votre inscription.');
								$view = 'frontend/operation/success';

							} else {
								\Message::danger('Une erreur s\'est produite dans l\'enregistrement, contacter <a href="mailto:dev@b2see.com">ici support</a>.');
							}
						}

						if ($inscription_passed) {

							$email = \Email::forge();
							$email->from('contact@butdom.com','Votre magasin BUT');
							$email->to($fields['email'], $data['name']);
							$email->subject($subject_subscribed);
							$content = \Theme::instance()->view('frontend/jeux/subscribed_content')->set($data);
							$email->html_body($subscribed_view->set('content',$content, false)->set('departement', $this->departement));
							$email->send();
						}

						#### Logic emails filleul, foreach in filleuls fields and test them
						foreach ($fields['filleul'] as $filleul_mail) {
							if ( !empty($filleul_mail) && $filleul_mail != $fields['email']  ) {

								$filleul_url = urldecode(\Uri::base(false).'operation'.DIRECTORY_SEPARATOR.$ope.DIRECTORY_SEPARATOR.'parrainage'.DIRECTORY_SEPARATOR.$clientID);
								$data['filleul'] 	= $filleul_mail;
								$data['filleul_url']= $filleul_url;
								$email_fil 			= \Email::forge();
								$email_fil->from('contact@butdom.com','Votre magasin BUT');
								$email_fil->to( $filleul_mail , $filleul_mail);
								$email_fil->subject($subject_filleul); 
								$content = \Theme::instance()->view('frontend/jeux/filleul_content')->set($data);
								$email_fil->html_body($filleul_view->set('content',$content, false)->set('departement', $this->departement));

								// get if filleul exist
								$query_fil = \Admin\Model_Butdom_Client::query()->where('email', '=', $filleul_mail)->get_one();
								
								// filleul doesn't exist
								if (!isset($query_fil)) {
									// store filleul in DB
									$filleul 				= \Admin\Model_Butdom_Client::forge();
									$id_filleul 			= $filleul->id;
									$filleul->email 		= $filleul_mail;
									$filleul->departement 	= $fields['departement'];
									$filleul->confirmed 	= \Admin\Model_Butdom_Client::is_mailchimp($filleul_mail) === true ? 'approved' : 'pending';
									$filleul->token 		= \Security::generate_token();
									$filleul->save();
									$id_filleul = $filleul->id;
								} else {
									$id_filleul = $query_fil->id;
								}

								// save parrain relationship with filleul
								$parrain 			 	= \Admin\Model_Butdom_Parrain::forge();
								$parrain->filleul_id 	= $id_filleul;
								$parrain->parrain_id 	= $clientID;
								$parrain->operation_id 	= $this->operation_id;
								$parrain->used 		 	= 0;
								if ($parrain->save()) {
									$email_fil->send();
								}
							}
						} // end foreach $filleuil emails

						#### Logic if user is parrain
						if ($code_parrain_id) {

							$filleulclient = \Admin\Model_Butdom_Parrain::query()
												->where('operation_id','=',$this->operation_id)
												->where('used','!=', $this->operation_id)
												->where('parrain_id', '=', $code_parrain_id)
												->get_one();
							if ($filleulclient) {
								# code parain is valid, we store it
								$filleulclient->used = $this->operation_id;
								$filleulclient->save();
							}
						}
						\Theme::instance()->get_template()->set('title', $title)->set('ope', $source.$ope.'-bg.jpg');
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
		\Theme::instance()->get_template()->set('title', $title)->set('ope', $source.$ope.'-bg.jpg');
		\Theme::instance()->set_partial('content', $view )->set($data);

	}

	# end ouverture


##############################################
#											 #
# Function for ouverture : send doubleoptin  #
#											 #
##############################################
	 
	protected function register_to_mailchimp($surname, $name, $email, $double_optin = false) {

			$merge_vars = array(
					  'FNAME'		=> $surname,
					  'LNAME'		=> $name,
					  'PARTENAIRE' 	=> 'non', //'non' ou 'oui'
					  'GROUPINGS'	=>array(
					            		array('name' => 'Votre département', 'groups'=> $this->group_interest[$this->departement])
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


}


/* 	
// Test view here
	$data['name'] = 'lolo';
	$data['surname'] = 'lola';
	$data['filleul_url'] = 'lola@lolo';
	$content_s = \Theme::instance()->view('frontend/jeux/subscribed_content')->set($data);
	$content_f = \Theme::instance()->view('frontend/jeux/filleul_content')->set($data);
	$subscribed_view->set('content',$content_s, false)->set('departement', $this->departement);
	$filleul_view->set('content',$content_f, false)->set('departement', $this->departement);
	\Theme::instance()->get_template()->set('title', $title);
	\Theme::instance()->set_partial('content', $filleul_view)->set($data);
// test view on mandrill here
	$email = \Email::forge();
	$email->from('contact@butdom.com','Votre magasin BUT');
	$email->to('dev@b2see.com', 'laurent');
	$email->subject('test');
	$email->html_body($subscribed_view);
	$email->send();
	return null;	
*/