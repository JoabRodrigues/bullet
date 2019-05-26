<?php
    include_once '../config/database.php';

    class daoPedidoDeVenda{
        // database connection and table name
        private $conn;
        private $table_name = "pedidos_de_venda";
        
        // constructor with $db as database connection
        public function __construct(){
            $database = new Database();
            $db = $database->getConnection();
            $this->conn = $db;
        }


        function getAllPedidosDeVenda($organization){
            $query = "SELECT
                o.id, o.data_criacao, o.valor_total, o.cliente_id, c.nome cliente_nome, o.users_id, o.organizations_id,
                case
                    when o.status = 1 then 'Aberto' else 'Faturado' 
                end status
                FROM
                " . $this->table_name . " o
                    join clientes c on (c.id = o.cliente_id)
                WHERE 
                    o.organizations_id = " . $organization . "
                ORDER BY
                o.data_criacao DESC";

            // prepare query statement
            $stmt = $this->conn->prepare($query);
    
            // execute query
            $stmt->execute();

            $num = $stmt->rowCount();

            
            $pedidos=array();

            if($num>0){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);

                    $pedido = array(
                        "id" => $id,
                        "data_criacao" => $data_criacao,
                        "valor_total" => $valor_total,
                        "cliente_id" => $cliente_id,
                        "cliente_nome" => $cliente_nome,
                        "status" => $status,
                        "users_id" => $users_id,
                        "organizations_id" => $organizations_id
                    );
                    array_push($pedidos,$pedido);
                }
            }else{
                throw new Exception("Nenhum pedido de venda encontrado.");
            }

            return $pedidos; 
        }

        function getPedidoDeVendaById($id,$organization){

            $query = "SELECT
            o.id, o.data_criacao, o.valor_total, o.cliente_id, c.nome cliente_nome, o.users_id, o.organizations_id,
            case
                when o.status = 1 then 'Aberto' else 'Faturado' 
            end status
            FROM
            " . $this->table_name . " o
                join clientes c on (c.id = o.cliente_id)
            WHERE 
                o.organizations_id = " . $organization . "
                and o.id = " . $id . " 
            ORDER BY
            o.data_criacao DESC";


            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // execute query
            $stmt->execute();

            $num = $stmt->rowCount();

            
            $pedidos=array();

            if($num>0){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);

                    $pedido = array(
                        "id" => $id,
                        "data_criacao" => $data_criacao,
                        "valor_total" => $valor_total,
                        "cliente_id" => $cliente_id,
                        "cliente_nome" => $cliente_nome,
                        "status" => $status,
                        "users_id" => $users_id,
                        "organizations_id" => $organizations_id
                    );
                    array_push($pedidos,$pedido);
                }
            }else{
                throw new Exception("Nenhum pedido de venda encontrado.");
            }

            return $pedidos; 

        }

        function insertPedidoDeVenda($pedido){
            $query = "INSERT INTO
                        " . $this->table_name . "
                    SET
                        data_criacao=:data_criacao, 
                        valor_total=:valor_total, 
                        cliente_id=:cliente_id, 
                        status=:status, 
                        users_id=:users_id, 
                        organizations_id=:organizations_id";


            // prepare query
            $stmt = $this->conn->prepare($query);

            // bind values
            $stmt->bindParam(":data_criacao", $pedido->data_criacao);
            $stmt->bindParam(":valor_total", $pedido->valor_total);
            $stmt->bindParam(":cliente_id", $pedido->cliente_id);
            $stmt->bindParam(":status", $pedido->status);
            $stmt->bindParam(":users_id", $pedido->users_id);
            $stmt->bindParam(":organizations_id", $pedido->organizations_id);
            
            
            // execute query
            if($stmt->execute()){
                return $this->conn->lastInsertId();
            }else{
                throw new Exception("Nenhum pedido criado.");
            }

        }

        function updateStatusPedidoDeVenda($id,$status){
            $query = " UPDATE " . $this->table_name . " o
                    SET
                        o.status = " . $status . "
                    WHERE o.id = " . $id;

            $stmt = $this->conn->prepare($query);
    
            // execute query
            $stmt->execute();
        }

        function updateValorTotalPedidoDeVenda($id){

            $query = " UPDATE " . $this->table_name . " o
                SET
                    amount = (select sum(amount*quantity) total from products_has_order po where po.orders_id = o.id group by orders_id)
                WHERE o.id = " . $id;

            $stmt = $this->conn->prepare($query);

            // execute query
            $stmt->execute();
        }
    }
?>