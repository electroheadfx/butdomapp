<?php

namespace Admin;

class Model_Butdom_Invoice extends \Orm\Model {
	
	protected static $_table_name = 'butdom_invoices';

	protected static $_properties = array(
		'id',
		'client_id',
		'number'	=> array(
			'data_type' 	=> 'int',
			'label'			=> 'Numéro de Facture',
			'form'			=> array('type' => 'text'),
			'validation'	=> array('required', 'exact_length' => array(6), 'valid_string' => array('numeric') )
		),
        'date' => array(
			'data_type' 	=> 'int',
			'label' 		=> 'Date de la facture',
			'form' 			=> array('type' => 'text'),
			'validation'	=> array('required', 'exact_length' => array(10), 'valid_string' => array('numeric'))
        ),
	);

	protected static $_observers = array(
		'Orm\\Observer_Validation' => array(
			'events' => array('before_save')
		),
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

	protected static $_to_array_exclude = array(
        'client_id',
    );

}
