<?php
class Customer{
 
    // database connection and table name
    private $conn;
    private $table_name = "customers";
 
    // object properties
    public $id;
    public $name;
    public $type;
    public $email;
    public $phone;
    public $status;
    public $created;
    public $users_id;
    public $organizations_id;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    // read customers
    function read($id,$organization){
        if($id == 0){
            // select all query
            $query = "SELECT
                c.id, c.name, c.email, c.phone, c.created, c.users_id, c.organizations_id,
                case
                    when c.type = 1 then 'Pessoa Física' else 'Pessoa Jurídica'
                end type,
                case
                    when c.status = 1 then 'Ativo' else 'Inativo' 
                end status
                FROM
                " . $this->table_name . " c
                WHERE 
                    c.organizations_id = " . $organization . "
                ORDER BY
                c.created DESC";
        }else{
            // select all query
            $query = "SELECT
                c.id, c.name, c.email, c.phone, c.created, c.users_id, c.organizations_id,
                case
                    when c.type = 1 then 'Pessoa Física' else 'Pessoa Jurídica'
                end type,
                case
                    when c.status = 1 then 'Ativo' else 'Inativo' 
                end status
                FROM
                " . $this->table_name . " c
                WHERE 
                    c.organizations_id = " . $organization . "
                    and c.id = " . $id . " 
                ORDER BY
                c.created DESC";
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
                    name=:name, type=:type, email=:email, phone=:phone, status=:status, created=:created, users_id=:users_id, organizations_id=:organizations_id";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->type=htmlspecialchars(strip_tags($this->type));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->created=htmlspecialchars(strip_tags($this->created));
        $this->users_id=htmlspecialchars(strip_tags($this->users_id));
        $this->organizations_id=htmlspecialchars(strip_tags($this->organizations_id));
    
        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":users_id", $this->users_id);
        $stmt->bindParam(":organizations_id", $this->organizations_id);
        // execute query
        if($stmt->execute()){
            return true;
        }
        
        return false;
    }
}