<?php
include "pages/header.html";
echo '<main role="main" class="container"> ';
?>

  <body class="text-center">
    <form class="form-signin" action="/realizalogin.php" method="post">
      <img class="mb-4" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
      <label for="inputEmail" class="sr-only">E-mail</label>
      <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="E-mail" required autofocus>
      <label for="inputPassword" class="sr-only">Senha</label>
      <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Senha" required>
      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Continuar conectado
        </label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
    </form>
  </body>

<?php 

    echo '</main>';
?>

<?php
    include "pages/footer.html";
?>
