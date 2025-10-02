<?php
    session_start();

    require_once '../controller/usuariosController.php';

    if (isset($_SESSION['statusLogado']) && $_SESSION['statusLogado'] === true) {
        header('Location: ../pages/home.php');
        exit;
    }

    $controller = new UsuarioController();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        // Se acessar direto, redireciona para login
        header('Location: ../pages/loginTeste.php');
        exit;
    }

    $route = $_POST['route'] ?? '';

    switch ($route) {

        case 'consultas/login':
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            // Exemplo na parte do login
            if ($controller->loginConta($email, $senha)) {
                header('Location: ../pages/home.php');
                exit;
            } else {
                $_SESSION['erro'] = "Usuário ou senha inválidos.";
                // Salva os valores para reaproveitar no formulário
                $_SESSION['old_inputs'] = [
                    'email' => $email,
                    // não salvar a senha!
                ];
                header('Location: ../pages/loginTeste.php');
                exit;
            }
            break;

        case 'consultas/cadastrar':
            $nome = $_POST['nome'] ?? '';
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';
            $telefone = $_POST['telefone'] ?? '';

            if ($controller->cadastrarConta($nome, $email, $senha, $telefone)) {
                header('Location: ../pages/home.php');
                exit;
            } else {
                // Salva valores antigos para reaproveitar no formulário
                $_SESSION['old_inputs'] = [
                    'nome' => $nome,
                    'email' => $email,
                    'telefone' => $telefone,
                    // NÃO salva senha
                ];

                $_SESSION['erro'] = "Erro no cadastro. Por favor, verifique os dados e tente novamente.";
                header('Location: ../pages/cadastroTeste.php');
                exit;
            }
            break;

        default:
            $_SESSION['erro'] = "Rota inválida.";
            header('Location: ../pages/loginTeste.php');
            exit;

}