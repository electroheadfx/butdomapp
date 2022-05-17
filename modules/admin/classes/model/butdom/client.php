<?php

namespace Admin;

class Model_Butdom_Client extends \Orm\Model {

	protected static $_table_name = 'butdom_clients';

	protected static $_properties = array(
		'id',
		'email' 	=> array(
			'data_type' 	=> 'string',
			'label'			=> 'E-mail :',
			'form'			=> array('type' => 'text'),
			'validation'	=> array('required', 'valid_email', 'max_length' => array(255))
		),
		'surname'	=> array(
			'data_type' 	=> 'string', 
			'label'			=> 'Prénom :',
			'form'			=> array('type' => 'text'),
			'validation'	=> array('max_length' => array(100) )
		),
		'name'	=> array(
			'data_type' 	=> 'string',
			'label'			=> 'Nom :',
			'form'			=> array('type' => 'text'),
			'validation'	=> array('max_length' => array(100) )
		),
		'telephone'	=> array(
			'data_type' 	=> 'int', 
			'label'			=> 'Téléphone :',
			'form'			=> array('type' => 'text'),
			'validation'	=> array('match_pattern' => array("#^[0-9]{2}[\s\-/:]*[0-9]{2}[\s\-/:]*[0-9]{2}[\s\-/:]*[0-9]{2}[\s\-/:]*[0-9]{2}$#"))
		),
		'departement'	=> array(
			'data_type' 	=> 'enum',
			'label'			=> 'Département :',
			'form'			=> array('type' => 'select', 'options' => array(null => 'Choisissez ...','reunion'=>'Réunion','guadeloupe'=>'Guadeloupe','martinique'=>'Martinique','guyane'=>'Guyane')), //,'stmartin'=>'Saint Martin'
			'validation'	=> array('required')
		),
		'confirmed'			=> array(
			'form' 			=> array( 'type' => false),
		),
		'token'			=> array(
			'data_type' 	=> 'string',
			'form' 			=> array( 'type' => false),
			'validation'	=> array('max_length' => array(255) )
		),
		'created_at' => array(
			'data_type' 	=> 'int',
			'label' 		=> 'Created At',
			'form' 			=> array('type' => false)
        ),
		'updated_at' => array(
			'data_type' 	=> 'int',
			'label' 		=> 'Updated_ At',
			'form' 			=> array( 'type' => false) // this prevents this field from being rendered on a form
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
		'Orm\\Observer_Validation' => array(
			'events' => array('before_save')
		),
		
	);

	protected static $_has_many = array(
		'butdom_invoices'		 => array(
				'key_from'		 => 'id',
				'model_to'		 => 'Admin\Model_Butdom_Invoice',
				'key_to'		 => 'client_id',
				'cascade_save' 	 => true,
        		'cascade_delete' => true,
		),
		'butdom_parrains'		 => array(
				'key_from'		 => 'id',
				'model_to'		 => 'Admin\Model_Butdom_Parrain',
				'key_to'		 => 'parrain_id',
				'cascade_save' 	 => true,
        		'cascade_delete' => true,
		),
		'butdom_meta' => array(
				'key_from' 		 => 'id',
				'model_to' 		 => 'Admin\Model_Butdom_Meta', 
				'key_to' 		 => 'client_id',
				'cascade_save' 	 => true,
				'cascade_delete' => true,
		),
	);

	protected static $_belongs_to = array(
		'butdom_inscriptions' => array(
			'key_from' => 'id',
			'model_to' => 'Admin\Model_Butdom_Inscription',
			'key_to' => 'client_id',
			'cascade_save' => true,
			'cascade_delete' => true,
		)
	);

	protected static $_eav = array(
		'butdom_meta' => array(	
			'attribute' => 'key',
			'value' 	=> 'value',
		)
	);

	protected static $_to_array_exclude = array(
        'confirmed','token'
    );


    public static function is_mailchimp($email) {

    	\Config::load('but_mclist');
    	$ret = \TinyChimp::listMemberInfo(array( 'id' => \Config::get('but_list_id'),'email_address' => $email));

    	if ($ret->success === 1 && $ret->data[0]->status == "subscribed" ) {
    			return true;

    	} else {

    		return false;
    	}

    }

    public static function get_clients($departement, $confirmed, $operation_id = 0) {

    	$operation_id < 0 and $operation_id = 0;

    	if ($departement == "all") {

    		$order_by = array( 'departement' => 'desc', 'name' => 'asc');
    		$where_dep = ($confirmed == 'all') ? array(array('confirmed','!=', 'refused')) : array(array('confirmed', $confirmed));

    	} else {

    		$order_by = array( 'name' => 'asc');
    		$where_dep = ($confirmed == 'all') ? array(array('confirmed','!=', 'refused'), array('departement', $departement)) : array(array('confirmed', $confirmed), array('departement', $departement));
    	}

    	$clients = static::query()->where($where_dep)->order_by($order_by);

    	if ($operation_id > 0) {

    		$clients->related('butdom_inscriptions', array('where' => array(array('operation_id', '=', $operation_id)) ));

    	}

    	return $clients->get();

    }


    // public static function set_form_fields($form, $instance = null) {
    	
    // 	\Efx_Observer::set_fields($instance instanceof static ? $instance : get_called_class(), $form);
    // 	$instance and $form->populate($instance, true);
    // }



}
