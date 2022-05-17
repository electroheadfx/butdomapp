<?php

namespace Fuel\Migrations;

class Create_butdom_operations
{
	public function up()
	{
		\DBUtil::create_table('butdom_operations', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'name' => array('constraint' => 255, 'type' => 'varchar'),
			'departement' => array('constraint' => '"tous","reunion","guyane","martinique","guadeloupe","stmartin"', 'type' => 'enum'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('butdom_operations');
	}
}