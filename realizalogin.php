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

?>