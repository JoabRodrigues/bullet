<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/order.php';
    
    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();
        
    // initialize object
    $order = new Order($db);

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        
        // set ID property of record to read
        $order->id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        // read products will be here
        // query products
        $stmt = $order->read($order->id);
        
        $num = $stmt->rowCount();
        
        // check if more than 0 record found
        if($num>0){
        
            // products array
            $orders_arr=array();
            $orders_arr["records"]=array();
        
            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
        
                $order_item=array(
                    "id" => $id,
                    "created" => $created,
                    "amount" => $amount,
                    "customers_id" => $customers_id,
                    "customers_name" => $customers_name,
                    "status" => $status
                );
        
                array_push($orders_arr["records"], $order_item);
            }
        
            // set response code - 200 OK
            http_response_code(200);
        
            // show products data in json format
            echo json_encode($orders_arr);
        }
        
        else{
        
            // set response code - 404 Not found
            http_response_code(404);
        
            // tell the user no products found
            echo json_encode(
                array("message" => "No orders found.")
            );
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        // make sure data is not empty
        if(
            !is_null($data->amount) &&
            !is_null($data->customers_id) 
        ){
        
            // set product property values
            $order->amount = $data->amount;
            $order->status = 1;
            $order->created = date('Y-m-d H:i:s');
            $order->customers_id = $data->customers_id;

            // create the product
            $id_created = $order->create();

            
            if($id_created <> 0){
                // set response code - 201 created
                http_response_code(201);
        
                // tell the user
                echo json_encode(array("message" => "Order was created.","id" => $id_created));
            }
        
            // if unable to create the product, tell the user
            else{
        
                // set response code - 503 service unavailable
                http_response_code(503);
        
                // tell the user
                echo json_encode(array("message" => "Unable to create order."));
            }
        }
    
        // tell the user data is incomplete
        else{
        
            // set response code - 400 bad request
            http_response_code(400);
            
            // tell the user
            echo json_encode(array("message" => "Unable to create order. Data is incomplete."));
        }
    }

?>