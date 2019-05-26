<?php
include_once '../dao/daoCliente.php';

class Cliente{

    public $id;
    public $nome;
    public $tipo;
    public $email;
    public $telefone;
    public $status;
    public $data_criacao;
    public $users_id;
    public $organizations_id;
 
    function getClientes($id,$organization){
        if($id == 0){
            $daoCliente = new daoCliente();

            try {
                $clientes = $daoCliente->getAllClientes($organization);
            } catch (Exception $e) {
                $clientes = array("message" => $e->getMessage());
            }

        }else{
           $daoCliente = new daoCliente();

           try {
               $clientes = $daoCliente->getClienteById($id,$organization);
           } catch (Exception $e) {
               $clientes = array("message" => $e->getMessage());
           }
        }
    
        return $clientes;
    }

    function insertCliente(){
    
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