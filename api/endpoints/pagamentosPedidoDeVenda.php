<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../util/validation.php';
    include_once '../objects/pagamentosPedidoDeVenda.php';
    include_once '../objects/pedidoDeVenda.php';
    
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();
    $validation = new Validation();
        
    // initialize object
    $pagamentosPedidoDeVenda = new pagamentosPedidoDeVenda($db);

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $token = isset($_GET['token']) ? $_GET['token'] : null;
        $organization = isset($_GET['organization']) ? $_GET['organization'] : null;

        //verifica se existe token
        $arrValidation = $validation->validaToken($token,$organization,$db);

        if(is_null($arrValidation["userid"])){
            echo json_encode($arrValidation);
            return;
        }
        $pagamentosPedidoDeVenda->users_id = $arrValidation["userid"];
        
        // set ID property of record to read
        $pagamentosPedidoDeVenda->pedido_de_venda_id = isset($_GET['pedido_de_venda_id']) ? $_GET['pedido_de_venda_id'] : 0;

        if($pagamentosPedidoDeVenda->pedido_de_venda_id == 0){
            echo json_encode(
                array("message" => "Pedido de venda não encontrado.")
            );   
            return;
        }
        
        $pagamentosPedidoDeVenda_arr=array();
        $pagamentosPedidoDeVenda_arr["records"] = array();
        
        // Busca os clientes
        $itensPagamentosPedidoDeVenda = $pagamentosPedidoDeVenda->getPagamentosPedidoDeVenda($pagamentosPedidoDeVenda->pedido_de_venda_id);
        
        array_push($pagamentosPedidoDeVenda_arr["records"], $itensPagamentosPedidoDeVenda);

         // set response code - 200 OK
        http_response_code(200);
        
         // show customers data in json format
        echo json_encode($pagamentosPedidoDeVenda_arr);
        
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
            !is_null($data->numero_parcelas) &&
            !is_null($data->data_vencimento) &&
            !is_null($data->tipo_pagamento) &&
            !is_null($data->pedido_de_venda_id)
        ){
        
            // set product property values
            $pagamentosPedidoDeVenda->valor_total = $data->valor_total;
            $pagamentosPedidoDeVenda->numero_parcelas = $data->numero_parcelas;
            $pagamentosPedidoDeVenda->data_vencimento = $data->data_vencimento;
            $pagamentosPedidoDeVenda->tipo_pagamento = $data->tipo_pagamento;
            $pagamentosPedidoDeVenda->pedido_de_venda_id = $data->pedido_de_venda_id;
            $pagamentosPedidoDeVenda->bandeira = $data->bandeira;
            $pagamentosPedidoDeVenda->ultimos_digitos_cartao = $data->ultimos_digitos_cartao;
            $pagamentosPedidoDeVenda->status = 1;
            $pagamentosPedidoDeVenda->users_id = $arrValidation["userid"];
            
            // create the product
            if($pagamentosPedidoDeVenda->insertPagamentosPedidoDeVenda()){

                // set response code - 201 created
                http_response_code(201);
        
                // tell the user
                echo json_encode(array("message" => "Pagamento do pedido de venda criado com sucesso."));
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