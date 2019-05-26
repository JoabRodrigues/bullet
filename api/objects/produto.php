<?php
include_once '../dao/daoProduto.php';

class Produto{
 
    // database connection and table name
    private $conn;
    private $table_name = "produtos";
 
    // object properties
    public $id;
    public $nome;
    public $valor;
    public $status;
    public $data_criacao;
    public $users_id;
    public $organizations_id;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function getProdutos($id,$organization){
        if($id == 0){
            $daoProduto = new daoProduto();

            try {
                $produtos = $daoProduto->getAllProdutos($organization);
            } catch (Exception $e) {
                $produtos = array("message" => $e->getMessage());
            }

        }else{
            $daoProduto = new daoProduto();

            try {
                $produtos = $daoProduto->getProdutoById($id,$organization);
            } catch (Exception $e) {
                $produtos = array("message" => $e->getMessage());
            }
        }
        return $produtos;
    }

    function insertProduto(){
        $daoProduto = new daoProduto();

        $this->nome=htmlspecialchars(strip_tags($this->nome));
        $this->valor=htmlspecialchars(strip_tags($this->valor));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->data_criacao=htmlspecialchars(strip_tags($this->data_criacao));
        $this->users_id=htmlspecialchars(strip_tags($this->users_id));
        $this->organizations_id=htmlspecialchars(strip_tags($this->organizations_id));
        
        try {
            $this->id = $daoProduto->insertProduto($this);
        } catch (Exception $e) {
            return false;
        }        
        return true;
    }

}