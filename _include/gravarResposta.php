<?php
    require_once '_classes/Partida.php';
    require_once '_classes/Estudante.php';
    require_once '_cruds/daoResposta.php';
    require_once '_cruds/daoPartida.php';
    require_once '_cruds/daoPergunta.php';
    require_once '_cruds/daoEstudante.php';

    session_start();

    if(!isset($_SESSION['estudante'])){
        echo 'Erro ao salvar a partida, faça login novamente!';
    }else{
        if(isset($_SESSION['partida'])&&isset($_POST['valor'])&&isset($_POST['pergunta'])&&isset($_POST['tempo'])){
            $partida=$_SESSION['partida'];
            $estudante=$_SESSION['estudante'];
            $retorno=$partida->responder_pergunta(intval($_POST['pergunta']),intval($_POST['valor']));
            $idPartida=1;
            $idsperguntas=1;
            if($retorno>=0){
              if($partida->devoSalvarPartida()){
                $idPartida=salvarPartida($partida->get_operacao(),$partida->get_etapa(),$partida->get_carreira(),$partida->get_embaralhado(),$partida->get_rodada(),$estudante->get_id());
                if($idPartida>-1){
                  $partida->set_id($idPartida);
                  $idsperguntas=salvarPerguntas($partida->get_id(),$partida->get_perguntas());
                  if($idsperguntas!=-1){
                    $partida->set_ids_perguntas($idsperguntas);
                  }
                }
              }
              if($idPartida!=-1&&$idsperguntas!=-1){
                $resultado=salvarResposta($partida->get_id_pergunta(intval($_POST['pergunta'])),intval($_POST['valor']),$retorno,intval($_POST['tempo']));
                if($partida->verificaPartidaConcluida()){
                  concluirPartida($partida->get_id(),$partida->verificaPartidaVencida());
                  if($partida->devoAtualizarProgresso()){
                    atualizarProgresso($estudante->get_id(),$estudante->passa_fase());
                  }
                  unset($_SESSION['partida']);
                }
              }
            }
        }
    }
?>
