<?php
//Conecta ao banco de dados
//Retorna a conexão se a conexao for feita com sucesso ou null para sem sucesso
function conectar(){
    $conexao = mysqli_connect("localhost","root","root","apaemat") or die('Erro na conexão com o banco de dados!');
    if($conexao){
        return $conexao;
    }else{
        return null;
    }
}
//Desconecta do banco de dados
function desconectar($conexao){
    if($conexao){
        mysqli_close($conexao);
    }
}
//Função para impedir SQL Injector
function normalizarAspas($dados){
    if(is_array($dados)){
        foreach($dados as $chave=>$valor){
            $dados[$chave]=addslashes($valor);
        }
        return $dados;
    }else{
        return addslashes($dados);
    }
}
?>
