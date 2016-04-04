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

  function editaTurma($id,$nome,$periodo){
    if(verificaIdTurma($id)==0){
      $result=-1;
    }else{
      $conexao=conectar();
      $stmt=$conexao->prepare("update turma set nome_turma=?, periodo=? where id=?;");
      $stmt->bind_param("ssi",$nome,$periodo,$id);
      $result=$stmt->execute();
      desconectar($conexao);
    }
    return $result;
  }

  function insereTurma($nome,$periodo){
    $conexao=conectar();
    $stmt=$conexao->prepare("insert into turma (nome_turma,periodo) values(?,?);");
    $stmt->bind_param("ss",$nome,$periodo);
    $stmt->execute();
    $id=$conexao->insert_id;
    desconectar($conexao);
    return $id;
  }

  function exibeDadosTurma(){
    $conexao=conectar();

    $resultset = mysqli_query($conexao,"select turma.*, COUNT(historico.id_turma)-count(historico.data_saida) as quant, COUNT(historico.id_turma) as vinculo FROM turma LEFT OUTER JOIN historico ON turma.id=historico.id_turma GROUP BY turma.id order by turma.nome_turma;");
    $turmas=[];
    while($row = mysqli_fetch_assoc($resultset)) {
      $turmas[]=array($row['id'],utf8_encode($row['nome_turma']),utf8_encode($row['periodo']),$row['quant'],$row['vinculo']);
    }

    desconectar($conexao);
    return $turmas;
  }
?>
