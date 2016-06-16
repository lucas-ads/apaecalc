<?php
    require_once "ConnectionDatabase.php";

    function getMatriculasVigentes($idturma){
      $conexao=conectar();
      $resultset = mysqli_query($conexao,"select estudante.id,historico.data_entrada from estudante inner join historico on estudante.id=historico.id_estudante where historico.data_saida is null and historico.id_turma=".$idturma." order by estudante.nome;");
      $matriculas=[];
      while($row = mysqli_fetch_assoc($resultset)) {
        $data=explode('-',$row['data_entrada']);
        $row['data_entrada']=$data[2].'/'.$data[1].'/'.$data[0];
        $matriculas[]=$row;
      }

      desconectar($conexao);
      return $matriculas;
    }

    function getHistoricoTurma($idturma){
      $conexao=conectar();
      $resultset = mysqli_query($conexao,"select estudante.nome,estudante.nome_usuario,estudante.data_nascimento,deficiencia.nome_deficiencia,historico.data_entrada,historico.data_saida from deficiencia inner join estudante on deficiencia.id=estudante.deficiencia inner join historico on estudante.id=historico.id_estudante where historico.data_saida is not null and historico.id_turma=".$idturma." order by estudante.nome;");
      $matriculas=[];
      while($row = mysqli_fetch_assoc($resultset)) {
        $data=explode('-',$row['data_entrada']);
        $row['data_entrada']=$data[2].'/'.$data[1].'/'.$data[0];
        $data=explode('-',$row['data_saida']);
        $row['data_saida']=$data[2].'/'.$data[1].'/'.$data[0];
        $data=explode('-',$row['data_nascimento']);
        $row['data_nascimento']=$data[2].'/'.$data[1].'/'.$data[0];
        $matriculas[]=$row;
      }

      desconectar($conexao);
      return $matriculas;
    }
?>
