<?php
/**
 * BUT Mailchimp List
 */
 
return array(
	

	'but_list_id' 		=> '9186109541',

	'but_group_interest'=> array('guadeloupe' => 'Guadeloupe', 'martinique' => 'Martinique', 'guyane' => 'Guyane', 'reunion' => 'Réunion', 'saintmartin' => 'Saint-Martin'),
	

	'but_groups'		=>	array(	"guadeloupe" 	=> array("id" => 2777, "title" => "Guadeloupe"),
									"guyane" 		=> array("id" => 2769, "title" => "Guyane"),
									"martinique" 	=> array("id" => 2781, "title" => "Martinique"),
									"reunion" 		=> array("id" => 2773, "title" => "Réunion"),
									// "saintmartin" 	=> array("id" => 4625, "title" => "Saint-Martin")
						),

	'stats_jeux'		=> array(	"OptinActif"			=> array( 'title' => 'Activité du jeu', 			'desc' => "Total des tentatives d'optin au formulaire du jeu.", 									'style' => 'success', 	'icon' => 'tasks'),
									"OptinNewOpe"			=> array( 'title' => 'Nouveaux à ce jeu', 			'desc' => "Total des optins nouveaux à l'activité du jeu en cours.", 								'style' => 'info', 		'icon' => 'tag'),
									"OptinNewMailchimp"		=> array( 'title' => 'Nouveaux clients Mailchimp', 	'desc' => "Total des optins de nouveaux clients mailchimp.", 					'style' => 'danger', 	'icon' => 'user'),
									// "OptinNew"				=> array( 'title' => 'Nouveaux à tous les jeux',	'desc' => "Total des optins nouveaux à toutes les activités de jeux ou opérations.", 	'style' => 'warning', 	'icon' => 'tags'),
									"OptinMailchimp"		=> array( 'title' => 'Clients Mailchimp', 			'desc' => "Total des optins de clients mailchimp.", 									'style' => 'default', 	'icon' => 'check'),
						),
);