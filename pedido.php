<?php
    include "validasessao.php";
    include "pages/header.html";
    include "pages/menu.php";
?>

<?php
echo '<main role="main" class="container"> ';

// button new customer

echo '<p><button type="button" class="btn btn-success" data-toggle="modal" data-target="#meuModal">Novo Produto</button></p>';

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




include "pages/header-products_has_order.html";

$response = file_get_contents('http://localhost/api/endpoints/itensPedidoDeVenda.php?token='. $_SESSION['tokenUsuario'] . '&organization=' . $_SESSION['orgUsuario'] . '&pedido_de_venda_id=' . $_GET['pedido_de_venda_id']);

$response = json_decode($response);

echo '<tbody>'; 

if($response->message == 'No products_has_order found.'){
    echo '<tr><td colspan="5"> Nenhum produto encontrado na venda.</td</tr>';
}else{
    $count = 0;
    foreach ($response as $value) {
        foreach ($value as $key => $value2) {
            foreach ($value2 as $key => $value3) {
                $count++;
                echo '
                    <tr>
                        <th scope="row">' . $count . '</th>
                        <td>' . $value3->produto_id . ' - ' . $value3->produto_nome . ' </td>
                        <td>' . $value3->quantidade . ' </td>
                        <td>R$ ' . number_format($value3->valor, 2)  . '</td>
                        <td><i class="fas fa-edit"></i></td>
                    </tr>';        
            }   
        }
    }    
}

echo '</tbody> 
</table>';

echo '<div class="container">
        <div class="row">
            <div class="col-sm">
                <div class="alert alert-success" align="center" role="alert">Total Pedido: R$ ' . number_format($totalPedido, 2)  . '</div>
            </div>
        </div>
    </div>';


echo '<p><a href="/vendas"><button type="button" class="btn btn-success">Salvar</button></a></p>';

echo '</main>';

    include "novoprodutodopedidoModal.php";

    include "pages/footer.html";
?>
