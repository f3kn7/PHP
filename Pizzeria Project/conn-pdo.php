<?php

//conexão com banco
$caminho_banco = "199.999.999.999";
$banco_dados = "123";
$usuario = "123";
$pass = "123";

global $conexao;

try {
    $conexao = new PDO("mysql:host=$caminho_banco;dbname=" . $banco_dados, $usuario, $pass);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->exec("set names utf8");
} catch (PDOException $erro) {
    echo "Erro na conexão:" . $erro->getMessage();
} 

