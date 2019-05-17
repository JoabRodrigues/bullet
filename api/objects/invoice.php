<?php
class Invoice{
 
    // database connection and table name
    private $conn;
    private $table_name = "invoices";
 
    // object properties
    public $id;
    public $created;
    public $amount;
    public $status;
    public $orders_id;
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
                        i.id, i.created, i.amount, i.orders_id, i.users_id, i.organizations_id,
                        case 
                            when i.status = 1 then 'Fechada' else 'Cancelada' 
                        end status
                    FROM
                        " . $this->table_name . " i
                    WHERE 
                        i.organizations_id = " . $organization . "
                    ORDER BY
                        i.created DESC";
        }else{
            // select all query
            $query = "SELECT
                        i.id, i.created, i.amount, i.orders_id, i.users_id, i.organizations_id,
                        case 
                            when i.status = 1 then 'Fechada' else 'Cancelada' 
                        end status
                    FROM
                        " . $this->table_name . " i
                    WHERE 
                        i.organizations_id = " . $organization . "
                        and i.id = " . $id . " 
                    ORDER BY
                        i.created DESC";
        }
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create invoice
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                created=:created, amount=:amount, status=:status, orders_id=:orders_id, users_id=:users_id, organizations_id=:organizations_id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->created=htmlspecialchars(strip_tags($this->created));
        $this->amount=htmlspecialchars(strip_tags($this->amount));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->orders_id=htmlspecialchars(strip_tags($this->orders_id));
        $this->users_id=htmlspecialchars(strip_tags($this->users_id));
        $this->organizations_id=htmlspecialchars(strip_tags($this->organizations_id));
    
        // bind values
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":orders_id", $this->orders_id);
        $stmt->bindParam(":users_id", $this->users_id);
        $stmt->bindParam(":organizations_id", $this->organizations_id);
        
        // execute query
        if($stmt->execute()){
            return true;
        }
        
        return false;
    }
}