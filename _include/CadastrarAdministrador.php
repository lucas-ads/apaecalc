<?php
  require_once "_cruds/daoEstudante.php";
  require_once "_cruds/daoTeacher.php";
  require_once "_classes/Estudante.php";

  session_start();

  if(!isset($_SESSION['professor'])){
      echo 'Erro ao salvar a partida, faça login novamente!';
  }else{
    if(isset($_POST['operacao'])&&isset($_POST['nome'])&&isset($_POST['nomeusuario'])&&isset($_POST['senha'])&&isset($_POST['confirmasenha'])){
      $operacao=$_POST['operacao'];
      $nome=ucwords(strtolower(trim($_POST['nome'])));
      $nomeusuario=strtolower(trim($_POST['nomeusuario']));
      $senha=$_POST['senha'];
      $confirmasenha=$_POST['confirmasenha'];

      $resultado=Estudante::verificaNome($nome);
      if(!is_integer($resultado)){
        echo $resultado;
        return 0;
      }

      $resultado=Estudante::verificaNomeUsuario($nomeusuario);
      if(!is_integer($resultado)){
        echo $resultado;
        return 0;
      }

      $resultado=(verificaNomeUsuarioEstudante($nomeusuario,-1)>0||verificaNomeUsuarioProfessor($nomeusuario)>0);
      if($resultado){
        echo json_encode(array('nomeusuario','Nome de usuário já existente!'));
        return 0;
      }

      $resultado=($senha==addslashes($senha))&&($senha==str_replace(' ','', $senha))&&strlen($senha)>4&&strlen($senha)<21;
      if(!$resultado){
        echo json_encode(array('password','A senha deve ter entre 5 e 20 caracteres, sem aspas e sem espaços'));
        return 0;
      }

      if($senha!=$confirmasenha){
          echo json_encode(array('password','As senhas digitadas não conferem!'));
          return 0;
      }

      if($operacao=="CADASTRAR"){
          $idProfessor=insereProfessor($nome,$nomeusuario,$senha);
          echo json_encode(array($idProfessor,$nomeusuario.' cadastrado(a) com sucesso'));
      }
        /*if($operacao=="EDITAR"){
          if($alterarsenha==0){
            atualizaDadosGeraisEstudante($idEstudante,$nome,$nomeusuario,$dataNascimento,$observacao,$deficiencia,$embaralharjogo);
            echo json_encode(array($idEstudante,$nomeusuario.' atualizado(a) com sucesso'));
          }else{
            $result=atualizaDadosEstudante($idEstudante,$nome,$nomeusuario,$dataNascimento,$observacao,$deficiencia,$embaralharjogo,$senha);
            echo json_encode(array($idEstudante,$nomeusuario.' atualizado(a) com sucesso'));
          }
        }*/
    }
  }
 ?>
