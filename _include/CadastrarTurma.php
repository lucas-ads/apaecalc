<?php
  require_once "_cruds/daoTurmas.php";

  session_start();

  if(!isset($_SESSION['professor'])){
      echo 'Erro ao salvar a partida, faça login novamente!';
  }else{
    if(isset($_POST['nometurma'])&&isset($_POST['periodoturma'])){
      $nome=addslashes(strtoupper(trim($_POST['nometurma'])));
      $periodo=addslashes(trim($_POST['periodoturma']));

      if(strlen($nome)<5||strlen($nome)>20){
        echo json_encode(array('nometurma','O nome da turma deve ter entre 5 e 20 caracteres'));
        return 0;
      }

      if(strlen($periodo)>15){
        echo json_encode(array('periodoturma','O campo periodo não deve ter mais do que 15 caracteres'));
        return 0;
      }

      insereTurma(utf8_decode($nome),utf8_decode($periodo));
      echo json_encode(array(1,'Turma cadastrada com sucesso!'));
    }
  }
 ?>
