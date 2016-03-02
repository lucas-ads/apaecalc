<?php
    require_once "ConnectionDatabase.php";

    function salvarResposta($idPergunta,$valor,$correta,$tempo){
        $conexao = conectar();
        $valor=intval($valor);
        $tempo=intval($tempo);
        $idPergunta=intval($idPergunta);

        if($idPergunta>0&&$valor>=0&&$tempo>=0){
            $stmt=$conexao->prepare("insert into resposta (id_pergunta, valor_resposta, correta,tempo_gasto) values(?,?,?,?)");
            $stmt->bind_param("iiii",$idPergunta,$valor,$correta,$tempo);
            $stmt->execute();

            return 1;
        }
        return -1;
    }
?>
