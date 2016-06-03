<?php
  require_once "ConnectionDatabase.php";

  function carregarDeficiencias(){
    $conexao=conectar();

    $resultset = mysqli_query($conexao,"select * from deficiencia;");

    $deficiencias=[];
    while($row = mysqli_fetch_assoc($resultset)) {
      $deficiencias[]=array($row['id'],$row['nome_deficiencia']);
    }

    desconectar($conexao);
    return $deficiencias;
  }

  function verificaIdDeficiencia($id){
    $conexao=conectar();
    $resultset = mysqli_query($conexao,"select count(*) from deficiencia where id='".$id."';");
    $resultset = mysqli_fetch_assoc($resultset);
    desconectar($conexao);
    return $resultset['count(*)'];
  }

  function insereDeficiencia($nome){
    $conexao=conectar();
    $stmt=$conexao->prepare("insert into deficiencia (nome_deficiencia) values(?);");
    $stmt->bind_param("s",$nome);
    $stmt->execute();
    $id=$conexao->insert_id;
    desconectar($conexao);
    return $id;
  }
?>
