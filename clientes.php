<?php
    include "pages/header.html";
    include "pages/menu.php";
?>

<?php
echo '<main role="main" class="container"> ';

// button new customer

echo '<p><a href="/novocliente"><button type="button" class="btn btn-success">Novo Cliente</button></a></p>';

include "pages/header-clientes.html";


$response = file_get_contents('http://localhost/api/endpoints/clientes.php?token=47fc57393e93ef93f3653a1394ea4f57&organization=1');

$response = json_decode($response);

echo '<tbody>'; 

foreach ($response as $value) {
    foreach ($value as $key => $value2) {
        foreach ($value2 as $key3 => $value3) {
            echo '
            <tr>
            <th scope="row">' . $value3->id . '</th>
                <td>' . $value3->nome . ' </td>
                <td>' . $value3->tipo . ' </td>
                <td>' . $value3->email . '</td>
                <td>' . $value3->telefone . ' </td>
                <td>' . $value3->status . ' </td>
                <td><i class="fas fa-user-edit"></i></td>
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




