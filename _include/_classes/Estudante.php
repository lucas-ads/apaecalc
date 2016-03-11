<?php

class Estudante {
    private $id;
    private $nome;
    private $nomeusuario;
    private $datanascimento;
    private $operacao;
    private $etapa;
    private $rodada;
    private $embaralhar;

    public function Estudante($id,$nome,$nomeusuario,$datanascimento,$operacao,$etapa,$rodada,$embaralhar){
        $this->id=intval($id);
        $this->nome=$nome;
        $this->nomeusuario=$nomeusuario;
        $this->datanascimento=$datanascimento;
        $this->operacao=intval($operacao);
        $this->etapa=intval($etapa);
        $this->rodada=intval($rodada);
        $this->embaralhar=$embaralhar;
    }

    public function get_id(){
        return $this->id;
    }

    public function get_nome(){
        return $this->nome;
    }

    public function get_twonames(){
      $names=explode(" ",$this->nome);
      $twonames=(isset($names[0])?$names[0]:"")." ".(isset($names[count($names)-1])?$names[count($names)-1]:"");
      return $twonames;
    }

    public function get_nomeusuario(){
        return $this->nomeusuario;
    }

    public function get_datanascimento(){
        $data=explode('-',$this->datanascimento);
        return $data[2].'/'.$data[1].'/'.$data[0];
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
}
?>
