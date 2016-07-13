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

  require_once "_include/_cruds/daoTurmas.php";
  require_once "_include/_classes/Estudante.php";
  require_once "_include/_cruds/daoEstudante.php";
  require_once "_include/_cruds/daoHistorico.php";
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
    <link rel="stylesheet" href="_estilos/RelatorioCompletoEstudante.css" media="all">
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
            if(isset($_GET['idestudante'])){
              $idestudante=intval($_GET['idestudante']);
              $historico=getHistoricoEstudante($idestudante);
              if($historico!=null){
                $estudante=getEstudanteById($idestudante);
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
                echo "</table></section><ol id='historicos'>";
                  for($i=0;$i<count($historico);$i+=1){
                    $partidas=getPartidasByHistorico($historico[$i]["id"]);
                    $line = "<li>
                            <input id='open-historico-".$i."' type='checkbox'>
                            <h1>
                              <label for='open-historico-".$i."' class='btn-open-historico'>
                                <span class='icon-right-open'></span>
                              </label>
                              <span>".$historico[$i]["nome_turma"]."</span>&nbsp-&nbsp
                              <span>
                                <span>Período: ".$historico[$i]["data_entrada"]."</span>
                                <span>".($historico[$i]["data_saida"]!=NULL?("&nbspà&nbsp".$historico[$i]["data_saida"]):"&nbspAté o momento")."</span>
                              </span>
                            </h1>
                            <table>
                              <thead>
                                <tr>
                                  <th>Operação</th>
                                  <th>Fase</th>
                                  <th>Modalidade</th>
                                  <th>Embaralhado</th>
                                  <th>Tempo Total</th>
                                  <th>Resultado</th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>";
                            if($partidas!=null){
                              $data="";
                              for($j=0;$j<count($partidas);$j+=1){
                                if($data!=$partidas[$j]["data_partida"]){
                                  $data=$partidas[$j]["data_partida"];
                                  $line.="<tr class='data'><td colspan='7'>Dia ".$partidas[$j]["data_partida"]."</td></tr>";
                                }
                                $icon="";
                                $conteudo="";
                                $tempo="";
                                if($partidas[$j]["vencida"]==="1"){
                                  $icon="icon-check";
                                  $tempo=gmdate("i:s",$partidas[$j]["tempototal"])." min";
                                }else{
                                  if($partidas[$j]["vencida"]==="0"){
                                    $icon="icon-cancel";
                                    $tempo=gmdate("i:s",$partidas[$j]["tempototal"])." min";
                                  }else{
                                    $conteudo="Inacabada";
                                    $tempo="--";
                                  }
                                }
                                $line.="<tr class='partida'>
                                    <td>".getNomeOperacao($partidas[$j]["operacao"])."</td>
                                    <td>".$partidas[$j]["etapa"]."</td>
                                    <td>".($partidas[$j]["carreira"]==1?"Carreira":"Livre")."</td>
                                    <td>".($partidas[$j]["embaralhado"]?"Sim":"Não")."</td>
                                    <td>".$tempo."</td>
                                    <td><span class=".$icon.">".$conteudo."</span></td>
                                    <td>
                                      <form style='display: inline-block' method='GET' target='_blank' action='RelatorioCompletoPartida.php'>
                                        <input type='hidden' name='idpartida' value='".$partidas[$j]['id']."'>
                                        <input type='hidden' name='idestudante' value='".$idestudante."'>
                                        <button class='btn-open-historico btn-open-partida'><span class='icon-level-down'></span></button>
                                      </form>
                                    </td>
                                  </tr>";
                              }
                              $line.="<tbody></table></li>";
                            }else{
                              $line.="<tbody></table></li>";
                            }

                          echo $line;
                  }
                echo "</ol>";

                date_default_timezone_set('America/Sao_Paulo');
                echo '<p>Emitido em '.date('d/m/Y').' às '.date('H:i A').' (Horário Oficial de Brasília)</p>';

              }else{
                echo '<p>Erro! O estudante não pôde ser encontrado!</p>
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
