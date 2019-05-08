<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../util/validation.php';
    include_once '../objects/products_has_order.php';
    
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();
    $validation = new Validation();
        
    // initialize object
    $products_has_order = new Products_Has_Order($db);

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $token = isset($_GET['token']) ? $_GET['token'] : null;
        $organization = isset($_GET['organization']) ? $_GET['organization'] : null;

        //verifica se existe token
        $arrValidation = $validation->validaToken($token,$organization,$db);

        if(is_null($arrValidation["userid"])){
            echo json_encode($arrValidation);
            return;
        }
        $products_has_order->users_id = $arrValidation["userid"];
        
        // set ID property of record to read
        $products_has_order->orders_id = isset($_GET['orders_id']) ? $_GET['orders_id'] : 0;

        if($products_has_order->orders_id == 0){
            echo json_encode(
                array("message" => "Order id not found.")
            );   
            return;
        }
        
        // read products will be here
        // query products
        $stmt = $products_has_order->read($products_has_order->orders_id);
        
        $num = $stmt->rowCount();
        
        // check if more than 0 record found
        if($num>0){
        
            // products array
            $products_has_order_arr=array();
            $products_has_order_arr["records"]=array();
        
            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
        

                $products_has_order_item=array(
                    "products_id" => $products_id,
                    "products_name" => $products_name,
                    "orders_id" => $orders_id,
                    "quantity" => $quantity,
                    "amount" => $amount,
                    "status" => $status
                );
        
                array_push($products_has_order_arr["records"], $products_has_order_item);
            }
        
            // set response code - 200 OK
            http_response_code(200);
        
            // show products data in json format
            echo json_encode($products_has_order_arr);
        }
        
        else{
        
            // set response code - 404 Not found
            http_response_code(200);
        
            // tell the user no products found
            echo json_encode(
                array("message" => "No products_has_order found.")
            );
        }
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
            !is_null($data->products_id) &&
            !is_null($data->orders_id) &&
            !is_null($data->quantity) &&
            !is_null($data->amount)
        ){
        
            // set product property values
            $products_has_order->products_id = $data->products_id;
            $products_has_order->orders_id = $data->orders_id;
            $products_has_order->quantity = $data->quantity;
            $products_has_order->amount = $data->amount;
            $products_has_order->status = 1;
            $products_has_order->users_id = $arrValidation["userid"];
            
            // create the product
            if($products_has_order->create()){
        
                // set response code - 201 created
                http_response_code(201);
        
                // tell the user
                echo json_encode(array("message" => "products_has_order was created."));
            }
        
            // if unable to create the product, tell the user
            else{
        
                // set response code - 503 service unavailable
                http_response_code(503);
        
                // tell the user
                echo json_encode(array("message" => "Unable to create products_has_order."));
            }
        }
    
        // tell the user data is incomplete
        else{
        
            // set response code - 400 bad request
            http_response_code(400);
        
            // tell the user
            echo json_encode(array("message" => "Unable to create products_has_order. Data is incomplete."));
        }
    }

?>