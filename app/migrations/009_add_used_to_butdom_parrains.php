<?php

namespace Fuel\Migrations;

class Add_used_to_butdom_parrains
{
	public function up()
	{
		\DBUtil::add_fields('butdom_parrains', array(
			'used' => array('constraint' => 11, 'type' => 'int'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('butdom_parrains', array(
			'used'

		));
	}
}