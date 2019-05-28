<?php
session_start();

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch,CURLOPT_URL,'http://localhost/api/endpoints/users.php?email='. $_POST['inputEmail']. '&password='.$_POST['inputPassword']);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
$data = curl_exec($ch);
curl_close($ch);

$response = json_decode($data);
//print_r($response);

foreach ($response as $value) {
  foreach ($value as $value2) {
    if($value2->id >0){
      $_SESSION['idUsuario'] = $value2->id;
      $_SESSION['emailUsuario'] = $value2->email;
      $_SESSION['tokenUsuario'] = $value2->token;
      $_SESSION['orgUsuario'] = $value2->organizations_id;

      header("Location: /index");
    }
    else{
      //TODO: retornar para a pagina de login
    }
  }
    
}

die();



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