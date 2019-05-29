<?php
include "validasessao.php";

$service_url = 'http://localhost/api/endpoints/pedidosDeVenda.php?token='. $_SESSION['tokenUsuario'] . '&organization=' . $_SESSION['orgUsuario'];
$curl = curl_init($service_url);

$curl_post_data = array(
        'valor_total' => 0,
        'cliente_id' => $_POST['customers_id']
);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));

$curl_response = curl_exec($curl);

$data_response = json_decode($curl_response);

if($data_response->message == 'Pedido de venda criado com sucesso.'){
    //envia para a pagina do pedido com o id no get
    header("Location: /pedido?pedido_de_venda_id=" . $data_response->id);
}else{
    include "pages/header.html";

    echo '<p>Erro ao criar o pedido. Favor informar o administrador.<p>';
    echo '<p>Erro API' . $data_response->message . '</p>';
    echo '<p><a href="/vendas"><button type="button" class="btn btn-success">Voltar</button></a></p>';
}

?>