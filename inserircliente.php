<?php
include "validasessao.php";

$service_url = 'http://localhost/api/endpoints/clientes.php?token='  . $_SESSION['tokenUsuario'] . '&organization=' . $_SESSION['orgUsuario'];
$curl = curl_init($service_url);

$curl_post_data = array(
        'name' => $_POST['name'],
        'type' => $_POST['type'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone']
);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
$curl_response = curl_exec($curl);

$data_response = json_decode($curl_response);

if($data_response->message == 'Cliente criado com sucesso.'){
    header("Location: /clientes");
}else{
    include "pages/header.html";

    echo '<p>Erro ao criar o cliente. Favor informar o administrador.<p>';
    echo '<p>Erro API' . $data_response->message . '</p>';
    echo '<p><a href="/clientes"><button type="button" class="btn btn-success">Voltar</button></a></p>';
}

?>