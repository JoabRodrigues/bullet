<?php
    include "validasessao.php";
    $_SESSION['idUsuario'] = null;
    $_SESSION['emailUsuario'] = null;
    $_SESSION['tokenUsuario'] = null;
    $_SESSION['orgUsuario'] = null;

    header("Location: /index");
?>