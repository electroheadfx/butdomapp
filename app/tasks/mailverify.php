<?php

namespace Fuel\Tasks;

class Mailverify {

    public static function run($email = NULL) {

        if ($email != NULL) {

            try {

                $requete = json_decode(\Request::forge('api/emailverify/index/'.$email)->execute(),true);
                echo $requete['email'] .' : ';
                var_dump($requete['is_clean']);
                echo 'email status : ' . $requete['email_status'];
            
            } catch (\Fuel\Core\RequestStatusException $e) {

                echo $e->getMessage();

            } catch (\Fuel\Core\RequestException $e) {

                echo $e->getMessage();

            }

        }

        echo "\n";

    }


}