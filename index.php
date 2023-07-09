<?php

    /*
     * Mostrar errores  
     * * * * * * * * * */

    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', "C:/xampp/htdocs/resAPI/php_error_log.txt");

    /*
     * Requerimientos  
     * * * * * * * * * */

    require_once 'controllers/routes.controller.php';

/*
    // Si la conexión no es HTTPS devuelve 403
    if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {

        die(ResponseController::json(403, "Forbidden"));

    }
*/

    $index= new RoutesController();

    $index -> index();

    //https://www.youtube.com/watch?v=RCxFHxISFvc

?>