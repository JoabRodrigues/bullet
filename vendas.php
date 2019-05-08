<?php
    include "pages/header.html";
    include "pages/menu.php";
?>

<?php
echo '<main role="main" class="container"> ';

// button new customer

echo '<p><a href="/novopedido"><button type="button" class="btn btn-success">Novo Pedido</button></a></p>';

include "pages/header-orders.html";


$response = file_get_contents('http://localhost/api/endpoints/orders.php?token=47fc57393e93ef93f3653a1394ea4f57&organization=1');

$response = json_decode($response);

echo '<tbody>'; 

foreach ($response as $value) {
    foreach ($value as $key => $value2) {
    echo '
        <tr>
        <th scope="row">' . $value2->id . '</th>
            <td>' . date("d/m/Y", strtotime($value2->created)) . ' </td>
            <td>' . $value2->customers_id . ' - ' . ucwords(strtolower($value2->customers_name)) . '</td>
            <td> R$ ' . number_format($value2->amount, 2) . ' </td>
            <td>' . $value2->status . '</td>
            <td>
                <a href="/pedido?orders_id=' . $value2->id . '"><i class="fas fa-edit"></i></a>
            </td>
            </tr>';        
    }
}
echo '</tbody> 
</table>';

echo '</main>';
?>

<?php
    include "pages/footer.html";
?>