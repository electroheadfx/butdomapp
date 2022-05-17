<?php

namespace Admin;

class Controller_Api extends \Controller_Rest {

	public $datadepartements;

	public $campaignsfolders;

	public function before() {

		if ( ! \Auth::check() ) {

			\Message::danger('Accès refusé. Connectez-vous');
			\Response::redirect('/users/login');
			
		}

		\Config::load('but_mclist');
		$this->datadepartements = \Config::get('but_groups');
		$groupinterest 			= \Config::get('but_group_interest');

		foreach ($this->datadepartements as $key => $data) {
			$obj = new \stdClass();
			$obj->id           			= $data['id'];
			$obj->key          			= $key;
			$obj->title        			= $groupinterest[$key];
			$this->campaignsfolders[] 	= $obj;
		}

		parent::before();

	}

	/*  Call API url :
			limit = nombre de campaign à analyser
			departement : departement concerné pour la campaign
			use extension for campaignstats for array (php), json or xml
			Exemple : \Uri::base(false).'/admin/api/campaignstats.json?departement=reunion&limit=2' => http://but-dom.com/admin/api/campaignstats.json?departement=reunion&limit=2
	*/

	public function get_campaignstats() {

		$limit		 = \Input::get('limit') ? \Input::get('limit') : 2;
		$departement = \Input::get('departement') ? \Input::get('departement') : '';

		if (!array_key_exists($departement, $this->datadepartements)) {
			
			return NULL;

		} else {

			$folderId = $this->datadepartements[$departement]['id'];

			$this->campaignsfolders = \TinyChimp::campaigns( array(
						'filters' 	=> array(
						'list_id'	=> \Config::get('but_list_id'),
						'folder_id'	=> $folderId,
						'status'	=> 'sent',
					),
					'limit'	=> $limit,
				)
			)->data;

			// faire un foreach dans les campaigns pour prendre le titre de la campagne et insérer son contenu.
			foreach ($this->campaignsfolders as $campaign) {

				$data = explode('-', $campaign->title);

				$url = 'http://www.butdom.com/newsletter/'.$data[0].'-'.$data[1].'-'.$data[2].'-'.$departement.'/'.$departement.'/archive';			
				$content = file_get_contents($url);

				if (!empty($content)) {

					$campaign->archiveweb = $content;

				} else {

					$url = 'http://www.butdom.com/newsletter/'.$data[0].'-'.$data[1].'-'.$data[2].'-antilles/'.$departement.'/archive';
					$content = file_get_contents($url);

					if (!empty($content)) {

						$campaign->archiveweb = $content;

					} else {

						$campaign->archiveweb = '<p>No preview</p>';

					}

				}

			} // end Foreach compaign content

			return $this->response($this->campaignsfolders);

		}

	}


}
