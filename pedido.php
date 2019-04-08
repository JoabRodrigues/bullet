<?php
    include "pages/header.html";
    include "pages/menu.php";
?>

<?php
echo '<main role="main" class="container"> ';

// button new customer

echo '<p><a href="/novoprodutodopedido?orders_id=' . $_GET['orders_id'] . '"><button type="button" class="btn btn-success">Novo Produto</button></a></p>';

include "pages/header-products_has_order.html";


$response = file_get_contents('http://localhost/api/endpoints/products_has_order.php?orders_id=' . $_GET['orders_id']);

$response = json_decode($response);

echo '<tbody>'; 

if($response->message == 'No products_has_order found.'){
    echo '<tr><td colspan="5"> Nenhum produto encontrado na venda.</td</tr>';
}else{
    $count = 0;
    foreach ($response as $value) {
        foreach ($value as $key => $value2) {
            $count++;
        echo '
            <tr>
            <th scope="row">' . $count . '</th>
                <td>' . $value2->products_id . ' - ' . $value2->products_name . ' </td>
                <td>' . $value2->quantity . ' </td>
                <td>R$ ' . number_format($value2->amount, 2)  . '</td>
                <td><i class="fas fa-edit"></i></td>
                </tr>';        
        }
    }    
}

echo '</tbody> 
</table>';

echo '<p><a href="/vendas"><button type="button" class="btn btn-success">Salvar</button></a></p>';

echo '</main>';
?>

<?php
    include "pages/footer.html";
?>
