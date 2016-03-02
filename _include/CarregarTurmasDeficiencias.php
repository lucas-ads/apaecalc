<?php
  require_once "_cruds/daoTurmas.php";
  require_once "_cruds/daoDeficiencia.php";

  session_start();

  if(!isset($_SESSION['professor'])){
      echo 'Erro ao salvar a partida, faça login novamente!';
  }else{
    $retorno[0]=carregarDeficiencias();
    $retorno[1]=carregarTurmas();
    echo(json_encode($retorno));
  }
 ?>
