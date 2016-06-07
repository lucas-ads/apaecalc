<?php
  require_once "_cruds/daoEstudante.php";
  require_once "_cruds/daoTurmas.php";

  session_start();

  if(!isset($_SESSION['professor'])){
      echo 'Erro ao salvar a partida, faça login novamente!';
  }else{
    if(isset($_POST['estudantes'],$_POST['turmadestino'])){
      $turmadestino=intval($_POST['turmadestino']);
      $estudantes=$_POST['estudantes'];

      if(verificaIdTurma($turmadestino)!=1){
        echo json_encode(array('transferenciaEstudantes #select-turmas','Houve um erro: "Turma Inválida". Recarregue a página e tente novamente...'));
        return 0;
      }

      for($i=0;$i<count($estudantes);$i+=1){
          if(!is_numeric($estudantes[$i])||(intval($estudantes[$i])!=$estudantes[$i])){
            echo json_encode(array('estudantesSelecionados','1Houve um erro: "Estudante(s) Inválido(s)". Recarregue a página e tente novamente...'));
            return 0;
          }
      }

      $result=verificarTurmasParaTransferencia($estudantes,$turmadestino);
      if($result==-1){
        echo json_encode(array('estudantesSelecionados','2Houve um erro: "Estudante(s) Inválido(s)". Recarregue a página e tente novamente...'));
        return 0;
      }

      if($result==-2){
        echo json_encode(array('transferenciaEstudantes #select-turmas','Houve um erro: "Turma Inválida". Recarregue a página e tente novamente...'));
        return 0;
      }

      transferirEstudantes($estudantes,$turmadestino);
      echo json_encode(array(1,'Estudantes Transferidos com sucesso!'));
    }
  }
 ?>
