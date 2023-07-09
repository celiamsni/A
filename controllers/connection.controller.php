<?php

require_once "models/connection.model.php";
require_once "controllers/file.controller.php";

class Connection{

    static public function connect($database, $username, $service){

        if($database === null) {
            return null;
        }


        $host = $objectDB -> ip.":".$objectDB -> port;
        $lang = $objectDB -> lang;

        $userDB = 
        $password =

        connectSQL($host, $database, $userDB, $password);

    }

}