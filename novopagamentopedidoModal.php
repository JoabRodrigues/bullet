<?php
    include "validasessao.php";

    echo '
<div class="modal" tabindex="-1" role="dialog" id="meuModal" >
<div class="modal-dialog" role="document">
    <form action="/inserirpagamentopedido" method="post">
    <input type="hidden" id="pedido_de_venda_id" name="pedido_de_venda_id" value="' . $_GET['pedido_de_venda_id'] . '">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Novo Pagamento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cancelar">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="tipo_pagamento">Tipo Pagamento</label>
                <select name="tipo_pagamento" id="tipo_pagamento" class="form-control">
                    <option value="dinheiro">Dinheiro</option>
                    <option value="cartao_credito">Cartão de Crédito</option>
                    <option value="boleto">Boleto</option>
                </select>
            </div>
            <div class="form-group">
                <label for="valor_total">Valor Total</label>
                <input type="number" class="form-control" step="any" id="valor_total" name="valor_total"/>
            </div>
            <div class="form-group">
                <label for="numero_parcelas">Número de Parcelas</label>
                <input type="number" class="form-control" step="any" id="numero_parcelas" name="numero_parcelas"/>
            </div>
            <div class="form-group">
                <label for="data_vencimento">Data Vencimento</label>
                <div >
                    <input class="form-control" type="date" value="" id="data_vencimento" name="data_vencimento">
                </div>
            </div>
            <div class="form-group">
                <label for="bandeira">Bandeira</label>
                <select name="bandeira" id="bandeira" class="form-control">
                    <option value="visa">Visa</option>
                    <option value="master">Master</option>
                </select>
            </div>
            <div class="form-group">
                <label for="ultimos_digitos_cartao">Últimos 4 Dígitos do Cartão</label>
                <input type="number" class="form-control" step="any" id="ultimos_digitos_cartao" name="ultimos_digitos_cartao"/>
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

