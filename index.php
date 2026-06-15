<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Relatórios</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6f9;
}

.menu-lateral{
    min-height:100vh;
    background:#fff;
    border-right:1px solid #ddd;
}

.menu-lateral .nav-link{
    color:#333;
    border-radius:5px;
}

.menu-lateral .nav-link.active{
    background:#0d6efd;
    color:#fff;
}

.conteudo{
    padding:20px;
}
</style>
<style>

@media print {

    .menu-lateral {
        display: none !important;
    }

    form {
        display: none !important;
    }

    .card-header {
        display: none !important;
    }

    .conteudo {
        width: 100% !important;
    }

}

</style>

</head>
<body>

<div class="container-fluid">

    <div class="row">

        <div class="col-md-2 menu-lateral p-3">

            <h4>Relatórios</h4>

            <hr>

            <div class="nav flex-column">

                <a href="?pagina=profissional"
                   class="nav-link <?= (!isset($_GET['pagina']) || $_GET['pagina']=='profissional') ? 'active' : '' ?>">
                    Profissional
                </a>

                <!-- <a href="?pagina=clientes"
                   class="nav-link">
                    Clientes
                </a>

                <a href="?pagina=produtos"
                   class="nav-link">
                    Produtos
                </a>

                <a href="?pagina=financeiro"
                   class="nav-link">
                    Financeiro
                </a> -->

            </div>

        </div>

        <div class="col-md-10 conteudo">

            <?php

            $pagina = $_GET['pagina'] ?? 'profissional';

            switch($pagina){

                case 'profissional':
                    include 'views/profissional.php';
                    break;

                case 'clientes':
                    echo "<h3>Relatório de Clientes</h3>";
                    break;

                case 'produtos':
                    echo "<h3>Relatório de Produtos</h3>";
                    break;

                case 'financeiro':
                    echo "<h3>Relatório Financeiro</h3>";
                    break;

                default:
                    include 'views/profissional.php';
            }

            ?>

        </div>

    </div>

</div>

</body>
</html>