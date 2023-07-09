<?php

require 'vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;

class SessionModel{

    /*
     *  Generates a new JWT token
     * * * * * * * * * * * * * * * */

    function randomString($length){

        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#_?=!%^&()';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;

    }

    function sessionTokenJWT($userId, $secret){

        $expTime = time() + 3600; // 1 hora de expiración

        $data = [
            'id' => $userId,
            'exp' => $expTime
        ];

        return SessionModel::encodeJWT($data, $secret);

    }

    public function encodeJWT($data){

        // Clave secreta para firmar y verificar el token
        $secret = SessionModel::randomString(20);

        // El id es el microtime de creación del secret
        $milliseconds = round(microtime(true) * 1000);

        // Los datos del private key para desencriptar el token
        $secretToSave = [
            "secret" => $secret,
            "ip" => "ipOrigen",
            "active" => true
        ];
        
        // Persistencia del private key
        FileController::writeFile("secrets/$milliseconds", json_encode($secretToSave));

        // Donde exp es la clave para encontrar el token en persistencia
        $encrypted = array(
            "token" => JWT::encode($data, $secret, 'HS256'),
            "exp" => $milliseconds
        );

        $encrypted = json_encode($encrypted);

        return $encrypted;

    }

    public function validateSession(){

        // Checks if the EXPIRES header || if the AUTH header is not set
        if (!isset($_SERVER['HTTP_EXPIRES']) || !isset($_SERVER['HTTP_AUTH'])) {
            return null;
        }

        // Gets the EXPIRES value from the http header
        $timestamp = $_SERVER['HTTP_EXPIRES'];

        // Gets the secretObject from the file
        $secretObject = FileController::readJson("secrets/".$timestamp);

        // Check if the secret object exists
        if($secretObject === null){
            return null;
        }
        
        // Checkse if the secret object contains the secret field
        if(!array_key_exists("secret", $secretObject)){
            return null;
        }

        // Gets the secret field from the secret object
        $secret = $secretObject['secret'];

        // Gets the JWT token from the header AUTH value
        $headerAuth = $_SERVER['HTTP_AUTH'];

        $headerAuth = str_replace("Bearer ", "", $headerAuth);

        try {

            $tokenJWT = JWT::decode($headerAuth, new Key($secret, 'HS256'));
               
            // The JWT token is valid
            return $tokenJWT;

        } catch (Exception $e) {

            // The JWT token is not valid
            return null;

        }

    }

}

?>