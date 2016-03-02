<?php
    require_once '_classes/Estudante.php';
    require_once '_classes/Partida.php';
    require_once 'ConstrutorDeValores.php';

    session_start();

    if(!isset($_SESSION['estudante'])){
        echo 'Erro ao salvar a partida, faça login novamente!';
    }else{
        if(isset($_POST['modo'])){

          $estudante=$_SESSION['estudante'];
          if($_POST['modo']=="sequencial"){
            $values=getValues($estudante->get_operacao(),$estudante->get_etapa(),$estudante->get_embaralhar());
            $partida=new Partida($values,$estudante->get_operacao(),$estudante->get_etapa(),$estudante->get_rodada(),1,$estudante->get_embaralhar());
            $_SESSION['partida']=$partida;
            $values[]=array($estudante->get_operacao(),$estudante->get_etapa());
            echo json_encode($values);
          }else{
            if($_POST['modo']=="arcade"&&isset($_POST['operacao'])&&isset($_POST['etapa'])&&isset($_POST['embaralhar'])){
              $operacao=intval($_POST['operacao']);
              $etapa=intval($_POST['etapa']);
              $embaralhar=$_POST['embaralhar']=='true'?1:0;
              if($operacao>=1&&$operacao<=4&&$etapa>0&&$etapa<=12){
                  $values=getValues($operacao,$etapa,$embaralhar);
                  $partida=new Partida($values,$operacao,$etapa,0,0,$embaralhar);
                  $_SESSION['partida']=$partida;
                  $values[]=array($operacao,$etapa);
                  echo json_encode($values);
              }
            }
          }

        }
    }
?>
