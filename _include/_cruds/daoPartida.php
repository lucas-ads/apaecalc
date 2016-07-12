<?php
    require_once "ConnectionDatabase.php";

    function salvarPartida($operacao,$etapa,$carreira,$embaralhado,$rodada,$idEstudante){
        $conexao = conectar();
        $operacao=intval($operacao);
        $etapa=intval($etapa);
        $rodada=intval($rodada);
        $idEstudante=intval($idEstudante);

        if($operacao>0&&$operacao<5&&$etapa>0){
            $resultset = mysqli_query($conexao,"select MAX(id) from historico where id_estudante=".$idEstudante.";");

            $id=mysqli_fetch_assoc($resultset);
            $id=$id["MAX(id)"];

            if($id!=null){
                if($rodada>0){
                  $stmt=$conexao->prepare("insert into partida (operacao, etapa, carreira,rodada,embaralhado,id_historico,data_partida) values(?,?,?,?,?,?,now())");
                  $stmt->bind_param("iiiiii",$operacao,$etapa,$carreira,$rodada,$embaralhado,$id);
                }else{
                  $stmt=$conexao->prepare("insert into partida (operacao, etapa, carreira,embaralhado,id_historico,data_partida) values(?,?,?,?,?,now())");
                  $stmt->bind_param("iiiii",$operacao,$etapa,$carreira,$embaralhado,$id);
                }

                $stmt->execute();
                return $conexao->insert_id;
            }
            return -1;
        }

        return -1;
    }

    function concluirPartida($idPartida,$partidaVencida){
      if(is_bool($partidaVencida)&&is_numeric($idPartida)){
        $conexao=conectar();
        $stmt=$conexao->prepare("update partida set tempototal=(select sum(tempo_gasto) from resposta where id_pergunta in (select id from pergunta where id_partida=?)),vencida=? where id=?;");
        $stmt->bind_param("iii",$idPartida,$partidaVencida,$idPartida);
        $stmt->execute();
        return 1;
      }
      return -1;
    }

    function getPartidasByEstudanteAndRodada($idEstudante,$rodada){
      $idEstudante=intval($idEstudante);
      $rodada=intval($rodada);
      $conexao = conectar();

      $resultset = mysqli_query($conexao,"select partida.*,historico.id_turma from partida inner join historico on partida.id_historico=historico.id where historico.id_estudante=".$idEstudante." and partida.rodada=".$rodada." and partida.vencida=1;");

      $partida=null;
      while($row=mysqli_fetch_assoc($resultset)){
         $partida[]= $row;
      }

      desconectar($conexao);
      return $partida;
    }

    function getPartidasByHistorico($idHistorico){
      $idEstudante=intval($idHistorico);
      $conexao = conectar();

      $resultset = mysqli_query($conexao,"select partida.* from partida where id_historico=".$idHistorico.";");

      $partida=null;
      while($row=mysqli_fetch_assoc($resultset)){
        $datapartida=explode("-",$row["data_partida"]);
        $row["data_partida"]=$datapartida[2]."/".$datapartida[1]."/".$datapartida[0];
        $partida[]= $row;
      }

      desconectar($conexao);
      return $partida;
    }

?>
