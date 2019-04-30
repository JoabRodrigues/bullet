<?php
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $email;
    public $password;
    public $token;
    public $status;
    public $created;
    public $organizations_id;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }


    // read customers
    function read($email,$password){
        if(is_null($email) && is_null($password)){
            // select all query
            $query = "SELECT
                u.id, u.email, u.password, u.token, u.created, u.organizations_id,
                case
                    when u.status = 1 then 'Ativo' else 'Inativo' 
                end status
                FROM
                " . $this->table_name . " u
                ORDER BY
                u.created DESC";
        }else{
            // select all query
            $query = "SELECT
                u.id, u.email, u.password, u.token, u.created, u.organizations_id,
                case
                    when u.status = 1 then 'Ativo' else 'Inativo' 
                end status
                FROM
                " . $this->table_name . " u
                WHERE 
                    u.email = '" . $email . "'
                    and u.password = '" . $password . "' 
                ORDER BY
                u.created DESC";
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
                    email=:email, password=:password, status=:status, token=:token, organizations_id=:organizations_id, created=:created";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->token=htmlspecialchars(strip_tags($this->token));
        $this->organizations_id=htmlspecialchars(strip_tags($this->organizations_id));
        $this->created=htmlspecialchars(strip_tags($this->created));
    
        // bind values
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":token", $this->token);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":organizations_id", $this->organizations_id);
        $stmt->bindParam(":created", $this->created);
        
        // execute query
        if($stmt->execute()){
            return true;
        }
        
        return false;
    }

    function validaToken($token,$organization){
        $query = "SELECT u.id
                FROM
                    " . $this->table_name . " u
                WHERE
                    organizations_id = " . $organization . "
                    and token = '" . $token . "' limit 1 ";

        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            return $row[id];  
        }

        return null;
        
        
    }
}