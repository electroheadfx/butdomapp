<?php

namespace Fuel\Tasks;

class Date {

    public static function run($args = NULL) {

        if ($args != NULL) {

            echo \Date::create_from_string($args , "eu")->get_timestamp();

        }
        echo "\n";

    }

    public static function get($args = NULL) {

        if ($args != NULL) {

            echo \Date::forge($args)->format("%d/%m/%Y");

        }
        echo "\n";

    }

}