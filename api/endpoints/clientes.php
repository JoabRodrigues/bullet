<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    include_once '../config/database.php';
    include_once '../util/validation.php';
    include_once '../objects/cliente.php';
    include_once '../objects/user.php';
    
    $database = new Database();
    $db = $database->getConnection();
    $validation = new Validation();
        
    $cliente = new Cliente($db);

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        
        $token = isset($_GET['token']) ? $_GET['token'] : null;
        $organization = isset($_GET['organization']) ? $_GET['organization'] : null;

        //verifica se existe token
        $arrValidation = $validation->validaToken($token,$organization,$db);

        if(is_null($arrValidation["userid"])){
            echo json_encode($arrValidation);
            return;
        }
        $cliente->users_id = $arrValidation["userid"];
        
        // set ID property of record to read
        $cliente->id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        $clientes_arr=array();
        $clientes_arr["records"] = array();
        
        // Busca os clientes
        $cliente_item = $cliente->getClientes($cliente->id,$organization);
        
        array_push($clientes_arr["records"], $cliente_item);

         // set response code - 200 OK
        http_response_code(200);
        
         // show customers data in json format
        echo json_encode($clientes_arr);

    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // get posted data
        $data = json_decode(file_get_contents("php://input"));

        $token = isset($_GET['token']) ? $_GET['token'] : null;
        $organization = isset($_GET['organization']) ? $_GET['organization'] : null;

        //verifica se existe token
        $arrValidation = $validation->validaToken($token,$organization,$db);

        if(is_null($arrValidation["userid"])){
            echo json_encode($arrValidation);
            return;
        }
        
        // make sure data is not empty
        if(
            !empty($data->name) &&
            !is_null($data->type) &&
            !empty($data->email) &&
            !empty($data->phone) 
        ){
        
            // set customer property values
            $cliente->nome = $data->name;
            $cliente->tipo = $data->type;
            $cliente->email = $data->email;
            $cliente->telefone = $data->phone;
            $cliente->status = 1;
            $cliente->data_criacao = date('Y-m-d H:i:s');
            $cliente->users_id = $arrValidation["userid"];
            $cliente->organizations_id = $organization;

            // create the customer
            if($cliente->insertCliente()){
        
                // set response code - 201 created
                http_response_code(201);
        
                // tell the user
                echo json_encode(array("message" => "Cliente criado com sucesso."));
            }
        
            // if unable to create the customer, tell the user
            else{
        
                // set response code - 503 service unavailable
                http_response_code(503);
        
                // tell the user
                echo json_encode(array("message" => "Não foi possivel criar o cliente."));
            }
        }
    
        // tell the user data is incomplete
        else{
        
            // set response code - 400 bad request
            http_response_code(400);
        
            // tell the user
            echo json_encode(array("message" => "Não foi possivel criar o cliente. Os dados estão incompletos."));
        }
    }
?>