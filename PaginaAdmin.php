<?php
  require_once '_include/_classes/Teacher.php';
  session_start();

  if(!isset($_SESSION['professor'])){
      header("Location:index.php");
  }else{
    $professor=$_SESSION['professor'];
    $nome=utf8_encode($professor->get_name());
    $nomes=explode(" ",$nome);
    $doisnomes=(isset($nomes[0])?$nomes[0]:"")." ".(isset($nomes[1])?$nomes[1]:"");
  }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="_estilos/PaginaAdmin.css" charset="utf-8">
    <link rel="stylesheet" href="jquery-ui-1.11.4.custom/jquery-ui.min.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
  <header id="cabecalho">
      <img src="_imagens/logo_apae.png" id="logoApae" alt="Logo Apae" />
      <h1><?php echo $doisnomes; ?></h1>
      <img src="_imagens/logo_ifms.png" id="logoIfms" alt="Logo IFMS">
  </header>
  <main>
    <form action="deslogarProfessor.php" method="post">
        <button type="submit">Sair</button>
    </form>
    <button id="btn-cadastrarEstudante">Cadastrar Estudante</button>
    <button id="btn-cadastrarTurma">Cadastrar Turma</button>
  </main>
  <div id="testes">

  </div>
  <div id="cadastroEstudante" class="form">
      <div class="foco">
          <h1>Cadastrar Estudante</h1>
          <div id="formCadEstudante">
            <div class="">
              <label for="nome">*Nome:</label>
              <input type="text" id="nome" placeholder="Ex. João da Silva" required>
            </div>
            <div class="">
              <label for="nomeusuario">*Nome de Usuário:</label>
              <input type="text" id="nomeusuario" placeholder="Ex. joao-silva" required>
            </div>
            <div class="">
              <label for="dataNascimento">*Data de Nascimento:</label>
              <input type="text" id="dataNascimento" placeholder="Ex. 10/05/2005" required>
            </div>
            <div class="">
              <label for="">*Possui Deficiência?</label>
              <select id="select-deficiencia">

              </select>
            </div>
            <div class="div-observacao">
              <label for="observacao">Observação:</label>
              <textarea id="observacao" rows="1" cols="40"></textarea>
            </div>
            <div class="">
              <label for="">*Selecione a Turma:</label>
              <select id="select-turmas">

              </select>
            </div>
            <div class="">
              <label for="password">*Senha:</label>
              <input type="password" id="password" placeholder="Mínimo: 6 caracteres" required>
            </div>
            <div class="">
              <label for="confirm-password">*Confirme a senha:</label>
              <input type="password" id="confirm-password" placeholder="Repita a senha" required>
            </div>
            <output> </output>
            <button id="bt1">Cancelar</button>
            <button id="bt2">Cadastrar</button>
          </div>
      </div>
  </div>
  <div id="cadastroTurma" class="form">
    <div class="foco">
        <h1>Cadastrar Turma</h1>
        <div id="formCadTurma">
          <div class="">
            <label for="nometurma">*Turma:</label>
            <input type="text" id="nometurma" placeholder="Ex. 3º ANO - B" required>
          </div>
          <div class="div-observacao">
            <label for="observacaoturma">Observação:</label>
            <textarea id="observacaoturma" rows="1" cols="40"></textarea>
          </div>
          <output> </output>
          <button id="btTurma1">Cancelar</button>
          <button id="btTurma2">Cadastrar</button>
        </div>
    </div>
  </div>
  <script src="_js/jquery-2.1.4.min.js"></script>
  <script src="jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
  <script src="_js/paginaAdmin.js"></script>
</body>
</html>