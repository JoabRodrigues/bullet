<?php
    include_once '../config/database.php';

    class daoCliente{
        // database connection and table name
        private $conn;
        private $table_name = "clientes";
        
        // constructor with $db as database connection
        public function __construct(){
            $database = new Database();
            $db = $database->getConnection();
            $this->conn = $db;
        }


        function getAllClientes($organization){

            $query = "SELECT
                        c.id, c.nome, c.email, c.telefone, c.data_criacao, c.users_id, c.organizations_id,
                        case
                            when c.tipo = 1 then 'Pessoa Física' else 'Pessoa Jurídica'
                        end tipo,
                        case
                            when c.status = 1 then 'Ativo' else 'Inativo' 
                        end status
                    FROM
                        " . $this->table_name . " c
                    WHERE 
                        c.organizations_id = " . $organization . "
                    ORDER BY
                        c.data_criacao DESC";

            // prepare query statement
            $stmt = $this->conn->prepare($query);
    
            // execute query
            $stmt->execute();

            $num = $stmt->rowCount();
            
            $clientes=array();

            if($num>0){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);

                    $cliente = array(
                        "id" => $id,
                        "nome" => $nome,
                        "tipo" => $tipo,
                        "email" => $email,
                        "telefone" => $telefone,
                        "status" => $status,
                        "data_criacao" => $data_criacao,
                        "users_id" => $users_id,
                        "organizations_id" => $organizations_id
                    );

                    array_push($clientes,$cliente);

                }
            }else{
                throw new Exception("Nenhum cliente encontrado.");
            }

            return $clientes; 
        }

        function getClienteById($id,$organization){
            $query = "SELECT
                        c.id, c.nome, c.email, c.telefone, c.data_criacao, c.users_id, c.organizations_id,
                        case
                            when c.tipo = 1 then 'Pessoa Física' else 'Pessoa Jurídica'
                        end tipo,
                        case
                            when c.status = 1 then 'Ativo' else 'Inativo' 
                        end status
                    FROM
                        " . $this->table_name . " c
                    WHERE 
                        c.organizations_id = " . $organization . "
                        and c.id = " . $id . " 
                    ORDER BY
                        c.data_criacao DESC";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // execute query
            $stmt->execute();

            $num = $stmt->rowCount();
            
            $clientes=array();

            if($num>0){
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    extract($row);

                    $cliente = array(
                        "id" => $id,
                        "nome" => $nome,
                        "tipo" => $tipo,
                        "email" => $email,
                        "telefone" => $telefone,
                        "status" => $status,
                        "data_criacao" => $data_criacao,
                        "users_id" => $users_id,
                        "organizations_id" => $organizations_id
                    );

                    array_push($clientes,$cliente);

                }
            }else{
                throw new Exception("Nenhum cliente encontrado.");
            }

            return $clientes; // mudar para retornar a lista de clientes

        }

        function insertCliente($cliente){


            // query to insert record
            $query = "INSERT INTO
                        " . $this->table_name . "
                    SET
                        nome=:nome, 
                        tipo=:tipo, 
                        email=:email, 
                        telefone=:telefone, 
                        status=:status, 
                        data_criacao=:data_criacao, 
                        users_id=:users_id, 
                        organizations_id=:organizations_id";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // bind values
            $stmt->bindParam(":nome", $cliente->nome);
            $stmt->bindParam(":tipo", $cliente->tipo);
            $stmt->bindParam(":email", $cliente->email);
            $stmt->bindParam(":telefone", $cliente->telefone);
            $stmt->bindParam(":status", $cliente->status);
            $stmt->bindParam(":data_criacao", $cliente->data_criacao);
            $stmt->bindParam(":users_id", $cliente->users_id);
            $stmt->bindParam(":organizations_id", $cliente->organizations_id);
        
            // execute query
            if($stmt->execute()){
                return $this->conn->lastInsertId();
            }else{
                throw new Exception("Nenhum cliente encontrado.");
            }

        }
    }
?>