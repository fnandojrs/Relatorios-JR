<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Relatórios</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

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
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.menu-lateral .nav-link.active{
    background:#0d6efd;
    color:#fff;
}

.submenu .nav-link {
    padding-left: 25px;
    font-size: 0.95rem;
    background: transparent !important;
    color: #555;
}

.submenu .nav-link.active-sub {
    color: #0d6efd;
    font-weight: bold;
}

.conteudo{
    padding:20px;
}
</style>
<style>

@media print {
    .menu-lateral, form, .card-header {
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

                <?php 
                // Pega a página atual da URL. Se não tiver nada, assume 'inicio'
                $paginaAtual = $_GET['pagina'] ?? 'inicio'; 
                
                // O menu principal fica ativo (azul) e aberto se for a tela de início OU qualquer um dos subgrupos
                $grupoProfissionalAberto = ($paginaAtual == 'inicio' || $paginaAtual == 'profissional_vendas' || $paginaAtual == 'total_fornecedor');
                ?>

                <a class="nav-link <?= $grupoProfissionalAberto ? 'active' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#menuProfissional" 
                   role="button" 
                   aria-expanded="<?= $grupoProfissionalAberto ? 'true' : 'false' ?>" 
                   aria-controls="menuProfissional">
                    <span>Profissional</span>
                    <i class="bi bi-chevron-down small"></i>
                </a>

                <div class="collapse <?= $grupoProfissionalAberto ? 'show' : '' ?> submenu" id="menuProfissional">
                    <div class="nav flex-column ms-2">
                        
                        <a href="?pagina=profissional_vendas"
                           class="nav-link <?= ($paginaAtual == 'profissional_vendas') ? 'active-sub' : '' ?>">
                            <i class="bi bi-cart3 me-2"></i> Vendas
                        </a>

                        <a href="?pagina=total_fornecedor"
                           class="nav-link <?= ($paginaAtual == 'total_fornecedor') ? 'active-sub' : '' ?>">
                            <i class="bi bi-tags me-2"></i> Total por Fornecedor
                        </a>

                    </div>
                </div>

            </div>

        </div>

        <div class="col-md-10 conteudo">

            <?php

            switch($paginaAtual){

                case 'inicio':
                    echo "
                    <div class='text-center text-muted my-5'>
                        <i class='bi bi-arrow-left-circle' style='font-size: 3rem;'></i>
                        <h4 class='mt-3'>Bem-vindo ao Sistema de Relatórios</h4>
                        <p>Selecione uma opção no menu lateral para começar.</p>
                    </div>";
                    break;

                case 'profissional_vendas':
                    include 'views/profissional.php';
                    break;

                case 'total_fornecedor':
                    include 'views/profissional.php';
                    break;

                default:
                    echo "<h3>Selecione um relatório válido.</h3>";
            }

            ?>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>