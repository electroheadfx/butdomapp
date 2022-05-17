<?php

namespace Fuel\Migrations;

class Create_butdom_operations_meta
{
	public function up()
	{
		\DBUtil::create_table('butdom_operations_meta', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'operation_id' => array('constraint' => 11, 'type' => 'int'),
			'key' => array('constraint' => 30, 'type' => 'varchar'),
			'value' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('butdom_operations_meta');
	}
}