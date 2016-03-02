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
            header("location:PaginaAdmin.php");
          }else{
              $place_username="";
              $username="";
              $password="";
              if(isset($_GET['error'])){
                  if($_GET['error']==0){
                      $place_username="Usuario inexistente";
                  }else{
                      if($_GET['error']==-1&&isset($_GET['username'])){
                          $username=$_GET['username'];
                          $password="Senha incorreta";
                      }
                  }
              }
          }
        }
    ?>
    <main id="principal">
        <form action="logar.php" method="post" id="formlogin">
            <h1>Entrar</h1>
            <div class="campo">
                <label for="username">Digite o Nome de Usuário:</label>
                <input name="username" placeholder="<?php echo $place_username;?>" value="<?php echo $username;?>" id="username" type="text" required>
            </div>
            <div class="campo">
                <label for="password">Digite sua senha:</label>
                <input name="password" id="password" placeholder="<?php echo $password;?>" type="password" required>
            </div>
            <button>Entrar</button>
        </form>
    </main>
</body>
</html>
