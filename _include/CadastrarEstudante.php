<?php
  require_once "_cruds/daoEstudante.php";
  require_once "_cruds/daoTeacher.php";
  require_once "_cruds/daoTurmas.php";
  require_once "_cruds/daoDeficiencia.php";

  session_start();

  if(!isset($_SESSION['professor'])){
      echo 'Erro ao salvar a partida, faça login novamente!';
  }else{
    if(isset($_POST['deficiencia'])&&isset($_POST['nome'])&&isset($_POST['nomeusuario'])&&isset($_POST['dataNascimento'])&&isset($_POST['observacao'])&&isset($_POST['turma'])&&isset($_POST['senha'])&&isset($_POST['confirmasenha'])&&isset($_POST['embaralharjogo'])){
      $nome=ucwords(strtolower(trim($_POST['nome'])));
      $nomeusuario=strtolower(trim($_POST['nomeusuario']));
      $dataNascimento=trim($_POST['dataNascimento']);
      $observacao=trim(addslashes($_POST['observacao']));
      $turma=intval($_POST['turma']);
      $deficiencia=intval($_POST['deficiencia']);
      $senha=$_POST['senha'];
      $confirmasenha=$_POST['confirmasenha'];
      $embaralharjogo=intval($_POST['embaralharjogo']);

      $resultado=preg_match('/^[a-zá-ú\ ]{10,45}$/i', $nome);
      if(!$resultado){
        echo json_encode(array('nome','O nome deve ter entre 10 e 45 caracteres, podendo conter apenas letras, acentuadas ou não'));
        return 0;
      }

      $resultado=preg_match('/^[a-z0-9-.]{5,20}$/', $nomeusuario);
      if(!$resultado){
        echo json_encode(array('nomeusuario','O nome de usuário deve ter entre 5 e 20 caracteres, podendo conter numeros, letras não acentudadas, traço(-) e ponto(.)'));
        return 0;
      }

      $resultado=(verificaNomeUsuarioEstudante($nomeusuario)>0||verificaNomeUsuarioProfessor($nomeusuario)>0);
      if($resultado){
        echo json_encode(array('nomeusuario','Nome de usuário já existente!'));
        return 0;
      }

      $resultado=(preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/',$dataNascimento));
      if(!$resultado){
        echo json_encode(array('dataNascimento','Data inválida!'));
        return 0;
      }
      $diaMesAno=explode("/",$dataNascimento);
      if(!checkdate($diaMesAno[1],$diaMesAno[0],$diaMesAno[2])){
        echo json_encode(array('dataNascimento','Data inválida!'));
        return 0;
      }
      $dataNascimento=$diaMesAno[2].'-'.$diaMesAno[1].'-'.$diaMesAno[0];

      if($embaralharjogo<0||$embaralharjogo>1){
        echo json_encode(array('radio-noembaralhar','Ops! Houve um erro, recarregue a página e tente novamente!'));
        return 0;
      }

      $resultado=verificaIdTurma($turma);
      if($resultado<1){
        echo json_encode(array('turma','Turma inválida, tente novamente!'));
        return 0;
      }

      $resultado=verificaIdDeficiencia($deficiencia);
      if($resultado<1){
        echo json_encode(array('deficiencia','Deficiência inválida, tente novamente!'));
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

      $idEstudante=insereEstudante(utf8_decode($nome),$nomeusuario,$dataNascimento,utf8_decode($observacao),$senha,$deficiencia,$turma,$embaralharjogo);
      insereEstudanteTurma($idEstudante,$turma);
      echo json_encode(array(1,$nomeusuario.' cadastrado(a) com sucesso'));
    }
  }
 ?>
