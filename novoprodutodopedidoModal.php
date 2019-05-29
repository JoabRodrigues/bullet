<?php
    include "validasessao.php";


    $response = file_get_contents('http://localhost/api/endpoints/produtos.php?token='. $_SESSION['tokenUsuario'] . '&organization=' . $_SESSION['orgUsuario']);

    $response = json_decode($response);


    echo '
<div class="modal" tabindex="-1" role="dialog" id="meuModal" >
<div class="modal-dialog" role="document">
    <form action="/inserirprodutodopedido" method="post">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Novo Produto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Cancelar">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="pedido_de_venda_id" value="' . $_GET['pedido_de_venda_id']. '"/>
                <div class="form-group">
                    <label for="produto_id">Produto</label>
                    <select class="form-control" id="produto_id" name="produto_id">';
                    foreach ($response as $value) {
                        foreach ($value as $key => $value2) {
                            asort($value2);
                            foreach ($value2 as $key => $value3) {
                                echo '<option value="' . $value3->id . '">' . $value3->id . ' - ' . $value3->nome . '</option>';        
                            }  
                        } 
                    }
                    echo '</select>
                </div>
                <div class="form-group">
                    <label for="quantidade">Quantidade</label>
                    <input type="number" class="form-control" step="any" name="quantidade"/>
                </div>
                <div class="form-group">
                    <label for="valor">Valor</label>
                    <input type="number" class="form-control" step="any" name="valor"/>
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




