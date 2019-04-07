<?php
    include "pages/header.html";
    include "pages/menu.php";
?>

<?php
echo '<main role="main" class="container"> ';

echo '<form action="/inserirproduto" method="post">
        <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp" placeholder="Insira o nome do produto">
        </div>
        <div class="form-group">
        
            <label for="amount">Valor</label>
            <input type="number" class="form-control" step="any" name="amount"/>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>';

echo '</main>';
?>

<?php
    include "pages/footer.html";
?>




