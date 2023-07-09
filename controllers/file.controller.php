<?php

require_once "models/file.model.php";

class FileController{

    static public function readJson($filePath){

        $fileModel = new FileModel();

        return  $fileModel -> readJson($filePath);

    }

    static public function findInJson($filePath, $fieldName, $value){

        $fileModel = new FileModel();

        return $fileModel -> findInJson($filePath, $fieldName, $value);;

    }

    static public function appendString($filePath, $stringToAdd){

        $fileModel = new FileModel();

        $fileModel -> appendString($filePath, $stringToAdd);

    }

    static public function appendJson($filePath, $objectToAdd){

        $fileModel = new FileModel();

        $fileModel -> appendJson($filePath, $objectToAdd);

    }

    static public function writeFile($filePath, $content){

        $fileModel = new FileModel();

        $fileModel -> writeFile($filePath, $content);

    }

}