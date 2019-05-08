<?php

$service_url = 'http://localhost/api/endpoints/products_has_order.php?token=47fc57393e93ef93f3653a1394ea4f57&organization=1';
$curl = curl_init($service_url);

$curl_post_data = array(
        'orders_id' => $_POST['orders_id'],
        'products_id' => $_POST['products_id'],
        'quantity' => $_POST['quantity'],
        'amount' => $_POST['amount']
);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));

$curl_response = curl_exec($curl);

$data_response = json_decode($curl_response);

if($data_response->message == 'products_has_order was created.'){
    header("Location: /pedido?orders_id=" . $_POST['orders_id']);
}else{
    include "pages/header.html";

    echo '<p>Erro ao gravar o produto no pedido. Favor informar o administrador.<p>';
    echo '<p>Erro API' . $data_response->message . '</p>';
    echo '<p><a href="/vendas"><button type="button" class="btn btn-success">Voltar</button></a></p>';
}

?>