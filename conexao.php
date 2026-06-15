<?php
/* ==============================
   CONFIGURAÇÕES DO BANCO
================================ */

$host     = 'localhost';
$database = 'C:/Treinamento/RESULTH.FB';
$user     = 'SYSDBA';
$password = 'masterkey';
$charset = 'ISO8859_1';

/* ==============================
   CONEXÃO PDO FIREBIRD
================================ */

try {

    $dsn = "firebird:dbname={$host}:{$database};charset={$charset}";

    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_AUTOCOMMIT         => true
    ]);

} catch (PDOException $e) {

    die(
        "Erro ao conectar no Firebird: " .
        $e->getMessage()
    );
}
