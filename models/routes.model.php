<?php

class RoutesModel{

    public function corsHandler(){

        $accessAllowed = true; // Falta la lógica de que tenga acceso a la solicitud

        // Envía los encabezados CORS para la solicitud preflight
        if ($accessAllowed) {

            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
            header('Access-Control-Allow-Headers: *');
            header('Access-Control-Expose-Headers: auth');

            return true;

        } else {

            return false;
            
        }
        

    }

    public function getConfiguration(){

    }

    public function getAvaibleServices($databases){
        $services = [];

        foreach ($databases as $database) {
            foreach ($database['services'] as $service) {
                $services[] = $service["id"];
            }
        }

        return $services;
    }

    public function getDatabaseService($databases, $serviceToSearch){

        $output = array();
        $found = false;

        // Buscar la base de datos que contenga el servicio
        foreach ($databases as $bd) {
            foreach ($bd['services'] as $serv) {
                if ($serv['id'] === $serviceToSearch) {
                    $output['database'] = $bd;
                    $output['service']  = $serv;
                    $found = true;
                    break 2;
                }
            }
        }

        if(!$found){
            return null;
        }

        return $output;

    }

    public function getClientHost(){

        $referer = $_SERVER['HTTP_REFERER'] ?? null;

        if($referer == null) die(ResponseController::json(401, "Unauthorized"));

        $clientArray = parse_url($referer);

        $host = $clientArray['host'] ?? null;

        if($host == null) die(ResponseController::json(401, "Unauthorized"));

        return $host;

    }

    public function getClientService(){

        return $this -> getClientHost() . $_SERVER['REQUEST_URI'];

    }

    public function getUriParams(){
        // Splits the URI String in an Array breaking by "/"
        $uriArray = explode("/",$_SERVER['REQUEST_URI']); // Explode es como un split
        // Clears empty entries
        return array_filter($uriArray);
    }



    
    public function index($database, $service, $method, $decodedTokenJWT){

        $username = $decodedTokenJWT -> id ?? null;

        // Identificar el servicio al que quiere acceder y el nivel de permisos qe requiere ese servicio

        // Ver el nivel de permisos de ese usiario para esa base de datos

        $granted = true; // IMPLEMENTAR | Evaluar si tiene el nivel de permisos requerido

        if(!$granted){

            return null;

        }

        //Reemplazar por lógica
        $alumno = FileController::readJson("config/alumno.json");
        $alumno['nombre'] = $username;

        return  $alumno;
    }

    public function login($database, $service, $username, $password){
        
        $verified = $username === 'Manolito' && $password === 'contrasena'; // IMPLEMENTAR | Verificación de las credenciales

        // Si los datos de inicio de sesión no son correctos
        if(!$verified){
            return null;
        }

        // Generar token (Devuelve un objeto json {token, exp})
        $credentials = SessionController::sessionTokenJWT($username, SessionController::randomString(20));

        $credentials = json_decode($credentials);
        
        // Expone el token para que pueda ser accedido por javascript en el cliente (Si no da error)
        // ¿VULNERABILIDAD?
        header('Access-Control-Expose-Headers: auth, exp, type');

        // Establecer los datos en el encabezado HTTP
        header('auth: ' . $credentials->token);
        header('exp: ' . $credentials->exp);
        header('type: ' . 'alumno');

        // IMPLEMENTAR consulta asociada al login
        $querry = '{"username":"usuario"}';

        // Envía la respuesta al cliente
        return $querry;

    }

}

?>