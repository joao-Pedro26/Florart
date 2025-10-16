<?php
session_start();

if (isset($_SESSION['carrinho'])) {
    echo json_encode([
        "status" => "ok",
        "carrinho" => $_SESSION['carrinho']
    ]);
} else {
    echo json_encode([
        "status" => "vazio",
        "carrinho" => []
    ]);
}
?>
