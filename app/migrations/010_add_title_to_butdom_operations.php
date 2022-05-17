<?php

namespace Fuel\Migrations;

class Add_title_to_butdom_operations
{
	public function up()
	{
		\DBUtil::add_fields('butdom_operations', array(
			'title' => array('constraint' => 255, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('butdom_operations', array(
			'title'

		));
	}
}