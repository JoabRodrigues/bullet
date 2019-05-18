<?php
include_once '../config/database.php';

class Parcela{
 
    // database connection and table name
    private $conn;
    private $table_name = "parcelas";
 
    // object properties
    public $id;
    public $data_criacao;
    public $data_vencimento;
    public $data_pagamento;
    public $forma_pagamento;
    public $numero_parcela;
    public $status;
    public $fatura_id;
    public $bandeira_cartao;
    public $ultimos_4_digitos_cartao;
    public $valor;
    
    // constructor with $db as database connection
    public function __construct(){
        $database = new Database();
        $db = $database->getConnection();
        $this->conn = $db;
    }


    function criaParcela(){
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    data_criacao=:data_criacao, 
                    data_vencimento=:data_vencimento,
                    forma_pagamento=:forma_pagamento,
                    numero_parcela=:numero_parcela,
                    status=:status,
                    fatura_id=:fatura_id,
                    bandeira_cartao=:bandeira_cartao,
                    ultimos_4_digitos_cartao=:ultimos_4_digitos_cartao,
                    valor=:valor ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->data_criacao=htmlspecialchars(strip_tags($this->data_criacao));
        $this->data_vencimento=htmlspecialchars(strip_tags($this->data_vencimento));
        $this->forma_pagamento=htmlspecialchars(strip_tags($this->forma_pagamento));
        $this->numero_parcela=htmlspecialchars(strip_tags($this->numero_parcela));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->fatura_id=htmlspecialchars(strip_tags($this->fatura_id));
        $this->bandeira_cartao=htmlspecialchars(strip_tags($this->bandeira_cartao));
        $this->ultimos_4_digitos_cartao=htmlspecialchars(strip_tags($this->ultimos_4_digitos_cartao));
        $this->valor=htmlspecialchars(strip_tags($this->valor));
        
        if(empty($this->bandeira_cartao)){
            $this->bandeira_cartao = null;
        }
        
        if(empty($this->ultimos_4_digitos_cartao)){
            $this->ultimos_4_digitos_cartao = null;
        }


        // bind values
        $stmt->bindParam(":data_criacao", $this->data_criacao);
        $stmt->bindParam(":data_vencimento", $this->data_vencimento);
        $stmt->bindParam(":forma_pagamento", $this->forma_pagamento);
        $stmt->bindParam(":numero_parcela", $this->numero_parcela);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":fatura_id", $this->fatura_id);
        $stmt->bindParam(":bandeira_cartao", $this->bandeira_cartao);
        $stmt->bindParam(":ultimos_4_digitos_cartao", $this->ultimos_4_digitos_cartao);
        $stmt->bindParam(":valor", $this->valor);
        
        // execute query
        $stmt->execute();

        
    }

    //calcula a data de vencimento da parcela
    function calculaDataVencimento($data,$mes){
        $data = date('Y-m-d', strtotime("+". $mes . " months", strtotime($data)));
        return $data;
    }


    // read customers
    function read($id,$organization){
        if($id == 0){
            // select all query
            $query = "SELECT
                        i.id, i.created, i.amount, i.orders_id, i.users_id, i.organizations_id,
                        case 
                            when i.status = 1 then 'Fechada' else 'Cancelada' 
                        end status
                    FROM
                        " . $this->table_name . " i
                    WHERE 
                        i.organizations_id = " . $organization . "
                    ORDER BY
                        i.created DESC";
        }else{
            // select all query
            $query = "SELECT
                        i.id, i.created, i.amount, i.orders_id, i.users_id, i.organizations_id,
                        case 
                            when i.status = 1 then 'Fechada' else 'Cancelada' 
                        end status
                    FROM
                        " . $this->table_name . " i
                    WHERE 
                        i.organizations_id = " . $organization . "
                        and i.id = " . $id . " 
                    ORDER BY
                        i.created DESC";
        }
        
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // create invoice
    function create(){
    
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                created=:created, amount=:amount, status=:status, orders_id=:orders_id, users_id=:users_id, organizations_id=:organizations_id";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->created=htmlspecialchars(strip_tags($this->created));
        $this->amount=htmlspecialchars(strip_tags($this->amount));
        $this->status=htmlspecialchars(strip_tags($this->status));
        $this->orders_id=htmlspecialchars(strip_tags($this->orders_id));
        $this->users_id=htmlspecialchars(strip_tags($this->users_id));
        $this->organizations_id=htmlspecialchars(strip_tags($this->organizations_id));
    
        // bind values
        $stmt->bindParam(":created", $this->created);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":orders_id", $this->orders_id);
        $stmt->bindParam(":users_id", $this->users_id);
        $stmt->bindParam(":organizations_id", $this->organizations_id);
        
        // execute query
        if($stmt->execute()){
            return true;
        }
        
        return false;
    }
}