<?php

$service_url = 'http://localhost/api/endpoints/orders.php?token=47fc57393e93ef93f3653a1394ea4f57&organization=1';
$curl = curl_init($service_url);

$curl_post_data = array(
        'amount' => 0,
        'customers_id' => $_POST['customers_id']
);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));

$curl_response = curl_exec($curl);

$data_response = json_decode($curl_response);

if($data_response->message == 'Order was created.'){
    //envia para a pagina do pedido com o id no get
    header("Location: /pedido?orders_id=" . $data_response->id);
}else{
    include "pages/header.html";

    echo '<p>Erro ao criar o pedido. Favor informar o administrador.<p>';
    echo '<p>Erro API' . $data_response->message . '</p>';
    echo '<p><a href="/vendas"><button type="button" class="btn btn-success">Voltar</button></a></p>';
}

?>