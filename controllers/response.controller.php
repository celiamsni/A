<?php

require_once "models/response.model.php";

class ResponseController{

    static public function json($status, $values){

        $response = new ResponseModel();

        $response -> json($status, $values);
        
    }

}

?>