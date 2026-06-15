<?php

require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../repositories/RelatorioRepository.php';

$data1 = $_GET['data1'];
$data2 = $_GET['data2'];

$repo = new RelatorioRepository($pdo);

$resultado = $repo->relatorioProfissional(
    $data1,
    $data2
);

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=relatorio_profissionais.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo '
<style>
    table {
        border-collapse: collapse;
    }

    th {
        background: #D9D9D9;
        font-weight: bold;
    }

    th, td {
        border: 1px solid #000;
        padding: 5px;
    }

    .codigo {
        width: 80px;
    }

    .profissional {
        width: 500px;
    }

    .telefone {
        width: 150px;
    }

    .valor {
        width: 120px;
        text-align: right;
    }
</style>
';

echo '<table>';

echo '
<tr>
    <th class="codigo">Codigo</th>
    <th class="profissional">Profissional</th>
    <th class="telefone">Telefone</th>
    <th class="valor">Total Vendas</th>
</tr>';

$totalGeral = 0;

foreach ($resultado as $linha)
{
    $totalGeral += $linha['TOTAL_VENDAS'];

    echo '
    <tr>
        <td>'.$linha['CODPROF'].'</td>
        <td>'.$linha['NOMEPROF'].'</td>
        <td>'.$linha['TELEFONE'].'</td>
        <td class="valor">'.number_format($linha['TOTAL_VENDAS'], 2, ',', '.').'</td>
    </tr>';
}

echo '
<tr>
    <td colspan="3" style="font-weight:bold; text-align:right;">
        Total Geral:
    </td>
    <td class="valor" style="font-weight:bold;">
        '.number_format($totalGeral, 2, ',', '.').'
    </td>
</tr>';

echo '</table>';

exit;