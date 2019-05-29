<?php
    include "validasessao.php";
    include "pages/header.html";
    include "pages/menu.php";

?>
<?php
    
    echo $_SESSION["idUsuario"];
    echo '<br>';
    echo $_SESSION['emailUsuario'];
    echo '<br>';
    echo $_SESSION['tokenUsuario'];
    echo '<br>';
    echo $_SESSION['orgUsuario'];
    echo '<br>';

    include "pages/footer.html";
?>