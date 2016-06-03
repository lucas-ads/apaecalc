<?php

class Estudante {
    private $id;
    private $nome;
    private $nomeusuario;
    private $datanascimento;
    private $observacao;
    private $operacao;
    private $etapa;
    private $rodada;
    private $embaralhar;
    private $defiencia;

    public function Estudante($id,$nome,$nomeusuario,$datanascimento,$observacao,$operacao,$etapa,$rodada,$embaralhar,$deficiencia){
        $this->id=intval($id);
        $this->nome=$nome;
        $this->nomeusuario=$nomeusuario;
        $this->datanascimento=$datanascimento;
        $this->observacao=$observacao;
        $this->operacao=intval($operacao);
        $this->etapa=intval($etapa);
        $this->rodada=intval($rodada);
        $this->embaralhar=$embaralhar;
        $this->deficiencia=$deficiencia;
    }

    public function get_id(){
        return $this->id;
    }

    public function get_nome(){
        return $this->nome;
    }

    public function get_twonames(){
      $names=explode(" ",$this->nome);
      if(count($names)>1){
        $twonames=(isset($names[0])?$names[0]:"")." ".(isset($names[count($names)-1])?$names[count($names)-1]:"");
      }else{
        $twonames=$names[0];
      }
      return $twonames;
    }

    public function get_nomeabrev(){
      if(strlen($this->nome)<=25){
        return $this->nome;
      }else{
        return substr($this->nome,0,22)."...";
      }
    }

    public function get_nomeusuario(){
        return $this->nomeusuario;
    }

    public function get_datanascimento(){
        $data=explode('-',$this->datanascimento);
        return $data[2].'/'.$data[1].'/'.$data[0];
    }

    public function get_observacao(){
      return $this->observacao;
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

    public function get_embaralhar(){
        return $this->embaralhar;
    }

    public function get_deficiencia(){
        return $this->deficiencia;
    }

    public function get_nomeoperacao(){
        switch($this->operacao){
            case 1: return "Adição";
            case 2: return "Subtração";
            case 3: return "Multiplicação";
            case 4: return "Divisão";
            default: return "";
        }
    }

    public function get_quant_stages(){
        switch($this->operacao){
            case 1: return 12; break;
            case 2: return 11; break;
            case 3: return 12; break;
            case 4: return 11; break;
            default: return "error";
        }
    }

    public function passa_fase(){
        if($this->etapa<Estudante::get_quant_stages()){
            $this->etapa+=1;
        }else{
            if($this->operacao<4){
                $this->operacao+=1;
                $this->etapa=1;
            }else{
                $this->rodada+=1;
                $this->operacao=1;
                $this->etapa=1;
                $this->embaralhar=true;
            }
        }
        return array("rodada"=>$this->rodada,"operacao"=>$this->operacao,"etapa"=>$this->etapa,"embaralhar"=>$this->embaralhar);
    }

    public static function verificaNome($nome){
      $resultado=preg_match('/^[a-zá-ú\ ]{10,45}$/i', $nome);
      if(!$resultado){
        return json_encode(array('nome','O nome deve ter entre 10 e 45 caracteres, podendo conter apenas letras, acentuadas ou não'));
      }
      return 1;
    }

    public static function verificaNomeUsuario($nomeusuario){
      $resultado=preg_match('/^[a-z0-9-.]{5,20}$/', $nomeusuario);
      if(!$resultado){
        return json_encode(array('nomeusuario','O nome de usuário deve ter entre 5 e 20 caracteres, podendo conter numeros, letras não acentudadas, traço(-) e ponto(.)'));
      }
      return 1;
    }

    public static function verificaDataNascimento($dataNascimento){
      $resultado=(preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/',$dataNascimento));
      if(!$resultado){
        return array(0,json_encode(array('dataNascimento','Data inválida!')));
      }
      $diaMesAno=explode("/",$dataNascimento);
      if(!checkdate($diaMesAno[1],$diaMesAno[0],$diaMesAno[2])){
        return array(0,json_encode(array('dataNascimento','Data inválida!')));
      }
      return array(1,$diaMesAno[2].'-'.$diaMesAno[1].'-'.$diaMesAno[0]);
    }

    public static function verificaSenha($senha){
      $resultado=($senha==addslashes($senha))&&($senha==str_replace(' ','', $senha))&&strlen($senha)>4&&strlen($senha)<21;
      if(!$resultado){
        return json_encode(array('password','A senha deve ter entre 5 e 20 caracteres, sem aspas e sem espaços'));
      }
      return 1;
    }

}
?>
