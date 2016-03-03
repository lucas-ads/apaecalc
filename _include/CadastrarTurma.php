<?php
  require_once "_cruds/daoTurmas.php";

  session_start();

  if(!isset($_SESSION['professor'])){
      echo 'Erro ao salvar a partida, faça login novamente!';
  }else{
    if(isset($_POST['nometurma'])&&isset($_POST['observacaoturma'])){
      $nome=addslashes(strtoupper(trim($_POST['nometurma'])));
      $observacao=addslashes(trim($_POST['observacaoturma']));

      if(strlen($nome)<5||strlen($nome)>20){
        echo json_encode(array('nometurma','O nome da turma deve ter entre 5 e 20 caracteres'));
        return 0;
      }

      if(strlen($observacao)>100){
        echo json_encode(array('observacaoturma','O campo observação não deve ter mais do que 100 caracteres'));
        return 0;
      }

      insereTurma(utf8_decode($nome),utf8_decode($observacao));
      echo json_encode(array(1,'Turma cadastrada com sucesso!'));
    }
  }
 ?>
