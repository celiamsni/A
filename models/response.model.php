<?php

class ResponseModel{

    public function json($status, $value){

        $json = array(
            'status' => $status,
            'result' => $value
        );

        header('Content-Type: application/json');
        
        echo json_encode($json, http_response_code($json["status"]));
    
        return;
        
    }

}

?>