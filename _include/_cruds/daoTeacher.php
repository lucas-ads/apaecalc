<?php
    require_once "ConnectionDatabase.php";

    function efetuarLoginTeacher($username,$password){
        $conexao = conectar();
        normalizarAspas($username);
        normalizarAspas($password);

        $resultset = mysqli_query($conexao,"select * from professor where nome_usuario='".$username."';");

        if($resultset->num_rows>0){
            $resultado=mysqli_fetch_assoc($resultset);
            if($resultado['senha']==md5($password)){
                $teacher = new Teacher($resultado['id'],$resultado['nome_usuario'],$resultado['nome']);
                $resultado = $teacher;
            }else{
                $resultado= -1;
            }
        }else{
            $resultado = 0;
        }

        desconectar($conexao);
        return $resultado;
    }

    function insereProfessor($nome,$nomeusuario,$senha){
      $senha=normalizarAspas($senha);
      $conexao=conectar();
      $stmt=$conexao->prepare("insert into professor (nome,nome_usuario,senha) values(?,?,md5(?));");
      $stmt->bind_param("sss",$nome,$nomeusuario,$senha);
      $stmt->execute();
      $id = $conexao->insert_id;
      desconectar($conexao);
      return $id;
    }

    function verificaNomeUsuarioProfessor($username){
      $conexao=conectar();
      $resultset = mysqli_query($conexao,"select count(*) from professor where nome_usuario='".$username."';");
      $resultset = mysqli_fetch_assoc($resultset);
      desconectar($conexao);
      return $resultset['count(*)'];
    }
?>
