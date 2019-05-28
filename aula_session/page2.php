<?php

    session_start();

    echo 'Bem vindo à página 2<br>';

    echo $_SESSION['favcolor'];
    echo $_SESSION['animal'];
    echo date('Y m d H:i:s',$_SESSION['time']);

    echo '<br><a href="page1.php">page 1</a>';

?>