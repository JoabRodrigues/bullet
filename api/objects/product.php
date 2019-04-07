<?php
class Product{
 
    // database connection and table name
    private $conn;
    private $table_name = "products";
 
    // object properties
    public $id;
    public $name;
    public $amount;
    public $status;
    public $created;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    // read products
    function read($id){
        if($id == 0){
            // select all query
            $query = "SELECT
                p.id, p.name, p.amount, p.created, 
                case
                    when p.status = 1 then 'Ativo' else 'Inativo' 
                end status
                FROM
                " . $this->table_name . " p
                ORDER BY
                p.created DESC";
        }else{
            // select all query
            $query = "SELECT
                p.id, p.name, p.amount, p.created, 
                case
                    when p.status = 1 then 'Ativo' else 'Inativo' 
                end status
                FROM
                " . $this->table_name . " p
                WHERE 
                    id = " . $id . " 
                ORDER BY
                p.created DESC";
        }
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create product
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    name=:name, amount=:amount, status=:status, created=:created";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->amount=htmlspecialchars(strip_tags($this->amount));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->created=htmlspecialchars(strip_tags($this->created));
    
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":created", $this->created);
        
        // execute query
        if($stmt->execute()){
            return true;
        }
        
        return false;
    }
}