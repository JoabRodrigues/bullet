<?php

$service_url = 'http://localhost/api/endpoints/pagamentosPedidoDeVenda.php?token=47fc57393e93ef93f3653a1394ea4f57&organization=1';
$curl = curl_init($service_url);

$curl_post_data = array(
        'valor_total' => $_POST['valor_total'],
        'numero_parcelas' => $_POST['numero_parcelas'],
        'data_vencimento' => $_POST['data_vencimento'],
        'tipo_pagamento' => $_POST['tipo_pagamento'],
        'bandeira' => $_POST['bandeira'],
        'ultimos_digitos_cartao' => $_POST['ultimos_digitos_cartao'],
        'pedido_de_venda_id' => $_POST['pedido_de_venda_id']
);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
$curl_response = curl_exec($curl);

$data_response = json_decode($curl_response);

if($data_response->message == 'Pagamento do pedido de venda criado com sucesso.'){
    header("Location: /pagamentospedido?pedido_de_venda_id=" . $_POST['pedido_de_venda_id']);
}else{
    include "pages/header.html";

    echo '<p>Erro ao criar o pagamento. Favor informar o administrador.<p>';
    echo '<p>Erro API' . $data_response->message . '</p>';
    echo '<p><a href="/pagamentospedido?pedido_de_venda_id=' . $_POST['pedido_de_venda_id'] . '"><button type="button" class="btn btn-success">Voltar</button></a></p>';
}
?>