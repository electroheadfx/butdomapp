<?php

namespace Fuel\Migrations;

class Create_butdom_clients
{
	public function up()
	{
		\DBUtil::create_table('butdom_clients', array(
			'id' 			=> array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'email' 		=> array('constraint' => 255, 'type' => 'varchar'),
			'name' 			=> array('constraint' => 100, 'type' => 'varchar', 'null' => true),
			'surname' 		=> array('constraint' => 100, 'type' => 'varchar', 'null' => true),
			'telephone' 	=> array('constraint' => 10, 'type' => 'varchar', 'null' => true),
			'departement' 	=> array('constraint' => '"reunion","guyane","martinique","guadeloupe","stmartin"', 'type' => 'enum'),
			'confirmed' 	=> array('constraint' => '"pending","approved","refused"', 'type' => 'enum'),
			'token'			=> array('constraint' => 255, 'type' => 'varchar'),
			'created_at' 	=> array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' 	=> array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('butdom_clients');
	}
}