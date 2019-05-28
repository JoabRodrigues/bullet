<?php
    include "validasessao.php";
    include "pages/header.html";
    include "pages/menu.php";
?>

<?php
echo '<main role="main" class="container"> ';

// button new customer

echo '<p><a href="/novopedido"><button type="button" class="btn btn-success">Novo Pedido</button></a></p>';

include "pages/header-orders.html";


$response = file_get_contents('http://localhost/api/endpoints/pedidosDeVenda.php?token='. $_SESSION['tokenUsuario'] . '&organization=' . $_SESSION['orgUsuario']);

$response = json_decode($response);

echo '<tbody>'; 

foreach ($response as $value) {
    foreach ($value as $key => $value2) {
        foreach ($value2 as $key3 => $value3) {
            echo '
                <tr>
                    <th scope="row">' . $value3->id . '</th>
                    <td>' . date("d/m/Y", strtotime($value3->data_criacao)) . ' </td>
                    <td>' . $value3->cliente_id . ' - ' . ucwords(strtolower($value3->cliente_nome)) . '</td>
                    <td> R$ ' . number_format($value3->valor_total, 2) . ' </td>
                    <td>' . $value3->status . '</td>
                    <td>
                        <a href="/pedido?orders_id=' . $value3->id . '"><i class="fas fa-edit"></i></a>
                    </td>
            </tr>';        
        }
    }
}

echo '</tbody> 
</table>';

echo '</main>';
?>

<?php
    include "pages/footer.html";
?>