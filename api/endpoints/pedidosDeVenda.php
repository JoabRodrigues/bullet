<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../util/validation.php';
    include_once '../objects/pedidoDeVenda.php';
    
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();
    $validation = new Validation();    

    // initialize object
    $pedidoDeVenda = new PedidoDeVenda($db);

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $token = isset($_GET['token']) ? $_GET['token'] : null;
        $organization = isset($_GET['organization']) ? $_GET['organization'] : null;

        //verifica se existe token
        $arrValidation = $validation->validaToken($token,$organization,$db);

        if(is_null($arrValidation["userid"])){
            echo json_encode($arrValidation);
            return;
        }
        $pedidoDeVenda->users_id = $arrValidation["userid"];
        
        // set ID property of record to read
        $pedidoDeVenda->id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        $pedidos_arr=array();
        $pedidos_arr["records"] = array();

        // Busca os pedidos
        $pedido_item = $pedidoDeVenda->getPedidos($pedidoDeVenda->id,$organization);
        
        array_push($pedidos_arr["records"], $pedido_item);

        // set response code - 200 OK
        http_response_code(200);
        
        // show customers data in json format
        echo json_encode($pedidos_arr);

    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $token = isset($_GET['token']) ? $_GET['token'] : null;
        $organization = isset($_GET['organization']) ? $_GET['organization'] : null;

        //verifica se existe token
        $arrValidation = $validation->validaToken($token,$organization,$db);

        if(is_null($arrValidation["userid"])){
            echo json_encode($arrValidation);
            return;
        }
        
        // get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        // make sure data is not empty
        if(
            !is_null($data->valor_total) &&
            !is_null($data->cliente_id) 
        ){
        
            // set product property values
            $pedidoDeVenda->valor_total = $data->valor_total;
            $pedidoDeVenda->status = 1;
            $pedidoDeVenda->data_criacao = date('Y-m-d H:i:s');
            $pedidoDeVenda->cliente_id = $data->cliente_id;
            $pedidoDeVenda->users_id = $arrValidation["userid"];
            $pedidoDeVenda->organizations_id = $organization;

            // create the customer
            if($pedidoDeVenda->insertPedidoDeVenda()){
        
                // set response code - 201 created
                http_response_code(201);
        
                // tell the user
                echo json_encode(array(
                    "id" => $pedidoDeVenda->id,
                    "message" => "Pedido de venda criado com sucesso.")
                );
            }
        
            // if unable to create the customer, tell the user
            else{
        
                // set response code - 503 service unavailable
                http_response_code(503);
        
                // tell the user
                echo json_encode(array("message" => "Não foi possivel criar o pedido de venda."));
            }
        }
    
        // tell the user data is incomplete
        else{
        
            // set response code - 400 bad request
            http_response_code(400);
            
            // tell the user
            echo json_encode(array("message" => "Não foi possivel criar o pedido de venda. Dados incompletos."));
        }
    }

?>