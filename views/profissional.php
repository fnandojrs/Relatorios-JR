<?php

$resultado = [];
$profissionais = [];

require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../repositories/RelatorioRepository.php';

$repo = new RelatorioRepository($pdo);

// Controla o tipo baseado na URL passada pelo index.php
$tipo_relatorio = $_GET['pagina'] ?? 'profissional_vendas';

// Busca APENAS o código e nome dos profissionais para popular o <select> sem duplicar caminhos
if ($tipo_relatorio === 'total_fornecedor') {
    $stmt_prof = $pdo->query("SELECT CODPROF, NOME, TELEFONE FROM PROFISSIONAL ORDER BY NOME ASC");
    $profissionais = $stmt_prof->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $data1 = $_POST['data1'];
    $data2 = $_POST['data2'];

    if ($tipo_relatorio === 'profissional_vendas') {
        // Busca a lista tradicional com o somatório de vendas (Query 1)
        $resultado = $repo->relatorioProfissional($data1, $data2);
    } else {
        // Busca o detalhamento de pedidos do profissional selecionado (Query 2)
        $codprof = $_POST['codprof'];
        $resultado = $repo->relatorioVendasDetalhado($data1, $data2, $codprof);
    }
}

?>

<style>
@media print {
    /* Impede o navegador de repetir o tfoot em cada página */
    table tfoot {
        display: table-row-group !important;
    }
    
    /* Garante que a linha do total geral não seja cortada ao meio */
    table tfoot tr {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
    }
}
</style>

<div class="card">
    <div class="card-header">
        <h4><?= $tipo_relatorio === 'profissional_vendas' ? 'Relatório de Profissionais' : 'Relatório - Total por Fornecedor' ?></h4>
        
    </div>

    <div class="card-body">
        <form method="post">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Data Inicial</label>
                    <input type="date" name="data1" class="form-control" value="<?= $data1 ?? '' ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Data Final</label>
                    <input type="date" name="data2" class="form-control" value="<?= $data2 ?? '' ?>" required>
                </div>
                
                <?php if ($tipo_relatorio === 'total_fornecedor'): ?>
                    <div class="col-md-4">
                        <label class="form-label">Digite ou Selecione o Profissional</label>
                        
                        <?php 
                        // Recupera o texto digitado anteriormente para manter preenchido na tela
                        $nomeSelecionado = $_POST['nome_prof_busca'] ?? '';
                        ?>

                        <input 
                            type="text" 
                            name="nome_prof_busca" 
                            id="nome_prof_busca" 
                            class="form-select" 
                            list="listaProfissionais" 
                            placeholder="Digite para buscar..." 
                            value="<?= $nomeSelecionado ?>"
                            autocomplete="off"
                            required
                            onchange="vincularCodigoProf(this.value)">

                        <input type="hidden" name="codprof" id="codprof" value="<?= $codprof ?? '' ?>">

                        <datalist id="listaProfissionais">
                            <?php foreach ($profissionais as $prof): ?>
                                <option value="[<?= $prof['CODPROF'] ?>] <?= $prof['NOME'] ?>" data-codigo="<?= $prof['CODPROF'] ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <script>
                    function vincularCodigoProf(valorInput) {
                        const datalist = document.getElementById('listaProfissionais');
                        const opcoes = datalist.getElementsByTagName('option');
                        let codigoEncontrado = '';

                        for (let i = 0; i < opcoes.length; i++) {
                            if (opcoes[i].value === valorInput) {
                                codigoEncontrado = opcoes[i].getAttribute('data-codigo');
                                break;
                            }
                        }
                        document.getElementById('codprof').value = codigoEncontrado;
                    }
                    </script>
                <?php endif; ?>
            </div>
            
            <br>
            
            <button type="submit" class="btn btn-primary">Gerar Relatório</button>
            <button type="button" class="btn btn-secondary" onclick="window.print();">Imprimir</button>
            
            <?php if ($tipo_relatorio === 'profissional_vendas'): ?>
                <a href="views/exportar_profissional.php?data1=<?= $data1 ?? '' ?>&data2=<?= $data2 ?? '' ?>" class="btn btn-success">
                    Exportar Excel
                </a>
            <?php endif; ?>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($resultado)): ?>
            <hr>
            
            <div id="area-relatorio">
                <p>
                    <strong>Período:</strong> <?= date('d/m/Y', strtotime($data1)) ?> até <?= date('d/m/Y', strtotime($data2)) ?> 
                    &nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php if ($tipo_relatorio === 'total_fornecedor'): ?>
                        
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php endif; ?>
                    <strong>Emitido em:</strong> <?= date('d/m/Y H:i:s') ?>
                </p>
                <p> 
            <strong>Profissional:</strong> <?= htmlspecialchars($nomeSelecionado) ?>
                    </p>

                <?php if ($tipo_relatorio === 'profissional_vendas'): ?>
                    <table class="table table-bordered table-striped mt-3">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Profissional</th>
                                <th>Telefone</th>
                                <th>Total Vendas</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $totalGeral = 0;
                        foreach ($resultado as $linha):
                            $totalGeral += $linha['TOTAL_VENDAS'];
                        ?>
                            <tr>
                                <td><?= $linha['CODPROF'] ?></td>
                                <td><?= $linha['NOMEPROF'] ?></td>
                                <td><?= $linha['TELEFONE'] ?></td>
                                <td>
                                    R$ <?= number_format($linha['TOTAL_VENDAS'], 2, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total Geral:</th>
                                <th>R$ <?= number_format($totalGeral, 2, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>

                <?php else: ?>
                    <table class="table table-bordered table-striped mt-3 bg-white">
                        <thead>
                            <tr>
                                <th style="width: 150px;">Código Pedido</th>
                                <th>Cliente / Destinatário</th>
                                <th style="width: 200px; text-align: right;">Total Vendas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalGeralFornecedor = 0;
                            foreach ($resultado as $pedido): 
                                $totalGeralFornecedor += $pedido['TOTAL_VENDAS'];
                            ?>
                                <tr>
                                    <td><?= $pedido['CODPEDIDO'] ?></td>
                                    <td><?= !empty($pedido['NOME_CLIENTE']) ? $pedido['NOME_CLIENTE'] : 'Cliente não identificado' ?></td>
                                    <td style="text-align: right;">R$ <?= number_format($pedido['TOTAL_VENDAS'], 2, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end">Total Geral:</th>
                                <th style="text-align: right;">R$ <?= number_format($totalGeralFornecedor, 2, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                <?php endif; ?>

            </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="alert alert-warning mt-3">Nenhum registro encontrado para os parâmetros selecionados.</div>
        <?php endif; ?>
    </div>
</div>