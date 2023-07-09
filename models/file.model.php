<?php

class FileModel{

    public function readJson($filePath){

        if(file_exists($filePath)){

            $data = file_get_contents($filePath);
            //$data = "[".$data."]";

            return json_decode($data, true);

        } else {

            return null;

        }
        

    }

    public function findInJson($filePath, $fieldName, $value): ?array {

        $data = $this->readJson($filePath); // Decoded JSON in file
    
        foreach ($data as $objeto) {

            if ($objeto[$fieldName] == $value) {

                return $objeto;

            }
            
        }
    
        return null; // Object not found

    }

    public function appendJson($filePath, $objectToAdd) {

/* DEBUGGING
echo print_r($objectToAdd);
*/
        if(filesize($filePath) > 0){

            $str = ",";

        }else{

            $str = "";

        }

        $str = $str.json_encode($objectToAdd)."\n";

        if($str != ",\n"){

            $this -> appendString($filePath, $str);

        }

    }

    static public function isEmpty($str){

        $trimmedStr = trim($str);
        $escapedStr = addslashes($trimmedStr);
        return empty($escapedStr);

    }


    public function appendString($filePath, $stringToAdd){

        if(!(FileModel::isEmpty($stringToAdd))){

            file_put_contents($filePath, $stringToAdd, FILE_APPEND);

        }

    }

    static public function writeFile($filePath, $content){

        file_put_contents($filePath, $content);

    }

}

?>