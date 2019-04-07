<?php
    include "pages/header.html";
    include "pages/menu.php";
?>

<?php
echo '<main role="main" class="container"> ';

// button new customer

echo '<p><a href="/novocliente"><button type="button" class="btn btn-success">Novo Cliente</button></a></p>';

include "pages/header-customers.html";


$response = file_get_contents('http://localhost/api/endpoints/customers.php');

$response = json_decode($response);

echo '<tbody>'; 

foreach ($response as $value) {
    foreach ($value as $key => $value2) {
    echo '
        <tr>
        <th scope="row">' . $value2->id . '</th>
            <td>' . $value2->name . ' </td>
            <td>' . $value2->type . ' </td>
            <td>' . $value2->email . '</td>
            <td>' . $value2->phone . ' </td>
            <td>' . $value2->status . ' </td>
            <td><i class="fas fa-user-edit"></i></td>
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




