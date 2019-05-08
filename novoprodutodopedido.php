<?php
    include "pages/header.html";
    include "pages/menu.php";
?>

<?php
echo '<main role="main" class="container"> ';



$response = file_get_contents('http://localhost/api/endpoints/products.php?token=47fc57393e93ef93f3653a1394ea4f57&organization=1');

$response = json_decode($response);


echo '<form action="/inserirprodutodopedido" method="post">
        <input type="hidden" name="orders_id" value="' . $_GET['orders_id']. '"/>
        <div class="form-group">
            <label for="products_id">Produto</label>
            <select class="form-control" id="products_id" name="products_id">';
            foreach ($response as $value) {
                asort($value);
                foreach ($value as $key => $value2) {
                    echo '<option value="' . $value2->id . '">' . $value2->id . ' - ' . $value2->name . '</option>';        
                }
            }
            echo '</select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantidade</label>
            <input type="number" class="form-control" step="any" name="quantity"/>
        </div>
        <div class="form-group">
            <label for="amount">Valor</label>
            <input type="number" class="form-control" step="any" name="amount"/>
        </div>
        <button type="submit" class="btn btn-primary">Continuar</button>
    </form>';

echo '</main>';
?>

<?php
    include "pages/footer.html";
?>




