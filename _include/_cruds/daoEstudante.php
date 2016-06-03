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
                $estudante = new Estudante($resultado['id'],$resultado['nome'],$resultado['nome_usuario'],$resultado['datanascimento'],$resultado['observacao'],$resultado['operacao'],$resultado['etapa'],$resultado['rodada'],$resultado['embaralhar'],$resultado['deficiencia']);
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

    function verificaNomeUsuarioEstudante($username,$idExcluido){
      $conexao=conectar();
      $resultset = mysqli_query($conexao,"select count(*) from estudante where nome_usuario='".$username."' and id != ".$idExcluido.";");
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

    function insereEstudante($nome,$nomeusuario,$dataNascimento,$observacao,$senha,$deficiencia,$turma,$embaralharjogo){
      $senha=normalizarAspas($senha);
      $conexao=conectar();
      $stmt=$conexao->prepare("insert into estudante (nome,nome_usuario,senha,data_nascimento,observacao,deficiencia,turma_atual,embaralhar) values(?,?,md5(?),?,?,?,?,?);");
      $stmt->bind_param("sssssiii",$nome,$nomeusuario,$senha,$dataNascimento,$observacao,$deficiencia,$turma,$embaralharjogo);
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

    function atualizaDadosGeraisEstudante($idEstudante,$nome,$nomeusuario,$dataNascimento,$observacao,$deficiencia,$embaralharjogo){
      $conexao=conectar();
      $stmt=$conexao->prepare("update estudante set nome=?,nome_usuario=?,data_nascimento=?,observacao=?,deficiencia=?,embaralhar=? where id=?;");
      $stmt->bind_param("ssssiii",$nome,$nomeusuario,$dataNascimento,$observacao,$deficiencia,$embaralharjogo,$idEstudante);
      $stmt->execute();
      desconectar($conexao);
    }

    function atualizaDadosEstudante($idEstudante,$nome,$nomeusuario,$dataNascimento,$observacao,$deficiencia,$embaralharjogo,$senha){
      $senha=normalizarAspas($senha);
      $conexao=conectar();
      $stmt=$conexao->prepare("update estudante set nome=?,nome_usuario=?,data_nascimento=?,observacao=?,deficiencia=?,embaralhar=?,senha=md5(?) where id=?;");
      $stmt->bind_param("ssssiisi",$nome,$nomeusuario,$dataNascimento,$observacao,$deficiencia,$embaralharjogo,$senha,$idEstudante);
      $stmt->execute();
      desconectar($conexao);
    }

    function getEstudanteByClass($idClass){
      $idClass=intval($idClass);
      $conexao = conectar();

      $resultset = mysqli_query($conexao,"select * from estudante where turma_atual=".$idClass." order by nome;");

      $resultado = [];
      while($row=mysqli_fetch_assoc($resultset)){
          $estudante = new Estudante($row['id'],$row['nome'],$row['nome_usuario'],$row['data_nascimento'],$row['observacao'],$row['operacao'],$row['etapa'],$row['rodada'],$row['embaralhar'],$row['deficiencia']);
          $resultado[] = $estudante;
      }

      desconectar($conexao);
      return $resultado;
    }

    function getEstudanteById($idEstudante){
      $idEstudante=intval($idEstudante);
      $conexao = conectar();

      $resultset = mysqli_query($conexao,"select * from estudante where id=".$idEstudante.";");

      if($row=mysqli_fetch_assoc($resultset)){
        $estudante = new Estudante($row['id'],$row['nome'],$row['nome_usuario'],$row['data_nascimento'],$row['observacao'],$row['operacao'],$row['etapa'],$row['rodada'],$row['embaralhar'],$row['deficiencia']);
      }else{
        $estudante=null;
      }

      desconectar($conexao);
      return $estudante;
    }
?>
