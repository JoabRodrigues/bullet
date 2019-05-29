<?php
include "validasessao.php";

$response = file_get_contents('http://localhost/api/endpoints/clientes.php?token='. $_SESSION['tokenUsuario'] . '&organization=' . $_SESSION['orgUsuario']);

$response = json_decode($response);

echo '
<div class="modal" tabindex="-1" role="dialog" id="meuModal" >
<div class="modal-dialog" role="document">
    <form action="/inserirpedido" method="post"
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Novo Produto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Cancelar">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="customers_id">Cliente</label>
                    <select class="form-control" id="customers_id" name="customers_id">';
                    foreach ($response as $value) {
                        asort($value);
                        foreach ($value as $key => $value2) {
                            foreach ($value2 as $key3 => $value3) {
                                echo '<option value="' . $value3->id . '">' . $value3->id . ' - ' . $value3->nome . '</option>';        
                            }
                        }
                    }
                    echo '</select>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        </div>
    <form>
</div>
</div>';

?>




