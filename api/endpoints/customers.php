<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/customer.php';
    
    // instantiate database and customer object
    $database = new Database();
    $db = $database->getConnection();
        
    // initialize object
    $customer = new Customer($db);

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        
        // set ID property of record to read
        $customer->id = isset($_GET['id']) ? $_GET['id'] : 0;
        
        // read customers will be here
        // query customers
        $stmt = $customer->read($customer->id);
        
        $num = $stmt->rowCount();
        
        // check if more than 0 record found
        if($num>0){
        
            // customers array
            $customers_arr=array();
            $customers_arr["records"]=array();
        
            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
        
                $customer_item=array(
                    "id" => $id,
                    "name" => $name,
                    "type" => $type,
                    "email" => $email,
                    "phone" => $phone,
                    "status" => $status,
                    "created" => $created
                );
        
                array_push($customers_arr["records"], $customer_item);
            }
        
            // set response code - 200 OK
            http_response_code(200);
        
            // show customers data in json format
            echo json_encode($customers_arr);
        }
        
        else{
        
            // set response code - 404 Not found
            http_response_code(404);
        
            // tell the user no customers found
            echo json_encode(
                array("message" => "No customers found.")
            );
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        // make sure data is not empty
        if(
            !empty($data->name) &&
            !is_null($data->type) &&
            !empty($data->email) &&
            !empty($data->phone) 
        ){
        
            // set customer property values
            $customer->name = $data->name;
            $customer->type = $data->type;
            $customer->email = $data->email;
            $customer->phone = $data->phone;
            $customer->status = 1;
            $customer->created = date('Y-m-d H:i:s');

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