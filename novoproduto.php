<?php
    include "pages/header.html";
    include "pages/menu.php";
?>

<?php
echo '<main role="main" class="container"> ';

echo '<form action="/inserirproduto" method="post">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" aria-describedby="nameHelp" placeholder="Insira o nome do produto">
        </div>
        <div class="form-group">
        
            <label for="valor">Valor</label>
            <input type="number" class="form-control" step="any" id="valor" name="valor"/>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>';

echo '</main>';
?>

<?php
    include "pages/footer.html";
?>




