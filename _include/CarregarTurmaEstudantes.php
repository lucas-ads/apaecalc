<?php
  require_once "_cruds/daoTurmas.php";
  require_once "_cruds/daoEstudante.php";

  session_start();

  if(!isset($_SESSION['professor'])){
    echo 'Erro ao salvar a partida, faça login novamente!';
  }else{
    if(isset($_POST['idturma'])&&is_numeric($_POST['idturma'])){
      $turma=carregaTurma($_POST['idturma']);
      $turma=array($turma['id'],utf8_encode($turma['nome_turma']),utf8_encode($turma['periodo']));
      echo json_encode($turma);
    }
  }
 ?>
