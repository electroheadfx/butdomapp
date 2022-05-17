<?php

namespace Fuel\Migrations;

class Add_event_to_butdom_operations
{
	public function up()
	{
		\DBUtil::add_fields('butdom_operations', array(
			'event' => array('constraint' => 255, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('butdom_operations', array(
			'event'

		));
	}
}