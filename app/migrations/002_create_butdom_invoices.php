<?php

namespace Fuel\Migrations;

class Create_butdom_invoices
{
	public function up()
	{
		\DBUtil::create_table('butdom_invoices', array(
			'id' 		=> array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'client_id' => array('constraint' => 11, 'type' => 'int'),
			'number' 	=> array('constraint' => 6, 'type' => 'int'),
			'date' 		=> array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('butdom_invoices');
	}
}