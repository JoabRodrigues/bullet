<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../util/validation.php';
    include_once '../objects/produto.php';
    include_once '../objects/user.php';
    
    // instantiate database and produto object
    $database = new Database();
    $db = $database->getConnection();
    $validation = new Validation();
        
    // initialize object
    $produto = new Produto($db);

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $token = isset($_GET['token']) ? $_GET['token'] : null;
        $organization = isset($_GET['organization']) ? $_GET['organization'] : null;

        //verifica se existe token
        $arrValidation = $validation->validaToken($token,$organization,$db);

        if(is_null($arrValidation["userid"])){
            echo json_encode($arrValidation);
            return;
        }
        $produto->users_id = $arrValidation["userid"];
        
        
        // set ID property of record to read
        $produto->id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        $produtos_arr=array();
        $produtos_arr["records"] = array();
        
        // Busca os clientes
        $produtos_item = $produto->getProdutos($produto->id,$organization);
        
        array_push($produtos_arr["records"], $produtos_item);

         // set response code - 200 OK
        http_response_code(200);
        
         // show customers data in json format
        echo json_encode($produtos_arr);
       
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
            !empty($data->nome) &&
            !is_null($data->valor)
        ){
        
            // set produto property values
            $produto->nome = $data->nome;
            $produto->valor = $data->valor;
            $produto->status = 1;
            $produto->data_criacao = date('Y-m-d H:i:s');
            $produto->users_id = $arrValidation["userid"];
            $produto->organizations_id = $organization;

            // create the produto
            if($produto->insertProduto()){
        
                // set response code - 201 created
                http_response_code(201);
        
                // tell the user
                echo json_encode(array("message" => "Produto criado com sucesso."));
            }
        
            // if unable to create the produto, tell the user
            else{
        
                // set response code - 503 service unavailable
                http_response_code(503);
        
                // tell the user
                echo json_encode(array("message" => "Não foi possível criar o produto."));
            }
        }
    
        // tell the user data is incomplete
        else{
        
            // set response code - 400 bad request
            http_response_code(400);
        
            // tell the user
            echo json_encode(array("message" => "Não foi possível criar o produto. Dados incompletos."));
        }
    }

?>