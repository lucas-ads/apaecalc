<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faça Login!</title>
    <link rel="stylesheet" href="_estilos/index.css">
</head>
<body>
    <?php
        session_start();

        if(isset($_SESSION['estudante'])){
            header("location:PaginaInicial.php");
        }else{
          if(isset($_SESSION['professor'])){
            header("location:Admin.php");
          }else{
              session_unset();
              $place_username="";
              $username="";
              $password="";
              $focus_username="";
              $focus_password="";
              if(isset($_GET['error'])){
                  if($_GET['error']==0){
                      $place_username="Usuario inexistente";
                      $focus_username="autofocus";
                  }else{
                      if($_GET['error']==-1&&isset($_GET['username'])){
                          $username=$_GET['username'];
                          $password="Senha incorreta";
                          $focus_password="autofocus";
                      }
                  }
              }else{
                $focus_username="autofocus";
              }
          }
        }
    ?>
    <header id="cabecalho">
        <img src="_imagens/logo_apae.png" id="logoApae" alt="Logo Apae" />
        <h1>BEM-VINDO!</h1>
        <img src="_imagens/logo_ifms.png" id="logoIfms" alt="Logo IFMS">
    </header>
    <main id="principal">
        <form action="logar.php" method="post" id="formlogin">
            <h1>Login</h1>
            <div class="campo">
                <label for="username">Nome de Usuário:</label>
                <input name="username" placeholder="<?php echo $place_username;?>" value="<?php echo $username;?>" id="username" type="text" required <?php echo $focus_username;?>>
            </div>
            <div class="campo">
                <label for="password">Senha:</label>
                <input name="password" id="password" placeholder="<?php echo $password;?>" type="password" required <?php echo $focus_password;?>>
            </div>
            <button>Entrar</button>
        </form>
    </main>
</body>
</html>
