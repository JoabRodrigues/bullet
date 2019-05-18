<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../util/validation.php';
    include_once '../objects/invoice.php';
    include_once '../objects/order.php';
    include_once '../objects/user.php';
    include_once '../objects/parcela.php';
    
    // instantiate database and customer object
    $database = new Database();
    $db = $database->getConnection();
    $validation = new Validation();
        
    // initialize object
    $invoice = new Invoice($db);
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        
        $token = isset($_GET['token']) ? $_GET['token'] : null;
        $organization = isset($_GET['organization']) ? $_GET['organization'] : null;

        //verifica se existe token
        $arrValidation = $validation->validaToken($token,$organization,$db);

        if(is_null($arrValidation["userid"])){
            echo json_encode($arrValidation);
            return;
        }
        //$customer->users_id = $arrValidation["userid"];
        
        // set ID property of record to read
        $invoice->id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        // read customers will be here
        // query customers
        $stmt = $invoice->read($invoice->id,$organization);
        
        $num = $stmt->rowCount();
        
        // check if more than 0 record found
        if($num>0){
        
            // customers array
            $invoice_arr=array();
            $invoice_arr["records"]=array();
        
            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
        
                $invoice_item=array(
                    "id" => $id,
                    "created" => $created,
                    "amount" => $amount,
                    "status" => $status,
                    "orders_id" => $orders_id,
                    "users_id" => $users_id,
                    "organizations_id" => $organizations_id
                );
        
                array_push($invoice_arr["records"], $invoice_item);
            }
        
            // set response code - 200 OK
            http_response_code(200);
        
            // show customers data in json format
            echo json_encode($invoice_arr);
        }
        else{
        
            // set response code - 404 Not found
            http_response_code(404);
        
            // tell the user no customers found
            echo json_encode(
                array("message" => "No invoices found.")
            );
        }
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
            !empty($data->order_id) &&
            !empty($data->pagamentos)
        ){
            
            
            //echo json_encode(array("message" => $data->payments));

            //get data from order
            $order = new Order($db);
            $order->getOrder($data->order_id,$organization);    

            // valida se o pedido de venda está em aberto para faturar.
            if($order->status != 1){
                echo json_encode(array("message" => "O pedido de venda não está em aberto para faturamento."));
                return;
            }
            
            // valida e os pagamentos estão de acordo com o valor total do pedido.
            if(!validaPagamento($data->pagamentos,$order->amount)){
                echo json_encode(array("message" => "O Valor total dos pagamentos não está de acordo com o valor total do pedido."));
                return;
            }

            
            
            // set customer property values
            $invoice->created = date('Y-m-d H:i:s');
            $invoice->amount = $order->amount;
            $invoice->status = 1;
            $invoice->orders_id = $order->id;
            $invoice->users_id = $arrValidation["userid"];
            $invoice->organizations_id = $organization;

            // create the customer
            if($invoice->create()){

                // cria as parcelas da fatura
                if(!criaParcelas($data->pagamentos,$invoice->id)){
                    echo json_encode(array("message" => "Erro ao criar as parcelas.")); 
                    return; 
                };

                $statusOrder = 2; // faturado
                // change status order
                $order->updateStatusOrder($invoice->orders_id,$statusOrder);

                // set response code - 201 created
                http_response_code(201);
        
                // tell the user
                echo json_encode(array("message" => "Pedido de venda Faturado."));
            }
        
            // if unable to create the customer, tell the user
            else{
        
                // set response code - 503 service unavailable
                http_response_code(503);
        
                // tell the user
                echo json_encode(array("message" => "Não foi possível faturar o pedido."));
            }
        }
    
        // tell the user data is incomplete
        else{
        
            // set response code - 400 bad request
            http_response_code(400);
        
            // tell the user
            echo json_encode(array("message" => "Não foi possível faturar o pedido. Os dados passados estão incompletos."));
        }
    }


    function validaPagamento($pagamentos,$valorTotalPedido){
        $valorTotalPagamentos = 0;
        foreach ($pagamentos as $pagamento) {
            $valorTotalPagamentos = $valorTotalPagamentos + $pagamento->valor_total;
        }
        
        if($valorTotalPagamentos == $valorTotalPedido){
            return true;
        }

        return false;
    }

    function criaParcelas($pagamentos,$invoiceId){
        foreach ($pagamentos as $pagamento) {
            for ($i=0; $i < $pagamento->numero_parcelas; $i++) { 
                $parcela = new Parcela();
                $parcela->data_criacao = date('Y-m-d H:i:s');;
                $parcela->data_vencimento = $parcela->calculaDataVencimento($pagamento->data_vencimento,$i);
                $parcela->data_pagamento = null;
                $parcela->forma_pagamento = $pagamento->tipo_pagamento;
                $parcela->numero_parcela = $i+1;
                $parcela->status = 1;
                $parcela->fatura_id = $invoiceId;
                if($pagamento->tipo_pagamento == 'cartao_credito'){
                    $parcela->data_vencimento = $parcela->calculaDataVencimento($pagamento->data_vencimento,$i+1); // pagamento em cartão leva 30 dias para cair na conta
                    $parcela->bandeira_cartao = $pagamento->bandeira;
                    $parcela->ultimos_4_digitos_cartao = $pagamento->ultimos_4_digitos_cartao;
                }
 
                if($i == 0){
                    $valorParcelas = number_format((float)($pagamento->valor_total / $pagamento->numero_parcelas), 2, '.', '');
                    
                    $valorTotalParcelas = $valorParcelas * $pagamento->numero_parcelas;

                    if($valorTotalParcelas > $pagamento->valor_total){
                        $valorDesconto = $valorTotalParcelas - $pagamento->valor_total;
                        $valorPrimeiraParcela = $valorParcelas - $valorDesconto; 
                        $valorParcela = $valorPrimeiraParcela;   
                    }elseif ($valorTotalParcelas < $pagamento->valor_total) {
                        $valorSoma = $pagamento->valor_total - $valorTotalParcelas;
                        $valorPrimeiraParcela = $valorParcelas + $valorSoma;
                        $valorParcela = $valorPrimeiraParcela;
                    }
                    $valorParcela = $valorParcelas ;    
                }else{
                    $valorParcela = number_format((float)($pagamento->valor_total / $pagamento->numero_parcelas), 2, '.', '');
                }
                
                $parcela->valor = $valorParcela;

                $parcela->criaParcela(); 
            }
        }
        return true;
    }

?>