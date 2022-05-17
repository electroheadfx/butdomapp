<?php

namespace Admin;

class Controller_Dashboard extends \Controller_Base_Admin {

	public $datadepartements;

	public $campaignsfolders;

	public function before() {

		\Theme::instance()->asset->css(array('dashboard.css'), array(), 'header', false);
		\Theme::instance()->asset->js(array('custom/dashboard.js'), array(), 'footer', false);

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

	public function action_index() {

		$title 	= 'But Dom Dashboard';
		$view = \Theme::instance()->view('admin/dashboard');

		// Get Data of stats' Mailchimp Lists API
		$stats 					= \TinyChimp::lists()->data[3]->stats; // 0 is Darty martinique - 1 is Habitat Guyane - 2 is Habitat Martinique - 3 is BUT
		$listInterestGroupings 	= \TinyChimp::listInterestGroupings(array('id' => \Config::get('but_list_id')))[0];
		
		// work For mailchimp 2
		// $mailchimp = new \Mailchimp();
		// $stats = $mailchimp->lists->getList();
		// echo '<pre>';
		// var_dump($stats);
		// die();

		$data['departements'] 			= new \stdClass();
		$groups = $listInterestGroupings->groups;
		$data['departements']->groups 	= array($groups[1],$groups[2],$groups[0],$groups[3],$groups[4]);
		$data['campaignsfolders'] 		= $this->campaignsfolders;

		// Load Api description API translation
		\Config::load('mailchimp_api');
		$listStats = \Config::get('lists')['stats'];
		$depStats = \Config::get('groups');

		// Assign all API Data requests object in data array for view
		foreach ($listStats as $key => $api) {
			if ($api['active']) {
				$prop 			= new \stdClass();
				$prop->data 	= ($api['float'] == 0) ? $stats->$key : number_format($stats->$key,$api['float']);
				$prop->title 	= $api['title'];
				// echo 'title : '.$key.' - data : '.$prop->data.'<br/>';
				if (isset($api['icon'])) {
					$prop->icon = $api['icon'];
				}
				if (isset($api['html'])) {
					$prop->html = $api['html'];
				}
				$prop->badge 	= isset($api['badge']) ? $api['badge'] : 'label-default';
				$prop->desc 	= empty($api['desc']) ? $api['desc_en'] : $api['desc'];
				$prop->class 	= !isset($api['class']) ? 'btn-default' : $api['class'];
				$data[$key] 	= $prop;
				$data['api'][]	= $key;
			}
		}
		// die();

		\Theme::instance()->get_template()->set('title', $title);
		\Theme::instance()->set_partial('content', $view)->set($data,null, false);
	}

/* A SUPPRIMER

	public function action_campaignstats($departement = '', $limit = 2) {

		if (!array_key_exists($departement, $this->datadepartements)) {
			
			return NULL;

		} else {

			$folderId = $this->datadepartements[$departement]['id'];

			// foreach ($this->campaignsfolders as $folder) {

			// $data['departements']->groups[] = $folder->title;

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

			// echo '<pre>';
			// var_dump( $this->campaignsfolders );
			// die();
			return $this->campaignsfolders;

		}

	} */

}
