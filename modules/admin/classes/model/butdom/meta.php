<?php

namespace Admin;

class Model_Butdom_Meta extends \Orm\Model {

	protected static $_table_name = 'butdom_meta';

	protected static $_properties = array(
		'id',
		'client_id',
		'key',
		'value',
		'created_at',
		'updated_at',
	);


	protected static $_belongs_to = array(
	    'butdom_clients' => array(
	        'key_from' => 'client_id',
	        'model_to' => 'Admin\Model_Butdom_Client',
	        'key_to' => 'id',
	        'cascade_save' => true,
	        'cascade_delete' => true,
	    )
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
	);

}
