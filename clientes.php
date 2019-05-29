<?php
    include "validasessao.php";
    include "pages/header.html";
    include "pages/menu.php";
?>

<?php
echo '<main role="main" class="container"> ';

// button new customer

echo '<p><button type="button" class="btn btn-success" data-toggle="modal" data-target="#meuModal">Novo Cliente</button></p>';

include "pages/header-clientes.html";


$response = file_get_contents('http://localhost/api/endpoints/clientes.php?token=' . $_SESSION['tokenUsuario'] . '&organization=' . $_SESSION['orgUsuario']);

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

include "novoclienteModal.php";

include "pages/footer.html";
?>




