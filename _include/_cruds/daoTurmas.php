<?php
  require_once "ConnectionDatabase.php";

  function carregarTurmas(){
    $conexao=conectar();

    $resultset = mysqli_query($conexao,"select * from turma;");

    $turmas=[];
    while($row = mysqli_fetch_assoc($resultset)) {
      $turmas[]=array($row['id'],utf8_encode($row['nome_turma']));
    }

    desconectar($conexao);
    return $turmas;
  }

  function verificaIdTurma($id){
    $conexao=conectar();
    $resultset = mysqli_query($conexao,"select count(*) from turma where id='".$id."';");
    $resultset = mysqli_fetch_assoc($resultset);
    desconectar($conexao);
    return $resultset['count(*)'];
  }
?>
