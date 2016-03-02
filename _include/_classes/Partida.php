<?php

class Partida {
    private $id;
    private $partidaSalva=false;
    private $perguntas;
    private $idsPerguntas;
    private $embaralhado;
    private $operacao;
    private $etapa;
    private $rodada;
    private $carreira;
    private $perdeu=false;

    public function Partida($perguntas,$operacao,$etapa,$rodada,$carreira,$embaralhado){
      $this->perguntas=$perguntas;
      $this->operacao=$operacao;
      $this->etapa=$etapa;
      $this->rodada=$rodada;
      $this->carreira=$carreira;
      $this->embaralhado=$embaralhado;
    }

    public function get_id(){
      return $this->id;
    }

    public function set_id($id){
      $this->id=$id;
    }

    public function get_perguntas(){
      return $this->perguntas;
    }

    public function set_ids_perguntas($ids){
        if(gettype($ids)=="array"){
            $this->idsPerguntas=$ids;
            return 1;
        }else{
            return -1;
        }
    }

    public function get_id_pergunta($pergunta){
      if(gettype($this->idsPerguntas)=='array'){
        if(count($this->idsPerguntas)>$pergunta){
          return $this->idsPerguntas[$pergunta];
        }
      }
      return -1;
    }

    public function get_embaralhado(){
      return $this->embaralhado;
    }

    public function get_operacao(){
      return $this->operacao;
    }

    public function get_etapa(){
      return $this->etapa;
    }

    public function get_rodada(){
      return $this->rodada;
    }

    public function get_carreira(){
      return $this->carreira;
    }

    public function devoSalvarPartida(){
      if($this->partidaSalva){
        return false;
      }
      $this->partidaSalva=true;
      return true;
    }

    //retorna -1 se a pergunta já foi concluida, -2 se a pergunta não existe, 1 para correto, 0 para errado
    public function responder_pergunta($pergunta,$resposta){
      if(count($this->perguntas)>$pergunta&&$pergunta>=0){
        if(!isset($this->perguntas[$pergunta][3])){
          if($resposta==$this->perguntas[$pergunta][2]){
            $this->perguntas[$pergunta][3]=1;
            return 1;
          }
          if($pergunta==count($this->perguntas)-1){
            Partida::perdeu();
          }
          return 0;
        }
        return -1;
      }
      return -2;
    }

    public function perdeu(){
      $this->perdeu=true;
    }

    public function devoAtualizarProgresso(){
      if($this->carreira==true&&Partida::verificaPartidaConcluida()==true&&$this->perdeu==false){
        return true;
      }
      return false;
    }

    public function verificaPartidaVencida(){
      if(Partida::verificaPartidaConcluida()==true&&$this->perdeu==false){
        return true;
      }
      return false;
    }

    public function verificaPartidaConcluida(){
      for($i=0;$i<count($this->perguntas);$i+=1){
        if(!isset($this->perguntas[$i][3])){
          return false;
        }
      }
      return true;
    }
}
?>
