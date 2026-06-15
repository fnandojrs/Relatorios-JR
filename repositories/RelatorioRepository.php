<?php

class RelatorioRepository
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function relatorioProfissional($data1, $data2)
    {
        $sql = "
        SELECT
            PROFISSIONAL.CODPROF,
            PROFISSIONAL.NOME AS NOMEPROF,
            SUM(PEDIDOC.TOTALPEDIDO) AS TOTAL_VENDAS,
            PROFISSIONAL.TELEFONE
        FROM PROFISSIONAL
        INNER JOIN COMPPEDIDOC
            ON COMPPEDIDOC.CODPROF = PROFISSIONAL.CODPROF
        LEFT JOIN PEDIDOC
            ON COMPPEDIDOC.CODPEDIDO = PEDIDOC.CODPEDIDO
            AND COMPPEDIDOC.TIPOPEDIDO = PEDIDOC.TIPOPEDIDO
            AND COMPPEDIDOC.CODCLIENTE = PEDIDOC.CODCLIENTE
            AND COMPPEDIDOC.CODEMPRESA = PEDIDOC.CODEMPRESA
        WHERE PEDIDOC.DATAFATURA BETWEEN :DATA1 AND :DATA2
          AND PEDIDOC.FATURADO = 'S'
          and pedidoc.codempresa = '00'
        GROUP BY
            PROFISSIONAL.CODPROF,
            PROFISSIONAL.NOME,
            PROFISSIONAL.TELEFONE
        ORDER BY TOTAL_VENDAS DESC
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':DATA1', $data1);
        $stmt->bindValue(':DATA2', $data2);

        $stmt->execute();

        return $stmt->fetchAll();
    }
}