<?php
    echo '
    <header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="#">Bullet Finanças</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">';

          if($_SERVER["REQUEST_URI"] == '/'){
            echo '<li class="nav-item active">
                    <a class="nav-link" href="/">Dashboards <span class="sr-only">(current)</span></a>
                  </li>';
          }else{
            echo '<li class="nav-item">
                    <a class="nav-link" href="/">Dashboards <span class="sr-only">(current)</span></a>
                  </li>';
          }
          
          if($_SERVER["REQUEST_URI"] == '/vendas'){
            echo '<li class="nav-item active">
                    <a class="nav-link" href="vendas">Vendas</a>
                  </li>';
          }else{
            echo '<li class="nav-item">
                    <a class="nav-link" href="vendas">Vendas</a>
                  </li>';
          }
            
          if($_SERVER["REQUEST_URI"] == '/clientes' || $_SERVER["REQUEST_URI"] == '/novocliente'){
            echo '<li class="nav-item active">
                    <a class="nav-link" href="/clientes">Clientes</a>
                  </li>';
          }else{
            echo '<li class="nav-item">
                    <a class="nav-link" href="/clientes">Clientes</a>
                  </li>';
          }

          if($_SERVER["REQUEST_URI"] == '/produtos' || $_SERVER["REQUEST_URI"] == '/novoproduto'){
            echo '<li class="nav-item active">
                    <a class="nav-link" href="/produtos">Produtos</a>
                  </li>';
          }else{
            echo '<li class="nav-item">
                    <a class="nav-link" href="/produtos">Produtos</a>
                  </li>';
          }
          
          echo '
          </ul>
        </div>
      </nav>
      </header>';
?>