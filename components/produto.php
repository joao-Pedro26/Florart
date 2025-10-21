<?php
// Garantir que $produto existe e é array
if (!isset($produto) || !is_array($produto)) return;

// Valores padrão caso estejam vazios
$id        = $produto['id_produto'] ?? 0;
$nome      = htmlspecialchars($produto['nome'] ?? 'Produto sem nome');
$descricao = htmlspecialchars($produto['descricao'] ?? '');
$imagem    = $produto['imagem'] ?? '../imagens/produto-padrao.jpg';
$tipo      = htmlspecialchars($produto['tipo'] ?? '');
$preco     = isset($produto['valor_unitario']) ? number_format($produto['valor_unitario'], 2, ',', '.') : '0,00';

?>

<div class="container-produtos" data-id="<?= $id ?>">
    <div class="img-produto">
        <img src="<?= $imagem ?>" alt="<?= $nome ?>">
    </div>
    <div class="info-produtos">
        <div>
            <h3><?= $nome ?></h3>
            <p><?= $tipo ?></p>
            <p id='descricao'><?= $descricao ?></p>
            <div class="preco-produto">R$ <?= $preco ?></div>
        </div>
        <div class="botoes">

            <?php

            $idJS     = isset($produto['id_produto']) ? intval($produto['id_produto']) : 0;
            $nomeJS   = isset($produto['nome']) ? addslashes($produto['nome']) : 'Produto sem nome';
            $precoJS  = isset($produto['valor_unitario']) ? floatval($produto['valor_unitario']) : 0;
            $imagemJS = isset($produto['imagem']) && !empty($produto['imagem']) ? addslashes($produto['imagem']) : '../imagens/produto-padrao.jpg';

            echo '<div class="btn-comprar">
                        <button 
                            class="btn-add-carrinho" 
                            data-id="' . $idJS . '" 
                            data-nome="' . $nomeJS . '" 
                            data-preco="' . $precoJS . '" 
                            data-imagem="' . $imagemJS . '">
                            <i class="bxr bx-cart-plus icon-carrinho"></i>
                        </button> 
                    </div>';
            ?>
        </div>
    </div>
</div>