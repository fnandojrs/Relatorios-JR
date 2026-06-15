<?php

$resultado = [];

require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../repositories/RelatorioRepository.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $data1 = $_POST['data1'];
    $data2 = $_POST['data2'];

    $repo = new RelatorioRepository($pdo);

    $resultado = $repo->relatorioProfissional(
        $data1,
        $data2
    );
}

?>

<div class="card">

    <div class="card-header">
        <h4>Relatório de Profissionais</h4>
    </div>

    <div class="card-body">

        <form method="post">

            <div class="row">

                <div class="col-md-3">
                    <label>Data Inicial</label>
                    <input
                        type="date"
                        name="data1"
                        class="form-control"
                        required>
                </div>

                <div class="col-md-3">
                    <label>Data Final</label>
                    <input
                        type="date"
                        name="data2"
                        class="form-control"
                        required>
                </div>

            </div>

            <br>

            <button
                type="submit"
                class="btn btn-primary">
                Gerar Relatório
            </button>

            <button
                type="button"
                class="btn btn-secondary"
                onclick="window.print();">
                Imprimir
            </button>
            <a href="views/exportar_profissional.php?data1=<?= $data1 ?>&data2=<?= $data2 ?>"
                class="btn btn-success">
                Exportar Excel
            </a>

        </form>

        <?php if (!empty($resultado)): ?>

            <hr>
            <p>
                Relatorio de Profissionais
        </p>
            <div id="area-relatorio">
                <p>
                    <strong>Período:</strong>
                    <?= date('d/m/Y', strtotime($data1)) ?> até <?= date('d/m/Y', strtotime($data2)) ?>  <strong>Emitido em:</strong> <?= date('d/m/Y H:i:s') ?>
                </p>


            <table class="table table-bordered table-striped">

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
                        <th colspan="3" class="text-end">
                            Total Geral:
                        </th>
                        <th>
                            R$ <?= number_format($totalGeral, 2, ',', '.') ?>
                        </th>
                    </tr>
                </tfoot>

            </table>
            </div>

        <?php endif; ?>

    </div>

</div>