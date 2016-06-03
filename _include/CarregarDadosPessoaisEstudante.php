<?php
  require_once "_cruds/daoEstudante.php";
  require_once "_classes/Estudante.php";

  session_start();

  if(!isset($_SESSION['professor'])){
      echo 'Erro ao salvar a partida, faça login novamente!';
  }else{
    if(isset($_POST['idEstudante'])){
      $idEstudante=intval($_POST['idEstudante']);
      $result=getEstudanteById($idEstudante);

      if($result!=null){
        echo json_encode(array(1,$result->get_nome(),$result->get_nomeusuario(),$result->get_datanascimento(),$result->get_deficiencia(),$result->get_observacao(),$result->get_embaralhar()));
      }else{
        echo json_encode(array('error','Recarregue a página e tente novamente!'));
      }
    }
  }
 ?>
