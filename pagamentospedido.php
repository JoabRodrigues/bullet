<?php
    include "validasessao.php";
    include "pages/header.html";
    include "pages/menu.php";
?>

<?php
echo '<main role="main" class="container"> ';

// button new customer

echo '<p><button type="button" class="btn btn-success" data-toggle="modal" data-target="#meuModal">Novo Pagamento</button></p>';

include "pages/header-pagamentos-pedido-de-venda.html";

$response = file_get_contents('http://localhost/api/endpoints/pedidosDeVenda.php?token='. $_SESSION['tokenUsuario'] . '&organization=' . $_SESSION['orgUsuario'] . '&id='. $_GET['pedido_de_venda_id']);

$response = json_decode($response);

$totalPedido = 0;
foreach ($response as $value) {
    foreach ($value as $key => $value2) {
        foreach ($value2 as $key3 => $value3) {
            $totalPedido = $value3->valor_total;
        }
    }
}


$response = file_get_contents('http://localhost/api/endpoints/pagamentosPedidoDeVenda.php?token='. $_SESSION['tokenUsuario'] . '&organization=' . $_SESSION['orgUsuario'] . '&pedido_de_venda_id=' . $_GET['pedido_de_venda_id']);

$response = json_decode($response);

echo '<tbody>'; 

if($response->message == 'Nenhum pagamento encontrado.'){
    echo '<tr><td colspan="5"> Nenhum pagamento encontrado na venda.</td</tr>';
}else{
    $count = 0;
    $totalPagamentos = 0;
    foreach ($response as $value) {
        foreach ($value as $key => $value2) {
            foreach ($value2 as $key => $value3) {
                $count++;
                echo '
                    <tr>
                        <th scope="row">' . $count . '</th>
                        <td>' . $value3->tipo_pagamento . ' </td>
                        <td>' . $value3->data_vencimento . ' </td>
                        <td>' . $value3->numero_parcelas . ' </td>
                        <td>R$ ' . number_format($value3->valor_total, 2)  . '</td>
                        <td>' . $value3->bandeira . ' </td>
                        <td>' . $value3->ultimos_digitos_cartao . ' </td>
                        <td><i class="fas fa-edit"></i></td>
                    </tr>';        
                    $totalPagamentos += $value3->valor_total;
            }   
        }
    }    
}

echo '</tbody> 
</table>';
echo '<div class="container">
        <div class="row">
            <div class="col-sm">
                <div class="alert alert-success" role="alert">Total Pedido: R$ ' . number_format($totalPedido, 2)  . '</div>
            </div>
            <div class="col-sm">
                ';
            if($totalPagamentos != $totalPedido){
                echo '<div class="alert alert-danger" role="alert">Total Pagamentos: R$ ' . number_format($totalPagamentos, 2)  . '</div>';
            }else{
                echo '<div class="alert alert-success" role="alert">Total Pagamentos: R$ ' . number_format($totalPagamentos, 2)  . '</div>';
            }
            echo '</div>
                </div>
            </div>';

echo '<p><a href="/vendas"><button type="button" class="btn btn-success">Salvar</button></a></p>';

echo '</main>';

    include "novopagamentopedidoModal.php";

    include "pages/footer.html";
?>
