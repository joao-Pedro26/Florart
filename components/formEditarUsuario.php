 <form class="form-editar" action="editar.php" method="POST">
            <input type="hidden" name="tipo" value="usuario">
            <input type="hidden" name="id" value="<?= $registro['id_usuario'] ?>">

            <label>Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($registro['nome']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($registro['email']) ?>" required>

            <label>Telefone:</label>
            <input type="text" name="telefone" value="<?= htmlspecialchars($registro['telefone']) ?>" required>

            <div class="label-select">
            <label for="admin">Admin:</label>
                <select name="admin" id="admin">
                    <option value="1" <?= $registro['admin'] ? 'selected' : '' ?>>Sim</option>
                    <option value="0" <?= !$registro['admin'] ? 'selected' : '' ?>>Não</option>
                </select>
            </div>

            <button type="submit" class="btn-salvar">Salvar Alterações</button>

            <a href="admin.php?aba=<?= $tipo === 'usuario' ? 'usuarios' : 'produtos' ?>" class="btn-voltar"><i class="fas fa-arrow-left"></i> Voltar</a>
        </form>