<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../util/validation.php';
    include_once '../objects/customer.php';
    include_once '../objects/user.php';
    
    // instantiate database and customer object
    $database = new Database();
    $db = $database->getConnection();
    $validation = new Validation();
        
    // initialize object
    $customer = new Customer($db);

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        
        $token = isset($_GET['token']) ? $_GET['token'] : null;
        $organization = isset($_GET['organization']) ? $_GET['organization'] : null;

        //verifica se existe token
        $arrValidation = $validation->validaToken($token,$organization,$db);

        if(is_null($arrValidation["userid"])){
            echo json_encode($arrValidation);
            return;
        }
        $customer->users_id = $arrValidation["userid"];
        
        // set ID property of record to read
        $customer->id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        $customers_arr=array();
        $customers_arr["records"] = array();
        
        // Busca os clientes
        $customer_item = $customer->read($customer->id,$organization);
        
        array_push($customers_arr["records"], $customer_item);

         // set response code - 200 OK
        http_response_code(200);
        
         // show customers data in json format
        echo json_encode($customers_arr);

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
            $customer->nome = $data->name;
            $customer->tipo = $data->type;
            $customer->email = $data->email;
            $customer->telefone = $data->phone;
            $customer->status = 1;
            $customer->data_criacao = date('Y-m-d H:i:s');
            $customer->users_id = $arrValidation["userid"];
            $customer->organizations_id = $organization;

            // create the customer
            if($customer->create()){
        
                // set response code - 201 created
                http_response_code(201);
        
                // tell the user
                echo json_encode(array("message" => "Customer was created."));
            }
        
            // if unable to create the customer, tell the user
            else{
        
                // set response code - 503 service unavailable
                http_response_code(503);
        
                // tell the user
                echo json_encode(array("message" => "Unable to create customer."));
            }
        }
    
        // tell the user data is incomplete
        else{
        
            // set response code - 400 bad request
            http_response_code(400);
        
            // tell the user
            echo json_encode(array("message" => "Unable to create customer. Data is incomplete."));
        }
    }
?>