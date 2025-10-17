<?php
require_once "../controller/compraController.php";

function handleCompraRoute() {
    $route = $_GET['route'] ?? '';
    $controller = new CompraController();

    switch ($route) {
        case 'compras/listarCompras':
            return $controller->listarCompras();

        case 'compras/cancelar':
            $id = $_GET['id'] ?? null;
            if ($id) {
                return $controller->cancelarCompra($id);
            }
            break;

        default:
            error_log("Rota de compra desconhecida: $route");
            break;
    }

    return null;
}