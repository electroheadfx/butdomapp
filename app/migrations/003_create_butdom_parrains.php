<?php

namespace Fuel\Migrations;

class Create_butdom_parrains
{
	public function up()
	{
		\DBUtil::create_table('butdom_parrains', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'filleul_id' => array('constraint' => 11, 'type' => 'int'),
			'parrain_id' => array('constraint' => 11, 'type' => 'int'),
			'used' => array('constraint' => 2, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('butdom_parrains');
	}
}