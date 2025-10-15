<?php
session_start();

$dados = json_decode(file_get_contents("php://input"), true);

if (!isset($dados['carrinho'])) {
    echo json_encode(["status" => "erro", "mensagem" => "Carrinho não recebido"]);
    exit;
}

$_SESSION['carrinho'] = $dados['carrinho'];

echo json_encode(["status" => "ok", "mensagem" => "Carrinho salvo na sessão"]);
?>

