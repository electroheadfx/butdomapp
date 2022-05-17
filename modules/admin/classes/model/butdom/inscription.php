<?php

namespace Admin;

class Model_Butdom_Inscription extends \Orm\Model {

	protected static $_table_name = 'butdom_inscriptions';

	protected static $_properties = array(
		'id',
		'client_id',
		'operation_id',
		'created_at',
		'updated_at',
	);

	protected static $_has_many = array(
		'butdom_parrains'		 => array(
				'key_from'		 => 'client_id',
				'model_to'		 => 'Admin\Model_Butdom_Parrain',
				'key_to'		 => 'parrain_id',
				'cascade_save' 	 => false,
        		'cascade_delete' => false,
		),
	);

	protected static $_has_one = array(
		'butdom_clients'		 => array(
				'key_from'		 => 'client_id',
				'model_to'		 => 'Admin\Model_Butdom_Client',
				'key_to'		 => 'id',
				'cascade_save' 	 => true,
        		'cascade_delete' => true,
		),
		'butdom_operations'		 => array(
				'key_from'		 => 'operation_id',
				'model_to'		 => 'Admin\Model_Butdom_Operation',
				'key_to'		 => 'id',
				'cascade_save' 	 => false,
        		'cascade_delete' => false,
		),
	);

	protected static $_belongs_to = array(
		'butdom_operations' => array(
			'key_from' => 'operation_id',
			'model_to' => 'Admin\Model_Butdom_Operation',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => true,
		),
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
