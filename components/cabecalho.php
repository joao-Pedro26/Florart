

<header class="cabecalho">
    <a href="#"><img src="/images/logo.png" alt="logo" class="img-logo"></a>
    <input type="checkbox" id="check">
    <label for="check" class="icons">
        <i class='bx bx-menu' id="icon-menu"></i> 
        <i class='bx bxs-x' id="icon-fechar"></i>
    </label>
    <nav class="menu">
        <a href="#" id="home-menu" style="--i: 0">Home</a>
        <a href="#catalogo" id="catalogo-menu" style="--i: 1">Catálogo</a>
        <a href="#sobre" id="sobre-menu" style="--i: 2">Sobre</a>
        <a href="#contato" id="contato-menu" style="--i: 3">Contato</a>

        <?php
        // Se NÃO está logado
        if (!isset($_SESSION['statusLogado']) || $_SESSION['statusLogado'] !== true) {
            echo '<a href="../pages/loginTeste.php">Login</a>';
            echo '<a href="../pages/cadastroTeste.php">Cadastro</a>';
        } 
        // Se for ADMIN
        elseif (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            echo '<a href="../pages/admin.php">Central de Controle</a>';
            echo '<a href="../pages/home.php?route=acoes/sair">Sair</a>';
        } 
        // Se for USUÁRIO comum
        else {
            echo '<a href="../pages/perfil.php">Perfil</a>';
            echo '<a href="../pages/home.php?route=acoes/sair">Sair</a>';
        }
        ?>
    </nav>

    <div class="icon-carrinho">
        <button>
            <i class='bx bx-cart' id="icon-carrinho" style='color:#ffffff'></i> 
        </button>
    </div>
</header>


 <script src="../js/cabecalho.js"></script>
