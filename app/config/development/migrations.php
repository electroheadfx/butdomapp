<?php
return array(
	'version' => 
	array(
		'app' => 
		array(
			'default' => 
			array(
				0 => '001_create_butdom_clients',
				1 => '002_create_butdom_invoices',
				2 => '003_create_butdom_parrains',
				3 => '004_create_butdom_meta',
				4 => '005_create_butdom_operations',
				5 => '006_add_operation_id_to_butdom_parrains',
				6 => '007_create_butdom_inscriptions',
				7 => '008_delete_used_to_butdom_parrains',
				8 => '009_add_used_to_butdom_parrains',
				9 => '010_add_title_to_butdom_operations',
				10 => '011_create_butdom_operations_meta',
				11 => '012_add_event_to_butdom_operations',
			),
		),
		'module' => 
		array(
		),
		'package' => 
		array(
			'auth' => 
			array(
				0 => '001_auth_create_usertables',
				1 => '002_auth_create_grouptables',
				2 => '003_auth_create_roletables',
				3 => '004_auth_create_permissiontables',
				4 => '005_auth_create_authdefaults',
				5 => '006_auth_add_authactions',
				6 => '007_auth_add_permissionsfilter',
				7 => '008_auth_create_providers',
				8 => '009_auth_create_oauth2tables',
				9 => '010_auth_fix_jointables',
			),
		),
	),
	'folder' => 'migrations/',
	'table' => 'migration',
);
