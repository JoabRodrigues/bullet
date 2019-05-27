<?php

include_once '../dao/daoFatura.php';

class Fatura{
    
    public $id;
    public $data_criacao;
    public $valor_total;
    public $status;
    public $pedido_de_venda_id;
    public $users_id;
    public $organizations_id;
    
    // constructor with $db as database connection
    public function __construct(){}

    function getFaturas($id,$organization){
        if($id == 0){
            $daoFatura = new daoFatura();

            try {
                $faturas = $daoFatura->getAllFaturas($organization);
            } catch (Exception $e) {
                $faturas = array("message" => $e->getMessage());
            }

        }else{
           $daoFatura = new daoFatura();

           try {
               $faturas = $daoFatura->getFaturaById($id,$organization);
           } catch (Exception $e) {
               $faturas = array("message" => $e->getMessage());
           }
        }
    
        return $faturas;
    }

    function insertFatura(){
        $daoFatura = new daoFatura();

        $this->data_criacao=htmlspecialchars(strip_tags($this->data_criacao));
        $this->valor_total=htmlspecialchars(strip_tags($this->valor_total));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->pedido_de_venda_id=htmlspecialchars(strip_tags($this->pedido_de_venda_id));
        $this->users_id=htmlspecialchars(strip_tags($this->users_id));
        $this->organizations_id=htmlspecialchars(strip_tags($this->organizations_id));
    
        try {
            $this->id = $daoFatura->insertFatura($this);
        } catch (Exception $e) {
            return false;
        }        
        return true;

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
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }
}