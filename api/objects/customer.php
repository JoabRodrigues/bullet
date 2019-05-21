<?php
include_once '../dao/daoCliente.php';

class Customer{
    
    // object properties
    public $id;
    public $nome;
    public $tipo;
    public $email;
    public $telefone;
    public $status;
    public $data_criacao;
    public $users_id;
    public $organizations_id;
 
    // read customers
    function read($id,$organization){
        if($id == 0){

            // busca na DAO o getAllClientes();
            $daoCliente = new daoCliente();

            try {
                $clientes = $daoCliente->getAllClientes($organization);
            } catch (Exception $e) {
                $clientes = array("message" => $e->getMessage());
            }

        }else{
            // select all query
           // busca na DAO o getAllClientes();
           $daoCliente = new daoCliente();

           try {
               $clientes = $daoCliente->getClienteById($id,$organization);
           } catch (Exception $e) {
               $clientes = array("message" => $e->getMessage());
           }
        }
    
        return $clientes;
    }

    // create customer
    function create(){
    
        // busca na DAO o getAllClientes();
        $daoCliente = new daoCliente();

        // sanitize
        $this->nome=htmlspecialchars(strip_tags($this->nome));
        $this->tipo=htmlspecialchars(strip_tags($this->tipo));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->telefone=htmlspecialchars(strip_tags($this->telefone));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->data_criacao=htmlspecialchars(strip_tags($this->data_criacao));
        $this->users_id=htmlspecialchars(strip_tags($this->users_id));
        $this->organizations_id=htmlspecialchars(strip_tags($this->organizations_id));
    
        try {
            $this->id = $daoCliente->insertCliente($this);
        } catch (Exception $e) {
            return false;
        }        
        return true;
    }
}