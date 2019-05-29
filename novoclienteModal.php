<?php
    include "validasessao.php";


echo '
<div class="modal" tabindex="-1" role="dialog" id="meuModal" >
<div class="modal-dialog" role="document">
    <form action="/inserircliente" method="post">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Novo Cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cancelar">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
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
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-success">Salvar</button>
        </div>
    </div>
  <form>
</div>
</div>';


