<?php
  require_once '_include/_classes/Teacher.php';
  require_once '_include/_classes/Estudante.php';
  require_once '_include/_cruds/daoTurmas.php';
  require_once '_include/_cruds/daoEstudante.php';
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

    if(isset($_GET['idturma'])){
      $professor=$_SESSION['professor'];
      $nome=$professor->get_twofirstname();
      $idturma=$_GET['idturma'];
      $turma=carregaTurma($idturma);
      $estudantes=getEstudanteByClass($idturma);
      $nometurma=('/'.$turma['nome_turma'].($turma['periodo']==""?"":" - ").$turma['periodo']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="_estilos/PaginaTurma.css" charset="utf-8">
    <link rel="stylesheet" href="_fonts/entypo.css">
    <link rel="stylesheet" href="jquery-ui-1.11.4.custom/jquery-ui.min.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
  <header id="cabecalho">
      <img src="_imagens/logo_apae.png" id="logoApae" alt="Logo Apae" />
      <h1><?php echo $nome; ?></h1>
      <img src="_imagens/logo_ifms.png" id="logoIfms" alt="Logo IFMS">
  </header>
  <main>
    <div id='barra-superior'>
      <div id="local">
        <a href="Admin.php">Turmas</a><h2><?php echo $nometurma;?></h2>
      </div>
      <div id="top-buttons">
        <button class="top-button" id="btn-cadastrarEstudante"><img src="_imagens/add-estudante.svg"/></button>
        <button id="btn-transferir" name="name" class="top-button disabled"><img src="_imagens/transferir-estudantes.svg" alt="" /></button>
        <button class="top-button btn-print" id="<?php echo $idturma; ?>"><img src="_imagens/gerar-relacao-estudantes.svg" alt="" /></button>
        <form class="top-button" method="GET" target="_blank" action="RelatorioDeProgressoTurma.php">
          <input name="idturma" type="hidden" value="<?php echo $idturma; ?>">
          <button type='submit'><img src="_imagens/relatorio-progresso-turma.svg" alt="" /></button>
        </form>
        <form class="top-button deslogar" action="deslogarProfessor.php" method="post">
            <button type="submit"><img src="_imagens/logout.svg" alt="" /></button>
        </form>
      </div>
    </div>
    <table id="tableEstudantes">
        <thead>
          <tr>
            <th></th>
            <th>Nome</th>
            <th>Usuário</th>
            <th>Nascimento</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          if(count($estudantes)>0){
            for($i=0;$i<count($estudantes);$i+=1){
              echo '<tr value="'.$estudantes[$i]->get_id().'">
                      <td class="td-check"><input class="check-estudante" type="checkbox"></td>
                      <td class="td-nome">'.$estudantes[$i]->get_nomeabrev().'</td>
                      <td class="td-nomeusuario">'.$estudantes[$i]->get_nomeusuario().'</td>
                      <td class="td-datanascimento">'.$estudantes[$i]->get_datanascimento().'</td>
                      <td>
                      <form style="display: inline-block" method="GET" target="_blank" action="RelatorioCompletoEstudante.php">
                        <button class="btn-detalhesestudante btn-actionestudante">
                          <input name="idestudante" type="hidden" value="'.$estudantes[$i]->get_id().'">
                          <span class="icon-vcard"></span>
                        </button>
                      </form>
                      <button class="btn-editarestudante btn-actionestudante">
                        <img src="_imagens/icone-editar.svg" alt="Editar"/>
                      </button>
                      </td>
                    </tr>';
            }
          }else{
            echo '<tr><td colspan="4">Não existem estudantes nesta turma!</td><tr>';
          }
          ?>
        </tbody>
      </table>
  </main>
  <div id="testes">

  </div>
  <div id="cadastroEstudante" class="form">
      <div class="foco">
          <h1>Cadastrar Estudante</h1>
          <div id="formCadEstudante">
            <div class="">
              <label for="nome">*Nome:</label>
              <input type="text" id="nome" placeholder="Ex. João da Silva">
            </div>
            <div class="">
              <label for="nomeusuario">*Nome de Usuário:</label>
              <input type="text" id="nomeusuario" placeholder="Ex. joao-silva">
            </div>
            <div class="">
              <label for="dataNascimento">*Data de Nascimento:</label>
              <input type="text" id="dataNascimento" placeholder="Ex. 10/05/2005">
            </div>
            <div class="">
              <label for="">*Possui Deficiência?</label>
              <select id="select-deficiencia" class="select-deficiencia">

              </select>
              <button type="button" name="button" id="btn-cadDeficiencia" class="btn-cadDeficiencia">+</button>
            </div>
            <div class="div-observacao">
              <label for="observacao">Observação:</label>
              <textarea id="observacao" rows="1" cols="40"></textarea>
            </div>
            <div class="">
              <label>*Embaralhar questões?</label>
              <div class="radio-embaralhar">
                <input type="radio" name="radio-embaralhar" id="radio-embaralhar" value="1">
                <label for="radio-embaralhar">Sim</label>
                <input type="radio" name="radio-embaralhar" id="radio-noembaralhar" value="0" checked>
                <label for="radio-noembaralhar">Não</label>
              </div>
            </div>
            <div class="">
              <label for="">*Selecione a Turma:</label>
              <select id="select-turmas" disabled style="color: #333;">
                <option value="<?php echo $idturma; ?>"><?php echo $turma['nome_turma']; ?></option>
              </select>
            </div>
            <div class="">
              <label for="password">*Senha:</label>
              <input type="password" id="password" placeholder="Mínimo: 6 caracteres">
            </div>
            <div class="">
              <label for="confirm-password">*Confirme a senha:</label>
              <input type="password" id="confirm-password" placeholder="Repita a senha">
            </div>
            <output> </output>
            <button id="bt1">Cancelar</button>
            <button id="bt2">Cadastrar</button>
          </div>
      </div>
  </div>
  <div id="edicaoDadosGerais" class="form">
    <input type="hidden" id="idEstudanteEdicao">
    <div class="foco">
        <h1>Atualizar Informações Gerais</h1>
        <div id="formCadDadosGerais">
          <div class="">
            <label for="edicaonome">*Nome:</label>
            <input type="text" id="edicaonome" placeholder="Ex. João da Silva">
          </div>
          <div class="">
            <label for="edicaonomeusuario">*Nome de Usuário:</label>
            <input type="text" id="edicaonomeusuario" placeholder="Ex. joao-silva">
          </div>
          <div class="">
            <label for="edicaodataNascimento">*Data de Nascimento:</label>
            <input type="text" id="edicaodataNascimento" placeholder="Ex. 10/05/2005">
          </div>
          <div class="">
            <label for="">*Possui Deficiência?</label>
            <select id="edicaoselect-deficiencia" class="select-deficiencia">

            </select>
            <button type="button" name="button" id="btn-cadDeficiencia" class="btn-cadDeficiencia">+</button>
          </div>
          <div class="div-observacao">
            <label for="edicaoobservacao">Observação:</label>
            <textarea id="edicaoobservacao" rows="1" cols="40"></textarea>
          </div>
          <div class="">
            <label>*Embaralhar questões?</label>
            <div class="radio-embaralhar">
              <input type="radio" name="edicaoradio-embaralhar" id="edicaoradio-embaralhar" value="1">
              <label for="edicaoradio-embaralhar">Sim</label>
              <input type="radio" name="edicaoradio-embaralhar" id="edicaoradio-noembaralhar" value="0" checked>
              <label for="edicaoradio-noembaralhar">Não</label>
            </div>
          </div>
          <input type="checkbox" id="check-alterarsenha">
          <label for="check-alterarsenha">Alterar senha</label>
          <div class="edicaosenha">
              <label for="edicaopassword">*Senha:</label>
              <input type="password" id="edicaopassword" placeholder="Mínimo: 6 caracteres">
          </div>
          <div class="edicaosenha">
              <label for="edicaoconfirm-password">*Confirme a senha:</label>
              <input type="password" id="edicaoconfirm-password" placeholder="Repita a senha">
          </div>
          <output> </output>
          <button id="bt1">Cancelar</button>
          <button id="bt2">Cadastrar</button>
        </div>
    </div>
  </div>
  <div id="cadastroDeficiencia" class="form">
    <div class="foco">
        <h1>Cadastrar Deficiência</h1>
        <div id="formCadDeficiencia">
          <div class="">
            <label for="nomedeficiencia">*Deficiência:</label>
            <input type="text" id="nomedeficiencia" required>
          </div>
          <output> </output>
          <button id="bt1">Cancelar</button>
          <button id="bt2">Cadastrar</button>
        </div>
    </div>
  </div>
  <div id="transferenciaEstudantes" class="form">
    <div class="foco">
        <h1>Transferir de Turma</h1>
        <div id="formTransferenciaEstudantes">
          <div class="" id="estudantesSelecionados">
            <label for="">Estudantes Selecionados:</label>
            <h2></h2>
          </div>
          <div class="" id="turmaatual">
            <label>Turma Atual:</label>
            <h2><?php echo $turma['nome_turma']; ?></h2>
          </div>
          <div class="">
            <label for="">*Turma de Destino:</label>
            <select id="select-turmas">

            </select>
          </div>
          <output> </output>
          <button id="bt1">Cancelar</button>
          <button id="bt2">Cadastrar</button>
        </div>
    </div>
  </div>
  <div id="relatorioTurma" class="form">
    <div class="foco">
        <h1></h1>
        <form target="_blank" method="GET" action="RelatorioDeTurma.php" id="formRelatorioTurma">
          <h2>Este relatorio deve conter:</h2>
          <div class="">
            <input type="checkbox" name="matriculasatuais" id="check-matriculasatuais" checked>
            <label for="check-matriculasatuais">*os estudantes atualmente matriculados nesta turma</label>
          </div>
          <div class="">
            <input type="checkbox" name="matriculasantigas" id="check-matriculasantigas">
            <label for="check-matriculasantigas">*os estudantes que já passaram por esta turma</label>
          </div>
          <div class="">
            <input type="checkbox" name="nomeusuario" id="check-nomeusuario">
            <label for="check-nomeusuario">os nomes de usuário dos estudantes</label>
          </div>
          <div class="">
            <input type="checkbox" name="datanascimento" id="check-datanascimento" checked>
            <label for="check-datanascimento">as datas de nascimento dos estudantes</label>
          </div>
          <div class="">
            <input type="checkbox" name="deficiencia" id="check-deficiencia">
            <label for="check-deficiencia">a deficiência que cada estudante possui</label>
          </div>
          <div class="">
            <input type="checkbox" name="datasmatriculas" id="check-datasmatriculas" checked>
            <label for="check-datasmatriculas">as datas de entrada e saída da turma atual</label>
          </div>
          <input type="hidden" id="idturma" name="idturma">
          <output> </output>
          <button type="button" id="bt1">Cancelar</button>
          <button id="bt2">Cadastrar</button>
        </div>
    </form>
  </div>
  <script id="template-linhaestudante" type="text/template">
    <tr value="{{idestudante}}">
      <td><input type="checkbox"></td>
      <td>{{nomeestudante}}</td>
      <td>{{nomeusuario}}</td>
      <td>{{datadenascimento}}</td>
      <td>
          <button class="btn-editarestudante btn-actionestudante">
          <img src="_imagens/icone-editar.png" alt="Editar"/>
          </button>
      </td>
    </tr>
  </script>
  <script src="_js/jquery-2.1.4.min.js"></script>
  <script src="jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
  <script src="_js/paginaTurma.js"></script>
</body>
</html>
<?php }} ?>
