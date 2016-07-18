<?php
  function getNomeOperacao($code){
    $code=intval($code);
    if($code==1)
      return "Adição";
    if($code==2)
      return "Subtração";
    if($code==3)
      return "Multiplicação";
    if($code==4)
      return "Divisão";
  }

  function getSymbolOperacao($code){
    $code=intval($code);
    if($code==1)
      return " + ";
    if($code==2)
      return " - ";
    if($code==3)
      return " X ";
    if($code==4)
      return " / ";
  }

  require_once "_include/_cruds/daoTurmas.php";
  require_once "_include/_classes/Estudante.php";
  require_once "_include/_cruds/daoEstudante.php";
  require_once "_include/_cruds/daoHistorico.php";
  require_once "_include/_cruds/daoPergunta.php";
  require_once "_include/_cruds/daoPartida.php";
  require_once "_include/_classes/Teacher.php";
  session_start();

  if(!isset($_SESSION['professor'])){
      header("Location:index.php");
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

    $professor=$_SESSION['professor'];
    $nome=$professor->get_twofirstname();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="_estilos/RelatorioCompletoPartida.css" media="all">
    <link rel="stylesheet" href="_fonts/entypo.css">
</head>
<body>
  <header id="cabecalho">
      <img src="_imagens/logo_apae.png" id="logoApae" alt="Logo Apae" />
      <h1><?php echo $nome; ?></h1>
      <img src="_imagens/logo_ifms.png" id="logoIfms" alt="Logo IFMS">
  </header>
  <main>
    <?php
            if(isset($_GET['idestudante'])&&isset($_GET['idpartida'])){
              $idestudante=intval($_GET['idestudante']);
              $idpartida=intval($_GET['idpartida']);
              $estudante=getEstudanteById($idestudante);
              $perguntas=getPerguntasRespostas($idpartida);
              $partida=getPartidaById($idpartida);
              if($estudante!=null&&$perguntas!=null){
                echo "<section id='info-estudante'><table>";
                echo "<tr><td rowspan='3' id='nome-estudante'>".$estudante->get_nome()."</td>";
                echo "<td class='col2'>Nome de Usuário: ".$estudante->get_nomeusuario()."</td>";
                echo "<td class='col3 deficiencia' rowspan='2'>Deficiência: ".$estudante->get_nomedeficiencia()."</td></tr>";
                echo "<tr><td class='col2'>Data de Nascimento: ".$estudante->get_datanascimento()."</td></tr>";
                $turma=carregaTurma($estudante->get_turmaatual());
                echo "<tr><td class='col2'>Turma Atual: ".$turma["nome_turma"]."</td><td class='col3'>Modo de Jogo: ".($estudante->get_embaralhar()==1?"Embaralhado":"Normal")."</td></tr>";
                $obs=$estudante->get_observacao();
                if($obs!=""){
                  echo "<tr><td class='observacao' colspan='3'>Observação: ".$obs."</td></tr>";
                }
                echo "</table></section>";
                echo "<table id='tablerespostas'>
                <thead>
                  <tr>
                    <th>Pergunta</th>
                    <th>Resposta</th>
                    <th>Tempo</th>
                    <th>Correção</th>
                  </tr>
                </thead>
                <tbody>";
                  $pergunta="";
                  $count=0;
                  $line="";
                  $class=0;
                  for($i=0;$i<count($perguntas);$i+=1){
                    $line.= "<tr class='color{{color}}'>";
                    if($pergunta!=$perguntas[$i]["id"]){
                      $class+=1;
                      $class=$class%2;
                      $line=str_replace("{{here}}",$count,$line);
                      $line=str_replace("{{color}}",$class,$line);
                      echo $line;
                      $pergunta=$perguntas[$i]["id"];
                      $count=1;
                      $line= "<td class='tdpergunta' rowspan='{{here}}'>".$perguntas[$i]["primeiro_valor"].getSymbolOperacao($partida["operacao"]).$perguntas[$i]["segundo_valor"]."?</td>";
                    }else{
                      $line=str_replace("{{color}}",$class,$line);
                      $count+=1;
                    }
                    $resposta="--";
                    $data="--";
                    $correcao="--";
                    $classcorrecao="";
                    if($perguntas[$i]["valor_resposta"]!=null){
                      $resposta=$perguntas[$i]["valor_resposta"];
                      $data=gmdate("i:s",$perguntas[$i]["tempo_gasto"])." min";
                      $correcao="";
                      $classcorrecao=($perguntas[$i]["correta"]==0?"icon-cancel":"icon-check");
                    }
                    $line.= "<td>".$resposta."</td>
                            <td>".$data."</td>
                            <td><span class='".$classcorrecao."'>".$correcao."</span</td>";
                    $line.= "</tr>";
                  }
                  $line=str_replace("{{here}}",$count,$line);
                  echo $line;
                echo "</tbody></table>";
                date_default_timezone_set('America/Sao_Paulo');
                echo '<p class="dadosemissao">Emitido em '.date('d/m/Y').' às '.date('H:i A').' (Horário Oficial de Brasília)</p>';

              }else{
                echo '<p>Erro! O estudante e/ou a partida não poderam ser encontrados!</p>
                      <input type="submit" value="Fechar" onclick="window.close();">';
              }
            }else{
              echo '<p>Erro! Os dados necessários não foram informados!</p>
                    <input type="submit" value="Fechar" onclick="window.close();">';
            }
        }
    ?>
  </main>
</body>
</html>
