<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="_estilos/RelatorioTurma.css" media="all">
</head>
<body>
<?php
    session_start();
    require_once "_include/_cruds/daoTurmas.php";
    require_once "_include/_classes/Estudante.php";
    require_once "_include/_cruds/daoEstudante.php";
    require_once "_include/_cruds/daoHistorico.php";

    if(!isset($_SESSION['professor'])){
      echo '<p>Sessão expirada, faça login novamente!</p>
            <form method="get" action="index.php">
              <input type="submit" value="Fazer Login Novamente">
            </form>';
    }else{
        $inactive=900;
        if(isset($_SESSION['timeout'])){
          $session_life = time() - $_SESSION['timeout'];
          if($session_life > $inactive){
            session_destroy();
            header("Location: deslogarProfessor.php");
          }else{
              $_SESSION['timeout'] = time();
          }
        }else{
          $_SESSION['timeout'] = time();
        }
        if(isset($_GET['idturma'])&&(isset($_GET['matriculasatuais'])||isset($_GET['matriculasantigas']))){
          $idturma=intval($_GET['idturma']);
          $atuais=isset($_GET['matriculasatuais'])?1:0;
          $antigas=isset($_GET['matriculasantigas'])?1:0;
          $nomeusuario=isset($_GET['nomeusuario'])?1:0;
          $datanascimento=isset($_GET['datanascimento'])?1:0;
          $deficiencia=isset($_GET['deficiencia'])?1:0;
          $datasmatriculas=isset($_GET['datasmatriculas'])?1:0;

          $colunas=1+$nomeusuario+$datanascimento+$deficiencia+($datasmatriculas==1?2:0);
          if($colunas<3){
            $colunas=3;
          }

          $turma=exibeDadosTurma($idturma);
          if($turma!=null){
            $subcabecalho='
              <tr><th colspan="'.$colunas.'">{{tiposestudantes}}</th></tr>
              <tr>
                <th>Nome:</th>'.
                ($nomeusuario==1?"<th>Nome de Usuário:</th>":"").
                ($datanascimento==1?"<th>Data de Nascimento:</th>":"").
                ($deficiencia==1?"<th>Deficiência:</th>":"").
                ($datasmatriculas==1?"<th>Matriculado em:</th><th>Transferido em:</th>":"").
              '</tr>';

            $table='<table border="1">
                  <thead>
                    <tr><th class="titulo" colspan="'.$colunas.'">Turma: '.$turma['nome_turma'].' - APAECALC</th></tr>
                    <tr>
                      <th id="with-table" colspan="'.$colunas.'">
                          <table id="tableinterna" width="100%">
                            <tr>
                                  <th width="33%">Id da Turma: '.$turma['id'].'</th>
                                  <th width="33%">Matriculas Vigentes: '.$turma['quant'].'</th>
                                  <th width="33%">Periodo: '.$turma['periodo'].'</th>
                            </tr>
                          </table>
                      </th>
                    </tr>
                  </thead>
                  <tbody>';

            if($atuais==1){
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
              }else{
                $table.='<tr><td colspan="'.$colunas.'">Não existem historicos de estudantes transferidos nesta turma!</td><tr>';
              }
            }

            $table.='</body></table>';

            if($colunas>4||($colunas>3&&$deficiencia==1)){
              echo "<style>
                @page {size:landscape;}
              </style>";
            }

            echo $table;
            date_default_timezone_set('America/Sao_Paulo');
            echo '<p>Emitido em '.date('d/m/Y').' às '.date('H:i A').' (Horário Oficial de Brasília)</p>';

          }else{
            echo '<p>Erro! A turma não pôde ser encontrada!</p>
                  <input type="submit" value="Fechar" onclick="window.close();">';
          }
        }else{
          echo '<p>Erro! Os dados necessários não foram informados!</p>
                <input type="submit" value="Fechar" onclick="window.close();">';
        }
    }
?>
</body>
</html>
