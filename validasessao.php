<?php
  session_start();

  if(is_null($_SESSION['tokenUsuario'])){
      header("Location: /login");
  }

?>