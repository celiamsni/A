<?php

class Connection{

    /*
     * Conexión a la base de datos
     * * * * * * * * * * * * * * * * */

    public function connectSQL($host, $database, $user, $password, $sql){

        try{

            $link = new PDO(
                "mysql:host=".$host.";dbname=".$database,
                $user,
                $password
            );

            $link->exec("set names utf8");

        } catch(PDOException $e){

            die("Error: ".$e->getMessage());

        }

        return $link;

    }



}
