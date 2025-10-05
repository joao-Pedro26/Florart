<?php
session_start();
require_once "../public/routesUsuarios.php";

$etapa = 1; // 1 = digitar e-mail, 2 = código, 3 = nova senha

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $route = $_POST['route'] ?? '';
    $resultado = handleRoute();

    if (!$resultado) {
        $_SESSION['erro'] = 'Erro ao processar a requisição.';
    }

    if ($route === 'consultas/enviarCodigo' && $resultado) $etapa = 2;
    if ($route === 'consultas/validarCodigo' && $resultado) $etapa = 3;
    if ($route === 'consultas/redefinirSenhaCodigo' && $resultado) {
        $_SESSION['sucesso'] = "Senha alterada com sucesso!";
        header("Location: loginTeste.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Recuperar Senha</title>
<link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="../styles/reset.css" />
<link rel="stylesheet" href="../styles/style.css" />
</head>

<body>

  <main>
    <a href="javascript:history.back()"><i class='bxr  bx-arrow-left-stroke icon-voltar'  style='color:#5a2ff4'></i> </a> 


    <?php
          if (isset($_SESSION['erro'])): ?>
              <div class="erro" style="color:red; text-align:center; margin:10px 0; ; position: relative; ">
                  <?php 
                      echo htmlspecialchars($_SESSION['erro']); 
                      unset($_SESSION['erro']);   
                  ?>
              </div>
  <?php endif; ?>

  
    <a href=""><img src="/images/logo.png" alt="logo" class="logo-log-cad"></a>
    
    <div class="container-log-cad" role="region" aria-label="Formulário de recuperação de senha">
        <div class="header-log-cad">
            <a href="../pages/home.php">
            </a>
        </div>




      <?php if ($etapa === 1): ?>
        <form  class="form-login" " method="POST" action="" novalidate>
          <input type="hidden" name="route" value="consultas/enviarCodigo" />
          <div class="input-group" > 
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" required autocomplete="username" />
          </div>
          <button type="submit" class="btn-login">Enviar Código</button>
        </form>

      <?php elseif ($etapa === 2): ?>
        <form class="form-login" method="POST" action="" novalidate id="formCodigo">
          <input type="hidden" name="route" value="consultas/validarCodigo" />
          <div class="input-group">
          <label for="codigo1">Código de 6 dígitos</label>
          <div class="codigo-inputs">
            <?php for ($i=0; $i < 6; $i++): ?>
              <input type="text" id="codigo<?= $i ?>" name="codigo[]" maxlength="1" inputmode="numeric" pattern="[0-9]" required />
            <?php endfor; ?>
          </div>
          </div>
          <button type="submit" class="btn-login">Confirmar Código</button>
        </form>

      <?php elseif ($etapa === 3): ?>
        <form  class="form-login" method="POST" action="" novalidate>
          <input type="hidden" name="route" value="consultas/redefinirSenhaCodigo" />
          <div class="input-group">
          <label for="senha">Nova senha</label>
          <input type="password" id="senha" name="senha" required minlength="8" autocomplete="new-password" />
          </div>
          <button type="submit" class="btn-login">Redefinir Senha</button>
        </form>
      <?php endif; ?>
    </div>

    
  </main>

  <script>
    // Script para juntar os inputs do código em um campo único antes do envio
    const formCodigo = document.getElementById('formCodigo');
    if (formCodigo) {
      formCodigo.addEventListener('submit', function(e) {
        const inputs = this.querySelectorAll('.codigo-inputs input');
        const codigo = Array.from(inputs).map(i => i.value).join('');
        if(codigo.length !== 6){
          e.preventDefault();
          alert('Por favor, preencha o código completo de 6 dígitos.');
          return;
        }
        // cria input hidden com o código concatenado
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'codigo';
        hiddenInput.value = codigo;
        this.appendChild(hiddenInput);
      });

      // Focar no próximo input automaticamente
      const inputsCodigo = formCodigo.querySelectorAll('.codigo-inputs input');
      inputsCodigo.forEach((input, idx) => {
        input.addEventListener('input', () => {
          if(input.value.length === 1 && idx < inputsCodigo.length -1) {
            inputsCodigo[idx + 1].focus();
          }
        });
        input.addEventListener('keydown', (e) => {
          if(e.key === "Backspace" && input.value === "" && idx > 0){
            inputsCodigo[idx - 1].focus();
          }
        });
      });
    }
  </script>

</body>
</html>