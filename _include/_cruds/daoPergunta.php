<?php
    require_once "ConnectionDatabase.php";

    function salvarPerguntas($idPartida,$values){
        $comando="insert into pergunta (id_partida, primeiro_valor, segundo_valor) values ";
        $sqlValues="";

        for($i=0;$i<count($values);$i++){
            if(gettype($values[$i][0])=="integer"&&gettype($values[$i][1])=="integer"){
                $sqlValues .= '('.$idPartida.','.$values[$i][0].','.$values[$i][1].'),';
            }
        }

        if($sqlValues!=""){
            $sqlValues=$comando.substr($sqlValues,0,-1).';';
            $conexao = conectar();
            mysqli_query($conexao,$sqlValues);

            $sqlGetIds="select id from pergunta where id_partida=".$idPartida.";";
            $resultset=mysqli_query($conexao,$sqlGetIds);

            $ids=[];
            while($row=mysqli_fetch_assoc($resultset)){
                $ids[]=$row["id"];
            }
            return $ids;
        }

        return -1;

    }
?>
