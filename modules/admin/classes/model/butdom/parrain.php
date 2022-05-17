<?php

namespace Admin;

class Model_Butdom_Parrain extends \Orm\Model {

	protected static $_table_name = 'butdom_parrains';

	protected static $_properties = array(
		'id',
		'filleul_id',
		'parrain_id',
		'created_at',
		'updated_at',
		'operation_id',
		'used',
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
		'Orm\\Observer_Validation' => array(
			'events' => array('before_save')
		),
	);

	protected static $_has_one = array(
		'butdom_operations'		 => array(
				'key_from'		 => 'operation_id',
				'model_to'		 => 'Admin\Model_Butdom_Operation',
				'key_to'		 => 'id',
				'cascade_save' 	 => false,
        		'cascade_delete' => false,
		),
	);

	protected static $_belongs_to = array(
		'butdom_clients' => array(
			'key_from' => 'parrain_id',
			'model_to' => 'Admin\Model_Butdom_Client',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
		'butdom_operations' => array(
			'key_from' => 'operation_id',
			'model_to' => 'Admin\Model_Butdom_Operation',
			'key_to' => 'id',
			'cascade_save' => false,
			'cascade_delete' => false,
		),
		'butdom_inscriptions' => array(
			'key_from' => 'parrain_id',
			'model_to' => 'Admin\Model_Butdom_Inscription',
			'key_to' => 'client_id',
			'cascade_save' => false,
			'cascade_delete' => false,
		)
	);

	protected static $_to_array_exclude = array(
        'client_id',
    );
	

}
