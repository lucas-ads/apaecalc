<?php
    require_once "ConnectionDatabase.php";

    function efetuarLogin($username,$password){
        $conexao = conectar();
        normalizarAspas($username);
        normalizarAspas($password);

        $resultset = mysqli_query($conexao,"select * from estudante where nome_usuario='".$username."';");

        if($resultset->num_rows>0){
            $resultado=mysqli_fetch_assoc($resultset);
            if($resultado['senha']==md5($password)){
                $estudante = new Estudante($resultado['id'],$resultado['nome'],$resultado['nome_usuario'],$resultado['datanascimento'],$resultado['operacao'],$resultado['etapa'],$resultado['rodada'],$resultado['embaralhar']);
                $resultado = $estudante;
            }else{
                $resultado= -1;
            }
        }else{
            $resultado = 0;
        }

        desconectar($conexao);
        return $resultado;
    }

    function verificaNomeUsuarioEstudante($username){
      $conexao=conectar();
      $resultset = mysqli_query($conexao,"select count(*) from estudante where nome_usuario='".$username."';");
      $resultset = mysqli_fetch_assoc($resultset);
      desconectar($conexao);
      return $resultset['count(*)'];
    }

    function atualizarProgresso($id,$progresso){
        $conexao=conectar();
        $query="update estudante set operacao=".$progresso["operacao"].",rodada=".$progresso["rodada"].", etapa=".$progresso["etapa"].", embaralhar=".$progresso["embaralhar"]." where id=".$id.";";
        mysqli_query($conexao,$query);
        desconectar($conexao);
    }

    function insereEstudante($nome,$nomeusuario,$dataNascimento,$observacao,$senha,$deficiencia){
      $conexao=conectar();
      $stmt=$conexao->prepare("insert into estudante (nome,nome_usuario,senha,data_nascimento,observacao,deficiencia) values(?,?,md5(?),?,?,?);");
      $stmt->bind_param("sssssi",$nome,$nomeusuario,$senha,$dataNascimento,$observacao,$deficiencia);
      $stmt->execute();
      $id = $conexao->insert_id;
      desconectar($conexao);
      return $id;
    }

    function insereEstudanteTurma($idEstudante,$idTurma){
      $conexao=conectar();
      $stmt1=$conexao->prepare("update historico set data_saida=now() where id_estudante=? and data_saida is null;");
      $stmt1->bind_param("i",$idEstudante);
      $stmt1->execute();

      $stmt2=$conexao->prepare("insert into historico (id_estudante, id_turma, data_entrada) values(?,?,now());");
      $stmt2->bind_param("ii",$idEstudante,$idTurma);
      $stmt2->execute();
      desconectar($conexao);
    }
?>
