<?php
    include "validasessao.php";
    include "pages/header.html";
    include "pages/menu.php";

    echo '<main role="main" class="container"> ';

// button new customer

//echo '<p><a href="/novoproduto"><button type="button" class="btn btn-success">Novo Produto</button></a></p>';
echo '<p><button type="button" class="btn btn-success" data-toggle="modal" data-target="#meuModal">Novo Produto</button></p>';

include "pages/header-products.html";


$response = file_get_contents('http://localhost/api/endpoints/produtos.php?token=47fc57393e93ef93f3653a1394ea4f57&organization=1');

$response = json_decode($response);

echo '<tbody>'; 

foreach ($response as $value) {
    foreach ($value as $key => $value2) {
        foreach ($value2 as $key3 => $value3) {
            echo '
            <tr>
                <th scope="row">' . $value3->id . '</th>
                <td>' . $value3->nome . ' </td>
                <td>' . $value3->valor . ' </td>
                <td>' . $value3->status . ' </td>
                <td><i class="fas fa-edit"></i></td>
            </tr>';   
        }
    }
}

echo '</tbody> 
</table>';

echo '</main>';

include "novoprodutoModal.php";

include "pages/footer.html";
?>




