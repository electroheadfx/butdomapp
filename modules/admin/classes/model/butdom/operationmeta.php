<?php

namespace Admin;

class Model_Butdom_Operationmeta extends \Orm\Model {

	protected static $_table_name = 'butdom_operations_meta';

	protected static $_properties = array(
		'id',
		'operation_id',
		'key',
		'value',
		'created_at',
		'updated_at',
	);

	protected static $_belongs_to = array(
	    'butdom_operations' => array(
	        'key_from' => 'operation_id',
	        'model_to' => 'Admin\Model_Butdom_Operation',
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
