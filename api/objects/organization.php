<?php
class Organization{
 
    // database connection and table name
    private $conn;
    private $table_name = "organizations";
 
    // object properties
    public $id;
    public $name;
    public $status;
    public $created;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    // read customers
    function read($id){
        if($id == 0){
            // select all query
            $query = "SELECT
                org.id, org.name, org.created,
                case
                    when org.status = 1 then 'Ativa' else 'Inativa' 
                end status
                FROM
                " . $this->table_name . " org
                ORDER BY
                org.created DESC";
        }else{
            // select all query
            $query = "SELECT
                org.id, org.name, org.created,
                case
                    when org.status = 1 then 'Ativa' else 'Inativa' 
                end status
                FROM
                " . $this->table_name . " org
                WHERE 
                    org.id = " . $id . " 
                ORDER BY
                org.created DESC";
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
                    name=:name, status=:status, created=:created";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->created=htmlspecialchars(strip_tags($this->created));
    
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":created", $this->created);
        
        // execute query
        if($stmt->execute()){
            return true;
        }
        
        return false;
    }
}