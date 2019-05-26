<?php
    include_once '../config/database.php';

    class daoProduto{
        // database connection and table name
        private $conn;
        private $table_name = "produtos";
        
        // constructor with $db as database connection
        public function __construct(){
            $database = new Database();
            $db = $database->getConnection();
            $this->conn = $db;
        }


        function getAllProdutos($organization){
            // select all query
            $query = "SELECT
                p.id, p.nome, p.valor, p.data_criacao, p.users_id, p.organizations_id,
                case
                    when p.status = 1 then 'Ativo' else 'Inativo' 
                end status
                FROM
                " . $this->table_name . " p
                WHERE
                    p.organizations_id = " . $organization . "
                ORDER BY
                p.data_criacao DESC";

            
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
                        "nome" => $nome,
                        "valor" => $valor,
                        "status" => $status,
                        "data_criacao" => $data_criacao,
                        "users_id" => $users_id,
                        "organizations_id" => $organizations_id
                    );

                    array_push($produtos,$produto);

                }
            }else{
                throw new Exception("Nenhum produto encontrado.");
            }

            return $produtos; 
        }

        function getProdutoById($id,$organization){

            $query = "SELECT
                p.id, p.nome, p.valor, p.data_criacao, p.users_id, p.organizations_id,
                case
                    when p.status = 1 then 'Ativo' else 'Inativo' 
                end status
                FROM
                " . $this->table_name . " p
                WHERE
                    p.organizations_id = " . $organization . "
                    and p.id = " . $id . " 
                ORDER BY
                p.data_criacao DESC";



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
                        "nome" => $nome,
                        "valor" => $valor,
                        "status" => $status,
                        "data_criacao" => $data_criacao,
                        "users_id" => $users_id,
                        "organizations_id" => $organizations_id
                    );

                    array_push($produtos,$produto);

                }
            }else{
                throw new Exception("Nenhum produto encontrado.");
            }

            return $produtos;

        }

        function insertProduto($produto){
            $query = "INSERT INTO
                " . $this->table_name . "
                SET
                    nome=:nome, 
                    valor=:valor, 
                    status=:status, 
                    data_criacao=:data_criacao, 
                    users_id=:users_id, 
                    organizations_id=:organizations_id";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // bind values
            $stmt->bindParam(":nome", $produto->nome);
            $stmt->bindParam(":valor", $produto->valor);
            $stmt->bindParam(":status", $produto->status);
            $stmt->bindParam(":data_criacao", $produto->data_criacao);
            $stmt->bindParam(":users_id", $produto->users_id);
            $stmt->bindParam(":organizations_id", $produto->organizations_id);
        
            // execute query
            if($stmt->execute()){
                return $this->conn->lastInsertId();
            }else{
                throw new Exception("Nenhum produto criado.");
            }
        }
    }
?>