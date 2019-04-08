<?php
include_once '../objects/order.php';
include_once '../config/database.php';

class Products_Has_Order{
 
    // database connection and table name
    private $conn;
    private $table_name = "products_has_order";
 
    // object properties
    public $products_id;
    public $orders_id;
    public $quantity;
    public $amount;
    public $status;
    
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    // read customers
    function read($id){
            // select 
            $query = "SELECT
                pho.products_id, p.name products_name, pho.orders_id, pho.quantity, pho.amount,  
                case
                    when pho.status = 1 then 'Ativo' else 'Cancelado' 
                end status
                FROM
                " . $this->table_name . " pho
                    join products p on (p.id = pho.products_id)
                WHERE 
                    pho.orders_id = " . $id ;

        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create customer
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    products_id=:products_id, orders_id=:orders_id, quantity=:quantity, amount=:amount, status=:status";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->products_id=htmlspecialchars(strip_tags($this->products_id));
        $this->orders_id=htmlspecialchars(strip_tags($this->orders_id));
        $this->quantity=htmlspecialchars(strip_tags($this->quantity));
        $this->amount=htmlspecialchars(strip_tags($this->amount));
        $this->status=htmlspecialchars(strip_tags($this->status));
        
        
    
        // bind values
        $stmt->bindParam(":products_id", $this->products_id);
        $stmt->bindParam(":orders_id", $this->orders_id);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":status", $this->status);
        
        // execute query
        if($stmt->execute()){
            $database = new Database();
            $db = $database->getConnection();
            
            $order = new Order($db);
            $order->updateBalance($this->orders_id);
            return true;
        }

        return false;
    }
}