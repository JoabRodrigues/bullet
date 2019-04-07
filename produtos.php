<?php
    include "pages/header.html";
    include "pages/menu.php";
?>

<?php
echo '<main role="main" class="container"> ';

// button new customer

echo '<p><a href="/novoproduto"><button type="button" class="btn btn-success">Novo Produto</button></a></p>';

include "pages/header-products.html";


$response = file_get_contents('http://localhost/api/endpoints/products.php');

$response = json_decode($response);

echo '<tbody>'; 

foreach ($response as $value) {
    foreach ($value as $key => $value2) {
    echo '
        <tr>
        <th scope="row">' . $value2->id . '</th>
            <td>' . $value2->name . ' </td>
            <td>' . $value2->amount . ' </td>
            <td>' . $value2->status . ' </td>
            <td><i class="fas fa-edit"></i></td>
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




