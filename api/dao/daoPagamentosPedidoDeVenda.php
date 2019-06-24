<?php
    include_once '../config/database.php';

    class daoPagamentosPedidoDeVenda{
        // database connection and table name
        private $conn;
        private $table_name = "pagamentos_pedido_de_venda";
        
        // constructor with $db as database connection
        public function __construct(){
            $database = new Database();
            $db = $database->getConnection();
            $this->conn = $db;
        }


        function getAllPagamentosPedidoDeVenda($pedido_de_venda_id){
            $query = "  select 
                            ppv.id, ppv.valor_total, 
                            ppv.numero_parcelas, ppv.data_vencimento, 
                            ppv.bandeira, ppv.ultimos_digitos_cartao, 
                            ppv.tipo_pagamento, ppv.pedido_de_venda_id, 
                            ppv.status, ppv.users_id
                        from " . $this->table_name . " ppv
                            join pedidos_de_venda pv on (pv.id = ppv.pedido_de_venda_id)
                        where 
                            ppv.pedido_de_venda_id = " . $pedido_de_venda_id ;
                        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
    
            // execute query
            $stmt->execute();

            $num = $stmt->rowCount();
            
            $pagamentos=array();

            if($num>0){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
                    

                    $pagamento = array(
                        "id" => $id,
                        "valor_total" => $valor_total,
                        "numero_parcelas" => $numero_parcelas,
                        "data_vencimento" => $data_vencimento,
                        "bandeira" => $bandeira,
                        "ultimos_digitos_cartao" => $ultimos_digitos_cartao,
                        "tipo_pagamento" => $tipo_pagamento,
                        "pedido_de_venda_id" => $pedido_de_venda_id,
                        "status" => $status,
                        "users_id" => $users_id
                    );

                    array_push($pagamentos,$pagamento);

                }
            }else{
                throw new Exception("Nenhum pagamento encontrado.");
            }

            return $pagamentos; 
        }

        function insertPagamentoPedidoDeVenda($pagamentoPedidoDeVenda){
            // query to insert record
            $query = "INSERT INTO
                " . $this->table_name . "
                    SET
                        valor_total=:valor_total,
                        numero_parcelas=:numero_parcelas,
                        data_vencimento=:data_vencimento,
                        bandeira=:bandeira,
                        ultimos_digitos_cartao=:ultimos_digitos_cartao,
                        tipo_pagamento=:tipo_pagamento,
                        pedido_de_venda_id=:pedido_de_venda_id, 
                        status=:status, 
                        users_id=:users_id";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // bind values
            $stmt->bindParam(":valor_total", $pagamentoPedidoDeVenda->valor_total);
            $stmt->bindParam(":numero_parcelas", $pagamentoPedidoDeVenda->numero_parcelas);
            $stmt->bindParam(":data_vencimento", $pagamentoPedidoDeVenda->data_vencimento);
            $stmt->bindParam(":bandeira", $pagamentoPedidoDeVenda->bandeira);
            $stmt->bindParam(":ultimos_digitos_cartao", $pagamentoPedidoDeVenda->ultimos_digitos_cartao);
            $stmt->bindParam(":tipo_pagamento", $pagamentoPedidoDeVenda->tipo_pagamento);
            $stmt->bindParam(":pedido_de_venda_id", $pagamentoPedidoDeVenda->pedido_de_venda_id);
            $stmt->bindParam(":status", $pagamentoPedidoDeVenda->status);
            $stmt->bindParam(":users_id", $pagamentoPedidoDeVenda->users_id);
            
            // execute query
            if($stmt->execute()){
                return $this->conn->lastInsertId();
            }else{
                throw new Exception("Nenhum pagamento criado.");
            }
        }
    }
?>