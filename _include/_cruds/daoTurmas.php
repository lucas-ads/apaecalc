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

  function carregaTurma($id){
    $conexao=conectar();
    $resultset = mysqli_query($conexao,"select * from turma where id=".$id.";");

    $turma=null;
    if($row = mysqli_fetch_assoc($resultset)) {
      $turma=$row;
    }

    desconectar($conexao);
    return $turma;
  }

  function verificaIdTurma($id){
    $conexao=conectar();
    $resultset = mysqli_query($conexao,"select count(*) from turma where id='".$id."';");
    $resultset = mysqli_fetch_assoc($resultset);
    desconectar($conexao);
    return $resultset['count(*)'];
  }

  function insereTurma($nome,$observacao){
    $conexao=conectar();
    $stmt=$conexao->prepare("insert into turma (nome_turma,periodo) values(?,?);");
    $stmt->bind_param("ss",$nome,$observacao);
    $stmt->execute();
    desconectar($conexao);
  }

  function exibeDadosTurma(){
    $conexao=conectar();

    $resultset = mysqli_query($conexao,"select turma.*, count(historico.id_turma) as quant from turma inner join historico on turma.id=historico.id_turma where historico.data_saida is null group by(turma.id);");
    $turmas=[];
    while($row = mysqli_fetch_assoc($resultset)) {
      $turmas[]=array($row['id'],utf8_encode($row['nome_turma']),utf8_encode($row['periodo']),$row['quant']);
    }

    $resultset = mysqli_query($conexao,"select turma.*,0 as quant from turma where turma.id not in (select historico.id_turma from historico where historico.data_saida is null);");
    while($row = mysqli_fetch_assoc($resultset)) {
      $turmas[]=array($row['id'],utf8_encode($row['nome_turma']),utf8_encode($row['periodo']),$row['quant']);
    }

    desconectar($conexao);
    return $turmas;
  }
?>
