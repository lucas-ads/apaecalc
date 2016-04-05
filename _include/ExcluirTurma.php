<?php
  require_once "_cruds/daoTurmas.php";

  session_start();

  if(!isset($_SESSION['professor'])){
      echo 'Erro ao salvar a partida, faça login novamente!';
  }else{
    if(isset($_POST['idturma'])){
      $idturma=intval($_POST['idturma']);
      if($idturma>0){
        $result=excluiTurma($idturma);
        if($result==-1){
          echo json_encode(array('turma',"Turma inválida, recarregue a página e tente novamente!"));
        }else{
          if($result==-2){
            echo json_encode(array('turma',"Essa turma não pode ser excluida por possuir vínculos!"));
          }else{
            echo json_encode(array(1,"Turma excluída com sucesso!"));
          }
        }
      }else{
        echo json_encode(array('turma',"Turma inválida, recarregue a página e tente novamente!"));
      }
    }
  }
 ?>
