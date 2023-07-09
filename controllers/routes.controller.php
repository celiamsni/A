<?php

require_once 'controllers/session.controller.php';

require_once 'controllers/response.controller.php';

// no usados
require_once 'controllers/file.controller.php';



require_once 'models/routes.model.php';

class RoutesController{

    static public function index(){

        // Initializes the routes model
        $routesModel = new RoutesModel();

        // Gets the application method
        $method = $_SERVER['REQUEST_METHOD'];

        //Checks if its a preflight and if the method is allowed
        RoutesController::checkCors($routesModel, $method);

        // Gets the service wich the user is trying to access.
        $clientService = $routesModel -> getClientService();

        // Gets the info of the database that contains the service and the service itself
        $databaseService = RoutesController::getDatabaseService($routesModel, $clientService);
        
        $database = $databaseService['database'];
        $service = $databaseService['service'];

        if ($database === null || $service === null) {

            die(ResponseController::json(401, "Unauthorized"));

        }

        // Gets the uri params spliting by "/"
        $uriArray = $routesModel -> getUriParams();

        //Gets the decoded token if the user is authenticated
        $decodedTokenJWT = RoutesController::getDecodedToken($routesModel, $method, $database, $service, $uriArray);
        // El usuario está autentificado (No significa que tenga permiso para la operación que trata hacer)
        
        // Si tiene permisos para la operacion devuelve algo, si no devuelve null
        $response = $routesModel -> index($database, $service, $method, $decodedTokenJWT);

        if($response === null){ // El usuario no está verificado || no tiene permiso para la operación que trata de hacer

            die(ResponseController::json(401, "Unauthorized"));

        }

        die(ResponseController::json(200, $response));

    }

    static private function checkCors($routesModel, $method){

        // Checks if it's a preflight (OPTIONS method)
        if ($method === 'OPTIONS') {

            // To avoid CORS error
            $authorized = $routesModel -> corsHandler($method);

            if($authorized){

                die(ResponseController::json(200, "Authorized"));

            } else {

                die(ResponseController::json(401, "Unauthorized"));

            }

        }

    }

    static private function getDatabaseService($routesModel, $clientService){

        // Gets the databases from the configuration file.
        $databases = FileController::readJson("config/config.json");

        //Checks if the service is on the services list
        $databaseService = $routesModel -> getDatabaseService($databases, $clientService);

        if($databaseService === null){ // If the service is not listed, return false

            die(ResponseController::json(401, "Unauthorized"));

        }

        return $databaseService;

    }

    static private function getDecodedToken($routesModel, $method, $database, $service, $uriArray){

        // Gets the info codified in the token || NULL if the session is not valid
        $decodedTokenJWT = SessionController::validateSession();

        if($decodedTokenJWT == null){ // El usuario no está autentificado
 
            if($method === 'POST' && $uriArray[1] === "login"){ // El usuario trata de iniciar sesión

                // Obtén los datos enviados desde el formulario
                $username = $_POST['username'] ?? null;
                $password = $_POST['password'] ?? null;

                if($username == null || $password == null){
                    die(ResponseController::json(401, "Unauthorized"));
                }
 
                $outputLogin = $routesModel -> login($database, $service, $username, $password);

                if($outputLogin != null){
                    die(ResponseController::json(200, $outputLogin));
                }
 
            }

            // El usuario no esta autentificado y no trata de iniciar sesión (no debe acceder)
            die(ResponseController::json(401, "Unauthorized"));
            
        }

        return $decodedTokenJWT;

    }

}

?>