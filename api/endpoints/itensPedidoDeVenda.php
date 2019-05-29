<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../util/validation.php';
    include_once '../objects/itensPedidoDeVenda.php';
    include_once '../objects/pedidoDeVenda.php';
    
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();
    $validation = new Validation();
        
    // initialize object
    $itensPedidoDeVenda = new ItensPedidoDeVenda($db);

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $token = isset($_GET['token']) ? $_GET['token'] : null;
        $organization = isset($_GET['organization']) ? $_GET['organization'] : null;

        //verifica se existe token
        $arrValidation = $validation->validaToken($token,$organization,$db);

        if(is_null($arrValidation["userid"])){
            echo json_encode($arrValidation);
            return;
        }
        $itensPedidoDeVenda->users_id = $arrValidation["userid"];
        
        // set ID property of record to read
        $itensPedidoDeVenda->pedido_de_venda_id = isset($_GET['pedido_de_venda_id']) ? $_GET['pedido_de_venda_id'] : 0;

        if($itensPedidoDeVenda->pedido_de_venda_id == 0){
            echo json_encode(
                array("message" => "Pedido de venda não encontrado.")
            );   
            return;
        }
        
        $itens_pedido_de_venda_arr=array();
        $itens_pedido_de_venda_arr["records"] = array();
        
        // Busca os clientes
        $itens_pedido_de_venda = $itensPedidoDeVenda->getItensPedidoDeVenda($itensPedidoDeVenda->pedido_de_venda_id);
        
        array_push($itens_pedido_de_venda_arr["records"], $itens_pedido_de_venda);

         // set response code - 200 OK
        http_response_code(200);
        
         // show customers data in json format
        echo json_encode($itens_pedido_de_venda_arr);
        
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
            !is_null($data->produto_id) &&
            !is_null($data->pedido_de_venda_id) &&
            !is_null($data->quantidade) &&
            !is_null($data->valor)
        ){
        
            // set product property values
            $itensPedidoDeVenda->produto_id = $data->produto_id;
            $itensPedidoDeVenda->pedido_de_venda_id = $data->pedido_de_venda_id;
            $itensPedidoDeVenda->quantidade = $data->quantidade;
            $itensPedidoDeVenda->valor = $data->valor;
            $itensPedidoDeVenda->status = 1;
            $itensPedidoDeVenda->users_id = $arrValidation["userid"];
            
            // create the product
            if($itensPedidoDeVenda->insertItemPedidoDeVenda()){

                //atualiza o saldo do pedido de venda
                $pedidoDeVenda = new PedidoDeVenda();

                $pedidoDeVenda->id = $itensPedidoDeVenda->pedido_de_venda_id;

                $pedidoDeVenda->updateBalance($pedidoDeVenda->id);
        
                // set response code - 201 created
                http_response_code(201);
        
                // tell the user
                echo json_encode(array("message" => "Item do pedido de venda criado com sucesso."));
            }
        
            // if unable to create the product, tell the user
            else{
        
                // set response code - 503 service unavailable
                http_response_code(503);
        
                // tell the user
                echo json_encode(array("message" => "Não foi possível criar o item."));
            }
        }
    
        // tell the user data is incomplete
        else{
        
            // set response code - 400 bad request
            http_response_code(400);
        
            // tell the user
            echo json_encode(array("message" => "Não foi possível criar o item. Dados incompletos."));
        }
    }

?>