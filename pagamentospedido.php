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


$response = file_get_contents('http://localhost/api/endpoints/pagamentosPedidoDeVenda.php?token='. $_SESSION['tokenUsuario'] . '&organization=' . $_SESSION['orgUsuario'] . '&pedido_de_venda_id=' . $_GET['pedido_de_venda_id']);

$response = json_decode($response);

echo '<tbody>'; 

if($response->message == 'Nenhum pagamento encontrado.'){
    echo '<tr><td colspan="5"> Nenhum pagamento encontrado na venda.</td</tr>';
}else{
    $count = 0;
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
            }   
        }
    }    
}

echo '</tbody> 
</table>';

echo '<p><a href="/vendas"><button type="button" class="btn btn-success">Salvar</button></a></p>';

echo '</main>';

    include "novoprodutodopedidoModal.php";

    include "pages/footer.html";
?>
