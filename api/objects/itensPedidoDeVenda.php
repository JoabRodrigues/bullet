<?php
include_once '../objects/pedidoDeVenda.php';
include_once '../dao/daoItensPedidoDeVenda.php';

class ItensPedidoDeVenda{
 
    // database connection and table name
    private $conn;
    private $table_name = "itens_pedido_de_venda";
 
    // object properties
    public $id;
    public $produto_id;
    public $pedido_de_venda_id;
    public $quantidade;
    public $valor;
    public $status;
    public $users_id;
    
    public function __construct(){}


    function getItensPedidoDeVenda($pedido_de_venda_id){

        $daoItensPedidoDeVenda = new daoItensPedidoDeVenda();

            try {
                $itens = $daoItensPedidoDeVenda->getAllItensPedidoDeVenda($pedido_de_venda_id);
            } catch (Exception $e) {
                $itens = array("message" => $e->getMessage());
            }
            return $itens;
    }


    // create customer
    function insertItemPedidoDeVenda(){
    
        $daoItensPedidoDeVenda = new daoItensPedidoDeVenda();

        // sanitize
        $this->produto_id=htmlspecialchars(strip_tags($this->produto_id));
        $this->pedido_de_venda_id=htmlspecialchars(strip_tags($this->pedido_de_venda_id));
        $this->quantidade=htmlspecialchars(strip_tags($this->quantidade));
        $this->valor=htmlspecialchars(strip_tags($this->valor));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->users_id=htmlspecialchars(strip_tags($this->users_id));
        
        try {
            $this->id = $daoItensPedidoDeVenda->insertItemPedidoDeVenda($this);
        } catch (Exception $e) {
            return false;
        }        
        return true;
    }
}