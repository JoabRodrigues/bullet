<?php
    session_start();

    echo "Bem vindo à página 1";

    $_SESSION['favcolor'] = 'verde';
    $_SESSION['animal'] = 'gato';
    $_SESSION['time'] = time();

    echo '<br><a href="page2.php">page 2</a>';

    echo '<br><a href="page2.php?' . session_id() . '">page 2</a>';

?>