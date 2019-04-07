<?php
    include "pages/header.html";
    include "pages/menu.php";
?>

<?php
echo '<main role="main" class="container"> ';

echo '<form action="/inserircliente" method="post">
        <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp" placeholder="Insira o nome do cliente">
        </div>
        <div class="form-group">
            <label for="type">Tipo</label>
            <select class="form-control" id="type" name="type">
            <option value="1">Pessoa Física</option>
            <option value="2">Pessoa Jurídica</option>
            </select>
        </div>
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="nome@exemplo.com">
        </div>
        <div class="form-group">
            <label for="phone">Telefone</label>
            <input type="text" class="form-control" id="phone" name="phone">
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>';

echo '</main>';
?>

<?php
    include "pages/footer.html";
?>




