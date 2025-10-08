<form class="form-editar" action="editar.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="tipo" value="produto">
            <input type="hidden" name="id" value="<?= $registro['id_produto'] ?>">

            <label>Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($registro['nome']) ?>" required>

            <label>Tipo:</label>
            <input type="text" name="tipo_produto" value="<?= htmlspecialchars($registro['tipo']) ?>" required>

            <label>Valor Unitário:</label>
            <input type="number" name="valor_unitario" step="0.01" value="<?= htmlspecialchars($registro['valor_unitario']) ?>" required>

            <label>Estoque:</label>
            <input type="number" name="estoque" value="<?= htmlspecialchars($registro['estoque']) ?>" required>

            <label>Descrição:</label>
            <textarea name="descricao"><?= htmlspecialchars($registro['descricao']) ?></textarea>

            <label>Imagem (opcional):</label>
            <input type="file" name="imagem">

            <button type="submit" class="btn-salvar">Salvar Alterações</button>

            <a href="admin.php?aba=<?= $tipo === 'usuario' ? 'usuarios' : 'produtos' ?>" class="btn-voltar"><i class="fas fa-arrow-left"></i> Voltar</a>
        </form>