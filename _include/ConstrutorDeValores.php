<?php
/*--------------------------------------- 1 - Construtores de valores -------------------------------*/
/*--------------------------------------- Funções Utilitárias -------------------------------------*/
function existeRepeticao($values,$n1,$n2){
    for($i=0;$i<count($values);$i+=1){
        if($values[$i][0]==$n1 && $values[$i][1]==$n2){
            return true;
        }
    }
    return false;
}

function verificarCondicoes($operacao,$tipo,$n1,$n2){
    if($operacao==1){
        $soma=$n1+$n2;
        if(($tipo==1&&$soma<11)||($tipo==2&&$soma>10)){
            return true;
        }
    }
    if($operacao==2){
        if($n1>=$n2){
            return true;
        }
    }
    if($operacao==3){
        if(($tipo==1&&$n1<6)||($tipo==2&&$n1>5)){
            return true;
        }
    }
    if($operacao==4){
        if($n2!=0&&($n1%$n2==0)){
            return true;
        }
    }
    return false;
}

function buildValuesAleatory($operacao,$tipo){
    $values=[];
    for($i=0;$i<11;$i+=1){
        if($operacao==4){
          $n1 = rand(0,20);
        }else{
          $n1 = rand(0,10);
        }

        $n2 = rand(0,10);

        if(!existeRepeticao($values,$n1,$n2)){
            if(verificarCondicoes($operacao,$tipo,$n1,$n2)==true){
                $values[$i]=array($n1,$n2);
            }else{
                $i--;
            }
        }else{
            $i--;
        }
    }
    return $values;
}

function embaralharValores($valores){
    $quantLinhas=count($valores);
    $valoresEmbaralhados=[];
    for($i=0;$i<$quantLinhas-1;$i+=1){
        $indiceSorteado=rand(0,count($valores)-2);
        $valoresEmbaralhados[$i]=$valores[$indiceSorteado];
        array_splice($valores,$indiceSorteado,1);
    }
    $valoresEmbaralhados[$quantLinhas-1]=$valores[0];
    return $valoresEmbaralhados;
}

/*################################## Fim Funções Utilitárias ##########################################*/
/*---------------------------------- Funções para Adição e Multiplicação ------------------------------*/

function buildValuesDefault($etapa){
    $values=[];
    for($i=0;$i<=10;$i+=1){
        $values[$i]=array($etapa,$i);
    }
    $values[]=array($etapa,rand(0,10));
    return $values;
}

function getValuesSoma($etapa){
    $values;
    if($etapa<11){
        $values=buildValuesDefault($etapa);
    }else{
        $etapa=$etapa-10;
        $values=buildValuesAleatory(1,$etapa);
    }

    for($i=0;$i<count($values);$i+=1){
        $values[$i][2]=$values[$i][0]+$values[$i][1];
    }

    return $values;
}

function getValuesMultiplicacao($etapa){
    $values;
    if($etapa<11){
        $values=buildValuesDefault($etapa);
    }else{
        $etapa=$etapa-10;
        $values=buildValuesAleatory(3,$etapa);
    }

    for($i=0;$i<count($values);$i+=1){
        $values[$i][2]=$values[$i][0]*$values[$i][1];
    }

    return $values;
}
/*####################################### Fim Funções para Adição e Multiplicação ##############################*/
/*---------------------------------- Funções para Subtração e Divisão ------------------------------------------*/
function getValuesSubtracao($etapa){
    $values=[];
    if($etapa<11){
        for($i=0;$i<=$etapa;$i+=1){
            $values[$i]=array($etapa,$i);
        }
        $numeroAleatoreo=0;
        do{
            $numeroAleatoreo=rand(0,10);
        }while(!verificarCondicoes(2,0,$etapa,$numeroAleatoreo));
        $values[]=array($etapa,$numeroAleatoreo);
    }else{
        $values=buildValuesAleatory(2,0);
    }

    for($i=0;$i<count($values);$i+=1){
        $values[$i][2]=$values[$i][0]-$values[$i][1];
    }

    return $values;
}

function getValuesDivisao($etapa){
    $values=[];
    if($etapa<11){
        for($i=1;$i<=20;$i+=1){
            if($i%$etapa==0){
                $values[]=array($i,$etapa);
            }
        }
        $numeroAleatoreo=0;
        do{
            $numeroAleatoreo=rand($etapa,20);
        }while(!verificarCondicoes(4,0,$numeroAleatoreo,$etapa));
        $values[]=array($numeroAleatoreo,$etapa);
    }else{
        $values=buildValuesAleatory(4,0);
    }

    for($i=0;$i<count($values);$i+=1){
        $values[$i][2]=$values[$i][0]/$values[$i][1];
    }

    return $values;
}
/*####################################### Fim Funções para Subtração e Divisão ##############################*/
function getValues($operacao,$etapa,$embaralhar){
    $valores=0;
    if($operacao==1)
        $valores = getValuesSoma($etapa);
    if($operacao==2)
        $valores = getValuesSubtracao($etapa);
    if($operacao==3)
        $valores = getValuesMultiplicacao($etapa);
    if($operacao==4)
        $valores = getValuesDivisao($etapa);

    if($embaralhar){
        $valores=embaralharValores($valores);
    }
    return $valores;
}

/*######################################## 1 - Fim Construtores de valores ########################################*/
?>
