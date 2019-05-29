<?php
    include "validasessao.php";

    echo '
<div class="modal" tabindex="-1" role="dialog" id="meuModal" >
<div class="modal-dialog" role="document">
    <form action="/inserirproduto" method="post">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Novo Produto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cancelar">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" aria-describedby="nameHelp" placeholder="Insira o nome do produto">
            </div>
            <div class="form-group">
                <label for="valor">Valor</label>
                <input type="number" class="form-control" step="any" id="valor" name="valor"/>
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




