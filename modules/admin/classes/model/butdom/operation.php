<?php

namespace Admin;

class Model_Butdom_Operation extends \Orm\Model {

	protected static $_table_name = 'butdom_operations';
	
	protected static $_properties = array(
		'id',
		'name' 	=> array(
			'data_type' 	=> 'string',
			'label'			=> 'Uri',
			'form'			=> array('type' => 'text'),
			'validation'	=> array('max_length' => array(255) )
		),
		'departement'	=> array(
			'data_type' 	=> 'enum',
			'label'			=> 'Département',
			'form'			=> array('type' => 'select', 'options' => array(null => 'Choisissez ...','tous'=>'Tous les départements','reunion'=>'Réunion','guadeloupe'=>'Guadeloupe','martinique'=>'Martinique','guyane'=>'Guyane')),
			'validation'	=> array('required')
		),
		'created_at' => array(
			'data_type' 	=> 'int'
		),
		'updated_at' => array(
			'data_type' 	=> 'int',
			'label' 		=> 'Updated_ At',
			'form' 			=> array( 'type' => false) // this prevents this field from being rendered on a form
        ),
        'title' 	=> array(
        	'data_type' 	=> 'string',
        	'label'			=> 'Titre',
        	'form'			=> array('type' => 'text'),
        	'validation'	=> array('max_length' => array(255) )
        ),
        'event' 	=> array(
        	'data_type' 	=> 'string',
        	'label'			=> 'Event',
        	'form'			=> array('type' => 'text'),
        	'validation'	=> array('max_length' => array(255) )
        ),

	);

	protected static $_has_many = array(
		'butdom_parrains'		 => array(
				'key_from'		 => 'id',
				'model_to'		 => 'Admin\Model_Butdom_Parrain',
				'key_to'		 => 'operation_id',
				'cascade_save' 	 => false,
        		'cascade_delete' => false,
		),
		'butdom_inscriptions'		 => array(
				'key_from'		 => 'id',
				'model_to'		 => 'Admin\Model_Butdom_Inscription',
				'key_to'		 => 'operation_id',
				'cascade_save' 	 => false,
        		'cascade_delete' => false,
		),
		'butdom_operations_meta' => array(
				'key_from' 		 => 'id',
				'key_to' 		 => 'operation_id',
				'model_to' 		 => 'Admin\Model_Butdom_Operationmeta', 
				'cascade_save' 	 => true,
				'cascade_delete' => true,
		),
	);

	protected static $_eav = array(
		'butdom_operations_meta' => array(	
			'attribute' => 'key',
			'value' 	=> 'value',
		)
	);

	protected static $_belongs_to = array(
		'butdom_inscriptions' => array(
			'key_from' => 'id',
			'model_to' => 'Admin\Model_Butdom_Inscription',
			'key_to' => 'operation_id',
			'cascade_save' => false,
			'cascade_delete' => false,
		),
		'butdom_parrains' => array(
			'key_from' => 'id',
			'model_to' => 'Admin\Model_Butdom_Parrain',
			'key_to' => 'operation_id',
			'cascade_save' => false,
			'cascade_delete' => false,
		),
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

	public static function get_departements_form() {
		return self::$_properties['departement']['form']['options'];
	}

}
