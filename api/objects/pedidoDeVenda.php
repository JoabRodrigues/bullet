<?php
include_once '../dao/daoPedidoDeVenda.php';
class PedidoDeVenda{
 
    // object properties
    public $id;
    public $data_criacao;
    public $valor_total;
    public $status;
    public $cliente_id;
    public $cliente_nome;
    public $users_id;
    public $organizations_id;
    
 
    // constructor with $db as database connection
    public function __construct(){
    }


    function getPedidos($id,$organization){
        if($id == 0){
            $daoPedidoDeVenda = new daoPedidoDeVenda();

            try {
                $pedidos = $daoPedidoDeVenda->getAllPedidosDeVenda($organization);
            } catch (Exception $e) {
                $pedidos = array("message" => $e->getMessage());
            }

        }else{
            $daoPedidoDeVenda = new daoPedidoDeVenda();

            try {
                $pedidos = $daoPedidoDeVenda->getPedidoDeVendaById($id,$organization);
            } catch (Exception $e) {
                $pedidos = array("message" => $e->getMessage());
            }
    
        }
        return $pedidos; 
        
    }

    function getPedidoFatura($id,$organization){
        $daoPedidoDeVenda = new daoPedidoDeVenda();
        
        $daoPedidoDeVenda->getPedidoFatura($id,$organization,$this);

        return $this;
    }

    function insertPedidoDeVenda(){

        $daoPedidoDeVenda = new daoPedidoDeVenda();

        $this->data_criacao=htmlspecialchars(strip_tags($this->data_criacao));
        $this->valor_total=htmlspecialchars(strip_tags($this->valor_total));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->cliente_id=htmlspecialchars(strip_tags($this->cliente_id));
        $this->users_id=htmlspecialchars(strip_tags($this->users_id));
        $this->organizations_id=htmlspecialchars(strip_tags($this->organizations_id));
        
        try {
            $this->id = $daoPedidoDeVenda->insertPedidoDeVenda($this);
        } catch (Exception $e) {
            return false;
        }        
        return true;

    }
    
    //TODO: Metodo usado pelo faturamento, mudar para buscar o getPedidoDeVendaById()
    function getOrder($id,$organization){
        $query = "SELECT
                    o.id, o.created, o.amount, o.customers_id, c.name customers_name, o.users_id, o.organizations_id, o.status
                FROM
                    " . $this->table_name . " o
                    join customers c on (c.id = o.customers_id)
                WHERE 
                    o.organizations_id = " . $organization . "
                    and o.id = " . $id . " 
                ORDER BY
                o.created DESC";
        
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $row[id];
        $this->created = $row[id];
        $this->amount = $row[amount];
        $this->customer_id = $row[customers_id];
        $this->$customer_name = $row[customers_name];
        $this->$users_id = $row[users_id];
        $this->organizations_id = $row[organizations_id];
        $this->status = $row[status];
        
    }

    function updateStatusPedidoDeVenda($id,$status){
        $daoPedidoDeVenda = new daoPedidoDeVenda();

        $daoPedidoDeVenda->updateStatusPedidoDeVenda($id,$status);

    }

    function updateBalance($id){

        $daoPedidoDeVenda = new daoPedidoDeVenda();

        $daoPedidoDeVenda->updateValorTotalPedidoDeVenda($id);

    }

}