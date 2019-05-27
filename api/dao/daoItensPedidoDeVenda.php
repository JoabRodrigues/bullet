<?php
    include_once '../config/database.php';

    class daoItensPedidoDeVenda{
        // database connection and table name
        private $conn;
        private $table_name = "itens_pedido_de_venda";
        
        // constructor with $db as database connection
        public function __construct(){
            $database = new Database();
            $db = $database->getConnection();
            $this->conn = $db;
        }


        function getAllItensPedidoDeVenda($pedido_de_venda_id){
            $query = "SELECT
                ipv.id, ipv.produto_id, p.nome produto_nome, ipv.pedido_de_venda_id, ipv.quantidade, ipv.valor, ipv.users_id, 
                case
                    when ipv.status = 1 then 'Ativo' else 'Cancelado' 
                end status
                FROM
                " . $this->table_name . " ipv
                    join produtos p on (p.id = ipv.produto_id)
                WHERE 
                    ipv.pedido_de_venda_id = " . $pedido_de_venda_id ;
            
            // prepare query statement
            $stmt = $this->conn->prepare($query);
    
            // execute query
            $stmt->execute();

            $num = $stmt->rowCount();
            
            $produtos=array();

            if($num>0){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);

                    $produto = array(
                        "id" => $id,
                        "produto_id" => $produto_id,
                        "produto_nome" => $produto_nome,
                        "pedido_de_venda_id" => $pedido_de_venda_id,
                        "quantidade" => $quantidade,
                        "valor" => $valor,
                        "status" => $status,
                        "users_id" => $users_id
                    );

                    array_push($produtos,$produto);

                }
            }else{
                throw new Exception("Nenhum produto encontrado.");
            }

            return $produtos; 
        }

        function insertItemPedidoDeVenda($itemPedidoDeVenda){
            // query to insert record
            $query = "INSERT INTO
                " . $this->table_name . "
                    SET
                        produto_id=:produto_id, 
                        pedido_de_venda_id=:pedido_de_venda_id, 
                        quantidade=:quantidade, 
                        valor=:valor, 
                        status=:status, 
                        users_id=:users_id";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // bind values
            $stmt->bindParam(":produto_id", $itemPedidoDeVenda->produto_id);
            $stmt->bindParam(":pedido_de_venda_id", $itemPedidoDeVenda->pedido_de_venda_id);
            $stmt->bindParam(":quantidade", $itemPedidoDeVenda->quantidade);
            $stmt->bindParam(":valor", $itemPedidoDeVenda->valor);
            $stmt->bindParam(":status", $itemPedidoDeVenda->status);
            $stmt->bindParam(":users_id", $itemPedidoDeVenda->users_id);
            
            // execute query
            if($stmt->execute()){
                return $this->conn->lastInsertId();
            }else{
                throw new Exception("Nenhum item criado.");
            }
        }
    }
?>