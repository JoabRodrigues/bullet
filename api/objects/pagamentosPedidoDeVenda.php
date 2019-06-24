<?php
include_once '../objects/pedidoDeVenda.php';
include_once '../dao/daoPagamentosPedidoDeVenda.php';

class pagamentosPedidoDeVenda{
 
    // object properties
    public $id;
    public $valor_total;
    public $numero_parcelas;
    public $data_vencimento;
    public $bandeira;
    public $ultimos_digitos_cartao;
    public $tipo_pagamento;
    public $pedido_de_venda_id;
    public $status;
    public $users_id;
    
    public function __construct(){}


    function getPagamentosPedidoDeVenda($pedido_de_venda_id){

        $daoPagamentosPedidoDeVenda = new daoPagamentosPedidoDeVenda();

            try {
                $itens = $daoPagamentosPedidoDeVenda->getAllPagamentosPedidoDeVenda($pedido_de_venda_id);
            } catch (Exception $e) {
                $itens = array("message" => $e->getMessage());
            }
            return $itens;
    }


    // create customer
    function insertPagamentosPedidoDeVenda(){
    
        $daoPagamentosPedidoDeVenda = new daoPagamentosPedidoDeVenda();

        $this->valor_total = htmlspecialchars(strip_tags($this->valor_total));
        $this->numero_parcelas = htmlspecialchars(strip_tags($this->numero_parcelas));
        $this->data_vencimento = htmlspecialchars(strip_tags($this->data_vencimento));
        $this->bandeira = htmlspecialchars(strip_tags($this->bandeira));
        $this->ultimos_digitos_cartao = htmlspecialchars(strip_tags($this->ultimos_digitos_cartao));
        $this->tipo_pagamento = htmlspecialchars(strip_tags($this->tipo_pagamento));
        $this->pedido_de_venda_id = htmlspecialchars(strip_tags($this->pedido_de_venda_id));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->users_id = htmlspecialchars(strip_tags($this->users_id));
        
        
        try {
            $this->id = $daoPagamentosPedidoDeVenda->insertPagamentoPedidoDeVenda($this);
        } catch (Exception $e) {
            return false;
        }        
        return true;
    }
}