<?php
  require_once "_cruds/daoTurmas.php";

  session_start();

  if(!isset($_SESSION['professor'])){
      echo 'Erro ao salvar a partida, faça login novamente!';
  }else{
    if(isset($_POST['nometurma'])&&isset($_POST['periodoturma'])&&isset($_POST['idturma'])){
      $nome=addslashes(strtoupper(trim($_POST['nometurma'])));
      $periodo=addslashes(trim($_POST['periodoturma']));
      $idturma=intval($_POST['idturma']);

      if(strlen($nome)<5||strlen($nome)>20){
        echo json_encode(array('nometurma','O nome da turma deve ter entre 5 e 20 caracteres'));
        return 0;
      }

      if(strlen($periodo)>15){
        echo json_encode(array('periodoturma','O campo periodo não deve ter mais do que 15 caracteres'));
        return 0;
      }

      if($idturma<=0){
        $id=insereTurma($nome,$periodo);
        echo json_encode(array($id,'Turma cadastrada com sucesso!'));
      }else{
        $result=editaTurma($idturma,$nome,$periodo);
        if($result==1){
          echo json_encode(array(1,"Turma editada com sucesso!"));
        }else{
          echo json_encode(array('error','Recarregue a página e tente novamente!'));
        }
      }
    }
  }
 ?>
