<?php
include "pages/header.html";
include "pages/menu.php";

echo '<main role="main" class="container"> ';

$response = file_get_contents('http://localhost/api/endpoints/customers.php?token=47fc57393e93ef93f3653a1394ea4f57&organization=1');

$response = json_decode($response);

echo '<form action="/inserirpedido" method="post">
        <div class="form-group">
            <label for="customers_id">Cliente</label>
            <select class="form-control" id="customers_id" name="customers_id">';
            foreach ($response as $value) {
                asort($value);
                foreach ($value as $key => $value2) {
                    echo '<option value="' . $value2->id . '">' . $value2->id . ' - ' . $value2->name . '</option>';        
                }
            }
            echo '</select>
        </div>
        <button type="submit" class="btn btn-primary">Continuar</button>
    </form>';

echo '</main>';
?>

<?php
    include "pages/footer.html";
?>




