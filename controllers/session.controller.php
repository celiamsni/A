<?php

require_once "models/session.model.php";

require_once "controllers/file.controller.php";

class SessionController{

    static function randomString($length){

        $model = new SessionModel();

        return $model -> randomString($length);

    }

    static function sessionTokenJWT($userId, $secret){

        $model = new SessionModel();

        return $model -> sessionTokenJWT($userId, $secret);

    }

    static public function encodeJWT($data){

        $model = new SessionModel();

        return $model -> encodeJWT($data);

    }

    /** 
     * Validates the session of the user through the JWT token sent in the request
     * 
     * @return Array Returns the user information inside the token || NULL if the session is not valid
     * 
     * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    static public function validateSession(){

        /* DEBUGGING
        foreach ($_SERVER as $key => $value) { // Print all the header keys
            echo $key."\n";
        }
        */

        $sessionModel = new SessionModel();

        return $sessionModel -> validateSession();

    }

}

?>