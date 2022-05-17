<?php

namespace Fuel\Migrations;

class Delete_used_to_butdom_parrains
{
	public function up()
	{
		\DBUtil::drop_fields('butdom_parrains', array(
			'used'

		));
	}

	public function down()
	{
		\DBUtil::add_fields('butdom_parrains', array(
			'used' => array('constraint' => 2, 'type' => 'int'),

		));
	}
}