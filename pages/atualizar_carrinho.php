<?php
session_start();
header('Content-Type: application/json');

// Recebe o carrinho enviado pelo JS (POST)
$data = json_decode(file_get_contents('php://input'), true);

if ($data !== null) {
    $_SESSION['carrinho'] = $data;
    echo json_encode(['status' => 'ok']);
    exit;
}

// Se for GET, retorna o carrinho atual da sessão
$carrinho = $_SESSION['carrinho'] ?? [];
echo json_encode($carrinho);
