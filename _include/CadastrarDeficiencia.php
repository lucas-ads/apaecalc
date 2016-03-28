<?php
  require_once "_cruds/daoDeficiencia.php";

  session_start();

  if(!isset($_SESSION['professor'])){
      echo 'Erro ao salvar a partida, faça login novamente!';
  }else{
    if(isset($_POST['nomedeficiencia'])){
      $nome=addslashes(strtoupper(trim($_POST['nomedeficiencia'])));

      if(strlen($nome)<5||strlen($nome)>80){
        echo json_encode(array('nomedeficiencia','O nome da deficiência deve ter entre 5 e 20 caracteres'));
        return 0;
      }

      $id=insereDeficiencia(utf8_decode($nome));
      echo json_encode(array($id,'Deficiência cadastrada com sucesso!'));
    }
  }
 ?>
