<?php

namespace Fuel\Tasks;

class Butdom {

    public static function run($args = NULL) {

        echo "\n===========================================";
        echo "\nRunning Task";
        echo "\n-------------------------------------------\n\n";

    }


    public static function add($args = NULL) {

        echo "\n===========================================";
        echo "\nRunning add clients";
        echo "\n-------------------------------------------\n\n";

        \DB::insert('butdom_Clients')->set(array(
                'id'            => 1,
                'email'         => 'dev@b2see.com',
                'name'          => 'marques',
                'surname'       => 'laurent',
                'telephone'     => '0492931411',
                'departement'   => 'guadeloupe',
                'confirmed'     => 'pending',
                'created_at'    =>  \Date::time()->get_timestamp(),
                'updated_at'    =>  \Date::time()->get_timestamp()
        ))->execute();

        \DB::insert('butdom_invoices')->set(array(
                'id'                => 1,
                'client_id' => 1,
                'number'            => '568745',
                'created_at'    =>  \Date::time()->get_timestamp(),
                'updated_at'    =>  \Date::time()->get_timestamp()
        ))->execute();

        \DB::insert('butdom_Clients')->set(array(
                'id'            => 2,
                'email'         => 'lmarques@b2see.com',
                'name'          => 'marques',
                'surname'       => 'laurent',
                'telephone'     => '0492931411',
                'departement'   => 'reunion',
                'confirmed'     => 'pending',
                'created_at'    =>  \Date::time()->get_timestamp(),
                'updated_at'    =>  \Date::time()->get_timestamp()
        ))->execute();

        \DB::insert('butdom_invoices')->set(array(
                'id'                => 2,
                'client_id' => 2,
                'number'            => '658545',
                'created_at'    =>  \Date::time()->get_timestamp(),
                'updated_at'    =>  \Date::time()->get_timestamp()
        ))->execute();

    }

    public static function more($args = NULL) {

        \DB::insert('butdom_invoices')->set(array(
                'id'                => 3,
                'client_id' => 1,
                'number'            => '589652',
                'created_at'    =>  \Date::time()->get_timestamp(),
                'updated_at'    =>  \Date::time()->get_timestamp()
        ))->execute();

         \DB::insert('butdom_invoices')->set(array(
                'id'                => 4,
                'client_id' => 2,
                'number'            => '785412',
                'created_at'    =>  \Date::time()->get_timestamp(),
                'updated_at'    =>  \Date::time()->get_timestamp()
        ))->execute();


    }


}