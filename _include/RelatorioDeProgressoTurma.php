<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../_estilos/RelatorioTurma.css" media="all">
    <style>

      [class*='bar-level']{
        padding: 0;
        vertical-align: baseline;
        width: 15%;
      }

      [class*='bar-level'] div{
        height: 100%;
        display: inline-block;
        background: #2abb67;
        margin: 0;
      }

      .bar-level-12 div{
        width: 8.33%;
      }

      .bar-level-11 div{
        width: 9.09%;
      }

      .bar-level-12 div:last-child{
        width: 8.37%;
      }

      .bar-level-11 div:last-child{
        width: 9.1%;
      }

      [class*='bar-level'] div.strengh{
        background: #2980b9;
      }

      [class*='bar-level'] div.destaturma{
        border-bottom: solid 3px #f39c12;
      }

    </style>
</head>
<body>
<?php
    session_start();
    require_once "_cruds/daoTurmas.php";
    require_once "_classes/Estudante.php";
    require_once "_cruds/daoEstudante.php";
    require_once "_cruds/daoHistorico.php";
    require_once "_cruds/daoPartida.php";

    if(!isset($_SESSION['professor'])){
        echo 'Erro ao salvar a partida, faça login novamente!';
    }else{
        if(isset($_GET['idturma'])){
          $idturma=intval($_GET['idturma']);

          $turma=exibeDadosTurma($idturma);
          if($turma!=null){
            $table='<table border="1">
                  <thead>
                    <tr><th class="titulo" colspan="6">Turma: '.$turma['nome_turma'].' - APAECALC</th></tr>
                    <tr>
                      <th id="with-table" colspan="6">
                          <table id="tableinterna" width="100%">
                            <tr>
                                  <th width="33%">Id da Turma: '.$turma['id'].'</th>
                                  <th width="33%">Matriculas Vigentes: '.$turma['quant'].'</th>
                                  <th width="33%">Periodo: '.$turma['periodo'].'</th>
                            </tr>
                          </table>
                      </th>
                    </tr>
                    <tr>
                      <th>Nome</th>
                      <th>Adição</th>
                      <th>Subtração</th>
                      <th>Multiplicação</th>
                      <th>Divisão</th>
                      <th>Rodada</th>
                    </tr>
                  </thead>
                  <tbody>';
            $estudantes=getEstudanteByClass($idturma);
            if(count($estudantes)>0){
              for($i=0;$i<count($estudantes);$i+=1){
                $partidas=getPartidasByEstudanteAndRodada($estudantes[$i]->get_id(),$estudantes[$i]->get_rodada());
                $table.="<tr><td>".$estudantes[$i]->get_nome()."</td>";
                $indicePartida=0;
                for($operacao=1;$operacao<=4;$operacao+=1){
                  $max=11;
                  if($operacao==1||$operacao==3){
                    $max=12;
                    $table.="<td class='bar-level-12'>";
                  }else{
                    $table.="<td class='bar-level-11'>";
                  }
                  $fasesDestaOperacao=0;
                  for($j=$indicePartida;$j<count($partidas)&&$fasesDestaOperacao<$max;$j+=1){
                    $class="";
                    if($partidas[$j]["id_turma"]==$idturma){
                      $class="destaturma ";
                    }
                    if($partidas[$j]["embaralhado"]==1){
                      $class.="strengh";
                    }
                    $table.="<div class='".$class."'></div>";
                    $fasesDestaOperacao+=1;
                    $indicePartida+=1;
                  }
                  $table.="</td>";
                }
                $table.="<td>x".$estudantes[$i]->get_rodada()."</td></tr>";
              }
            }else{
              $table.='<tr><td colspan="6">Não existem estudantes matriculados nesta turma!</td><tr>';
            }
            /*if($atuais==1){
              $estudantes=getEstudanteByClass($idturma);
              $matriculas=getMatriculasVigentes($idturma);
              $table.=str_replace('{{tiposestudantes}}','Estudantes Com Matriculas Vigentes',$subcabecalho);
              if(count($estudantes)>0){
                for($i=0;$i<count($estudantes);$i+=1){
                  $table.=('<tr><td class="capitalize">'.$estudantes[$i]->get_nome().'</td>');
                  $table.=($nomeusuario==1?'<td>'.$estudantes[$i]->get_nomeusuario().'</td>':"");
                  $table.=($datanascimento==1?'<td>'.$estudantes[$i]->get_datanascimento().'</td>':"");
                  $table.=($deficiencia==1?'<td>'.ucfirst(strtolower($estudantes[$i]->get_nomedeficiencia())).'</td>':"");
                  $table.=($datasmatriculas==1?'<td>'.$matriculas[$i]['data_entrada'].'</td><td></td>':"");
                  $table.='</tr>';
                }
              }else{
                $table.='<tr><td colspan="'.$colunas.'">Não existem matrículas em vigência nesta turma!</td><tr>';
              }
            }
            if($antigas==1){
              $matriculas=getHistoricoTurma($idturma);
              $table.=str_replace('{{tiposestudantes}}','Estudantes Transferidos de Turma',$subcabecalho);
              if(count($matriculas)>0){
                for($i=0;$i<count($matriculas);$i+=1){
                  $table.=('<tr><td class="capitalize">'.$matriculas[$i]['nome'].'</td>');
                  $table.=($nomeusuario==1?'<td>'.$matriculas[$i]['nome_usuario'].'</td>':"");
                  $table.=($datanascimento==1?'<td>'.$matriculas[$i]['data_nascimento'].'</td>':"");
                  $table.=($deficiencia==1?'<td>'.ucfirst(strtolower($matriculas[$i]['nome_deficiencia'])).'</td>':"");
                  $table.=($datasmatriculas==1?'<td>'.$matriculas[$i]['data_entrada'].'</td><td>'.$matriculas[$i]['data_saida'].'</td>':"");
                  $table.='</tr>';
                }

            }*/

            $table.='</body></table>';

            echo $table;

          }else{
              echo 'Erro!';
          }
        }else{
          echo 'Erro!';
        }
    }
    date_default_timezone_set('America/Sao_Paulo');
?>
<p>Emitido em <?php echo date('d/m/Y').' às '.date('H:i A').' (Horário Oficial de Brasília)';?></p>
</body>
</html>
