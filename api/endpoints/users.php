<?php
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/user.php';
    
    // instantiate database and customer object
    $database = new Database();
    $db = $database->getConnection();
        
    // initialize object
    $user = new User($db);

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        
        // set ID property of record to read

        $user->email = isset($_GET['email']) ? $_GET['email'] : null;
        $user->password = isset($_GET['password']) ? md5($_GET['password']) : null;
        
        // read customers will be here
        // query customers
        $stmt = $user->read($user->email,$user->password);
        
        $num = $stmt->rowCount();
        
        // check if more than 0 record found
        if($num>0){
        
            // customers array
            $users_arr=array();
            $users_arr["records"]=array();
        
            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
        
                $user_item=array(
                    "id" => $id,
                    "email" => $email,
                    "password" => $password,
                    "token" => $token,
                    "organizations_id" => $organizations_id,
                    "status" => $status,
                    "created" => $created
                );
        
                array_push($users_arr["records"], $user_item);
            }
        
            // set response code - 200 OK
            http_response_code(200);
        
            // show customers data in json format
            echo json_encode($users_arr);
        }
        
        else{
        
            // set response code - 404 Not found
            http_response_code(404);
        
            // tell the user no customers found
            echo json_encode(
                array("message" => "No users found.")
            );
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // get posted data
        $data = json_decode(file_get_contents("php://input"));

        // make sure data is not empty
        if(
            !empty($data->email) &&
            !empty($data->password) &&
            !is_null($data->organizations_id)
        ){
            
            $token = generateToken();

            // set customer property values
            $user->email = $data->email;
            $user->password = md5($data->password);
            $user->token = $token;
            $user->status = 1;
            $user->created = date('Y-m-d H:i:s');
            $user->organizations_id = $data->organizations_id;

            // create the customer
            if($user->create()){
        
                // set response code - 201 created
                http_response_code(201);
        
                // tell the user
                echo json_encode(array("message" => "User was created."));
            }
        
            // if unable to create the customer, tell the user
            else{
        
                // set response code - 503 service unavailable
                http_response_code(503);
        
                // tell the user
                echo json_encode(array("message" => "Unable to create user."));
            }
        }
    
        // tell the user data is incomplete
        else{
        
            // set response code - 400 bad request
            http_response_code(400);
        
            // tell the user
            echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
        }
    }


    function generateToken(){
        $date = date('Y-m-d H:i:s');
        //$string = $date->format('Y-m-d H:i:s');
        $token = md5($date);

        return $token;
    }

?>