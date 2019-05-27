<?php
    include_once '../config/database.php';

    class daoFatura{
        // database connection and table name
        private $conn;
        private $table_name = "faturas";
        
        // constructor with $db as database connection
        public function __construct(){
            $database = new Database();
            $db = $database->getConnection();
            $this->conn = $db;
        }

        function getAllFaturas($organization){
            $query = "SELECT
                        f.id, f.data_criacao, f.valor_total, f.pedido_de_venda_id, f.users_id, f.organizations_id,
                        case 
                            when f.status = 1 then 'Fechada' else 'Cancelada' 
                        end status
                    FROM
                        " . $this->table_name . " f
                    WHERE 
                        f.organizations_id = " . $organization . "
                    ORDER BY
                        f.data_criacao DESC";

            // prepare query statement
            $stmt = $this->conn->prepare($query);
    
            // execute query
            $stmt->execute();

            $num = $stmt->rowCount();
            
            $faturas=array();

            if($num>0){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);

                    $fatura = array(
                        "id" => $id,
                        "data_criacao" => $data_criacao,
                        "valor_total" => $valor_total,
                        "pedido_de_venda_id" => $pedido_de_venda_id,
                        "status" => $status,
                        "users_id" => $users_id,
                        "organizations_id" => $organizations_id
                    );

                    array_push($faturas,$fatura);

                }
            }else{
                throw new Exception("Nenhuma fatura encontrada.");
            }

            return $faturas; 
        }

        function getFaturaById($id,$organization){
            $query = "SELECT
                        f.id, f.data_criacao, f.valor_total, f.pedido_de_venda_id, f.users_id, f.organizations_id,
                        case 
                            when f.status = 1 then 'Fechada' else 'Cancelada' 
                        end status
                    FROM
                        " . $this->table_name . " f
                    WHERE 
                        f.organizations_id = " . $organization . "
                    ORDER BY
                        f.data_criacao DESC";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // execute query
            $stmt->execute();

            $num = $stmt->rowCount();
            
            $faturas=array();

            if($num>0){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);

                    $fatura = array(
                        "id" => $id,
                        "data_criacao" => $data_criacao,
                        "valor_total" => $valor_total,
                        "pedido_de_venda_id" => $pedido_de_venda_id,
                        "status" => $status,
                        "users_id" => $users_id,
                        "organizations_id" => $organizations_id
                    );

                    array_push($faturas,$fatura);

                }
            }else{
                throw new Exception("Nenhuma fatura encontrada.");
            }

            return $faturas;

        }

        function insertFatura($fatura){

            $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    data_criacao=:data_criacao, 
                    valor_total=:valor_total, 
                    pedido_de_venda_id=:pedido_de_venda_id, 
                    status=:status, 
                    users_id=:users_id, 
                    organizations_id=:organizations_id";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // bind values
            $stmt->bindParam(":data_criacao", $fatura->data_criacao);
            $stmt->bindParam(":valor_total", $fatura->valor_total);
            $stmt->bindParam(":pedido_de_venda_id", $fatura->pedido_de_venda_id);
            $stmt->bindParam(":status", $fatura->status);
            $stmt->bindParam(":users_id", $fatura->users_id);
            $stmt->bindParam(":organizations_id", $fatura->organizations_id);
        
            // execute query
            if($stmt->execute()){
                return $this->conn->lastInsertId();
            }else{
                throw new Exception("Nenhuma fatura criada.");
            }

        }
    }
?>