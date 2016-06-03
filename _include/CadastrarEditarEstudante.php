<?php
  require_once "_cruds/daoEstudante.php";
  require_once "_cruds/daoTeacher.php";
  require_once "_cruds/daoTurmas.php";
  require_once "_cruds/daoDeficiencia.php";
  require_once "_classes/Estudante.php";

  session_start();

  if(!isset($_SESSION['professor'])){
      echo 'Erro ao salvar a partida, faça login novamente!';
  }else{
    if(isset($_POST['operacao'])&&isset($_POST['deficiencia'])&&isset($_POST['nome'])&&isset($_POST['nomeusuario'])&&isset($_POST['dataNascimento'])&&isset($_POST['observacao'])&&isset($_POST['embaralharjogo'])&&isset($_POST['senha'])&&isset($_POST['confirmasenha'])){
      $operacao=$_POST['operacao'];
      if(($operacao=="CADASTRAR"&&isset($_POST['turma']))||($operacao=="EDITAR"&&isset($_POST['alterarsenha']))){
        $nome=ucwords(strtolower(trim($_POST['nome'])));
        $nomeusuario=strtolower(trim($_POST['nomeusuario']));
        $dataNascimento=trim($_POST['dataNascimento']);
        $observacao=trim(addslashes($_POST['observacao']));
        $deficiencia=intval($_POST['deficiencia']);
        $embaralharjogo=intval($_POST['embaralharjogo']);
        $senha=$_POST['senha'];
        $confirmasenha=$_POST['confirmasenha'];
        $alterarsenha=0;
        $idEstudante=0;
        if($operacao=="EDITAR"){
          $alterarsenha=intval($_POST['alterarsenha']);
          $idEstudante=intval($_POST['idEstudante']);
        }

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

        $resultado=(verificaNomeUsuarioEstudante($nomeusuario,$idEstudante)>0||verificaNomeUsuarioProfessor($nomeusuario)>0);
        if($resultado){
          echo json_encode(array('nomeusuario','Nome de usuário já existente!'));
          return 0;
        }

        $resultado=Estudante::verificaDataNascimento($dataNascimento);
        if($resultado[0]==0){
          echo $resultado[1];
          return 0;
        }
        $dataNascimento=$resultado[1];

        if($embaralharjogo<0||$embaralharjogo>1){
          echo json_encode(array('radio-noembaralhar','Ops! Houve um erro, recarregue a página e tente novamente!'));
          return 0;
        }

        $resultado=verificaIdDeficiencia($deficiencia);
        if($resultado<1){
          echo json_encode(array('deficiencia','Deficiência inválida, tente novamente!'));
          return 0;
        }

        if($operacao=="CADASTRAR"){
          $turma=intval($_POST['turma']);
          $resultado=verificaIdTurma($turma);
          if($resultado<1){
            echo json_encode(array('turma','Turma inválida, tente novamente!'));
            return 0;
          }
        }

        if($operacao=="CADASTRAR"||($operacao=="EDITAR"&&$alterarsenha==1)){
          $resultado=($senha==addslashes($senha))&&($senha==str_replace(' ','', $senha))&&strlen($senha)>4&&strlen($senha)<21;
          if(!$resultado){
            echo json_encode(array('password','A senha deve ter entre 5 e 20 caracteres, sem aspas e sem espaços'));
            return 0;
          }

          if($senha!=$confirmasenha){
            echo json_encode(array('password','As senhas digitadas não conferem!'));
            return 0;
          }
        }

        if($operacao=="CADASTRAR"){
          $idEstudante=insereEstudante($nome,$nomeusuario,$dataNascimento,$observacao,$senha,$deficiencia,$turma,$embaralharjogo);
          insereEstudanteTurma($idEstudante,$turma);
          echo json_encode(array($idEstudante,$nomeusuario.' cadastrado(a) com sucesso'));
        }
        if($operacao=="EDITAR"){
          if($alterarsenha==0){
            atualizaDadosGeraisEstudante($idEstudante,$nome,$nomeusuario,$dataNascimento,$observacao,$deficiencia,$embaralharjogo);
            echo json_encode(array($idEstudante,$nomeusuario.' atualizado(a) com sucesso'));
          }else{
            $result=atualizaDadosEstudante($idEstudante,$nome,$nomeusuario,$dataNascimento,$observacao,$deficiencia,$embaralharjogo,$senha);
            echo json_encode(array($idEstudante,$nomeusuario.' atualizado(a) com sucesso'));
          }
        }
      }
    }
  }
 ?>
