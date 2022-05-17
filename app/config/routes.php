<?php
return array(

// Frontend routes

	'_root_'  										=> 'frontend/jeux/loterie',  // The default route
	'_404_'   										=> 'frontend/404',    		// The main 404 route
	'loterie' 										=> 'frontend/jeux/loterie',

	'operation' 									=> 'frontend/operation/index/inscription',
	'operation/inscription' 						=> 'frontend/operation/index/inscription',
	'operation/parrainage' 							=> 'frontend/operation/index/parrainage',
	'operation/inscription/(:segment)' 				=> 'frontend/operation/index/inscription/$1',
	'operation/parrainage/(:segment)' 				=> 'frontend/operation/index/parrainage/$1',


	'jeu/(:segment)' 								=> 'frontend/jeux/index/$1',
	'jeu/(:segment)/(:segment)' 					=> 'frontend/jeux/index/$1/$2',
	'jeu/(:segment)/(:segment)/(:segment)' 			=> 'frontend/jeux/index/$1/$2/$3',

	// special operations
		// 'operation/ouverture' 						=> 'frontend/operation/ouverture/inscription',
		// 'operation/ouverture/inscription' 			=> 'frontend/operation/ouverture/inscription',
		// 'operation/ouverture/parrainage' 			=> 'frontend/operation/ouverture/inscription',
		// 'operation/ouverture/parrainage/(:segment)' => 'frontend/operation/ouverture/parrainage/$1',
	// 'operation/ouverture(/*.*)' 					=> 'frontend/404/expiration',

// Admin routes

	'mailchimp/webhook' 							=> 'api/mailchimp/index',

	'login' 										=> 'users/login',
	
	'admin/dashboard' 								=> 'admin/dashboard/index',

	'admin/clients' 								=> 'admin/list/clients/all/simple',
	'admin/clients/(:segment)' 						=> 'admin/list/clients/$1/simple',
	'admin/clients/(:segment)/(:segment)' 			=> 'admin/list/clients/$1/$2',
	'admin/clients/(:segment)/(:segment)/(:segment)'=> 'admin/list/clients/$1/$2/$3',
	
	'newsletter' 									=> 'admin/newsletter/index',

	'admin/invoices/(:segment)' 					=> 'admin/list/invoice/$1',
	'admin/invoices' 								=> 'admin/list/invoice',
	
	'admin/ramdon/(:segment)' 						=> 'admin/list/invoice/$1',
	'admin/ramdon'			 						=> 'admin/list/invoice',

	'admin/support' 								=> 'admin/static/support/index',

	
);