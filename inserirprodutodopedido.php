<?php
include "validasessao.php";

$service_url = 'http://localhost/api/endpoints/itensPedidoDeVenda.php?token='. $_SESSION['tokenUsuario'] . '&organization=' . $_SESSION['orgUsuario'];
$curl = curl_init($service_url);

$curl_post_data = array(
        'pedido_de_venda_id' => $_POST['pedido_de_venda_id'],
        'produto_id' => $_POST['produto_id'],
        'quantidade' => $_POST['quantidade'],
        'valor' => $_POST['valor']
);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));

$curl_response = curl_exec($curl);

$data_response = json_decode($curl_response);

if($data_response->message == 'Item do pedido de venda criado com sucesso.'){
    header("Location: /pedido?pedido_de_venda_id=" . $_POST['pedido_de_venda_id']);
}else{
    include "pages/header.html";

    echo '<p>Erro ao gravar o produto no pedido. Favor informar o administrador.<p>';
    echo '<p>Erro API = ' . $data_response->message . '</p>';
    echo '<p><a href="/vendas"><button type="button" class="btn btn-success">Voltar</button></a></p>';
}

?>