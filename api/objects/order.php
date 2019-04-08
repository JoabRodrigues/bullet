<?php
class Order{
 
    // database connection and table name
    private $conn;
    private $table_name = "orders";
 
    // object properties
    public $id;
    public $created;
    public $amount;
    public $status;
    public $customer_id;
    
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    // read customers
    function read($id){
        if($id == 0){
            // select all query
            $query = "SELECT
                o.id, o.created, o.amount, o.customers_id, c.name customers_name,
                case
                    when o.status = 1 then 'Aberto' else 'Faturado' 
                end status
                FROM
                " . $this->table_name . " o
                    join customers c on (c.id = o.customers_id)
                ORDER BY
                o.created DESC";
        }else{
            // select all query
            $query = "SELECT
                o.id, o.created, o.amount, o.customers_id, c.name customers_name,
                case
                    when o.status = 1 then 'Aberto' else 'Faturado' 
                end status
                FROM
                " . $this->table_name . " o
                    join customers c on (c.id = o.customers_id)
                WHERE 
                    o.id = " . $id . " 
                ORDER BY
                o.created DESC";
        }
        
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
                    created=:created, amount=:amount, status=:status, customers_id=:customers_id";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->created=htmlspecialchars(strip_tags($this->created));
        $this->amount=htmlspecialchars(strip_tags($this->amount));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->customer_id=htmlspecialchars(strip_tags($this->customers_id));
        
    
        // bind values
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":customers_id", $this->customers_id);
        
        // execute query
        if($stmt->execute()){
            return $this->conn->lastInsertId();
        }

        return 0;
    }

    function updateBalance($orders_id){
        $query = " UPDATE " . $this->table_name . "
                SET
                    amount = (select sum(amount*quantity) total from products_has_order where orders_id = id group by orders_id)
                WHERE id = " . $orders_id;

        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    }

}