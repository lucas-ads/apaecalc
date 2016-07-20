<?php
  require_once '_include/_classes/Teacher.php';
  require_once '_include/_cruds/daoTurmas.php';
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
    $nome=$professor->get_name();
    $nomes=explode(" ",$nome);
    $doisnomes=(isset($nomes[0])?$nomes[0]:"")." ".(isset($nomes[1])?$nomes[1]:"");
    $turmas=exibeDadosTurmas();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="_estilos/PaginaAdmin.css" charset="utf-8">
    <link rel="stylesheet" href="_fonts/entypo.css">
    <link rel="stylesheet" href="jquery-ui-1.11.4.custom/jquery-ui.min.css" media="screen" title="no title" charset="utf-8">
</head>
<body>
  <header id="cabecalho">
      <img src="_imagens/logo_apae.png" id="logoApae" alt="Logo Apae" />
      <h1><?php echo $doisnomes; ?></h1>
      <img src="_imagens/logo_ifms.png" id="logoIfms" alt="Logo IFMS">
  </header>
  <main>
    <div id='barra-superior'>
      <div id="local">
        <h2>Turmas</h2>
      </div>
      <div id="top-buttons">
        <button class="top-button" id="btn-cadastrarEstudante"><img src="_imagens/add-estudante.svg"/></button>
        <button class="btn-cadastrarTurma top-button"><img src="_imagens/add-turma.svg"/></button>
        <button class="top-button" id="btn-cadastrarAdministrador"><img src="_imagens/add-administrador.svg"/></button>
        <form class="top-button" action="deslogarProfessor.php" method="post">
            <button type="submit"><img src="_imagens/logout.svg" alt="" /></button>
        </form>
      </div>
    </div>
    <table id="tableTurmas">
        <thead>
          <tr>
            <th>Turma</th>
            <th>Periodo</th>
            <th>Estudantes</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php
          if(count($turmas)!=0){
            for($i=0;$i<count($turmas);$i+=1){
              $disabled=$turmas[$i][4]==0?"":"disabled";
              echo '<tr value="'.$turmas[$i][0].'">
                      <td>'.$turmas[$i][1].'</td>
                      <td>'.$turmas[$i][2].'</td>
                      <td>'.$turmas[$i][3].'</td>
                      <td>
                        <button class="btn-excluirturma btn-actionturma" '.$disabled.'>
                          <span class="icon-cancel"></span>
                        </button>
                        <button class="btn-editarturma btn-actionturma">
                          <img src="_imagens/icone-editar.png" alt="Editar"/>
                        </button>
                        <button class="btn-actionturma btn-print">
                          <span class="icon-print"></span>
                        </button>
                        <form style="display: inline-block" method="GET" target="_blank" action="RelatorioDeProgressoTurma.php">
                          <button class="btn-actionturma">
                            <input name="idturma" type="hidden" value="'.$turmas[$i][0].'">
                            <span class="icon-chart-line"></span>
                          </button>
                        </form>
                        <button class="btn-entrar btn-actionturma">
                          <span class="icon-right-open"></span>
                        </button>
                      </td>
                    </tr>';
            }
          }else{
            echo '<tr><td colspan="4">Não existem turmas cadastradas!</td><tr>';
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
              <select id="select-deficiencia">

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
              <select id="select-turmas">

              </select>
              <button type="button" name="button" id="btn-cadTurma" class="btn-cadastrarTurma">+</button>
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
  <div id="cadastroAdministrador" class="form">
      <div class="foco">
          <h1>Cadastrar Administrador</h1>
          <div id="formCadAdministrador">
            <div class="">
              <label for="nome-admin">*Nome:</label>
              <input type="text" id="nome-admin" placeholder="Ex. João da Silva">
            </div>
            <div class="">
              <label for="nomeusuario-admin">*Nome de Usuário:</label>
              <input type="text" id="nomeusuario-admin" placeholder="Ex. joao-silva">
            </div>
            <div class="">
              <label for="password-admin">*Senha:</label>
              <input type="password" id="password-admin" placeholder="Mínimo: 6 caracteres">
            </div>
            <div class="">
              <label for="confirm-password-admin">*Confirme a senha:</label>
              <input type="password" id="confirm-password-admin" placeholder="Repita a senha">
            </div>
            <output> </output>
            <button id="bt1">Cancelar</button>
            <button id="bt2">Cadastrar</button>
          </div>
      </div>
  </div>
  <div id="cadastroTurma" class="form">
    <div class="foco">
        <h1>Cadastrar Turma</h1>
        <div id="formCadTurma">
          <div class="">
            <label for="nometurma">*Turma:</label>
            <input type="text" id="nometurma" placeholder="Ex. 3º ANO - B" required>
          </div>
          <div class="div-periodo">
            <label for="periodoturma">Período:</label>
            <input type="text" id="periodoturma">
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
  <div id="exclusaoTurma" class="form">
    <div class="foco">
        <h1></h1>
        <div id="formCadDeficiencia">
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
  <script id="template-linhaturma" type="text/template">
    <tr value="{{idturma}}">
      <td>{{nometurma}}</td>
      <td>{{periodoturma}}</td>
      <td>0</td>
      <td>
        <button class="btn-excluirturma btn-actionturma">
          <span class="icon-cancel"></span>
        </button>
        <button class="btn-editarturma btn-actionturma">
          <img src="_imagens/icone-editar.png" alt="Editar"/>
        </button>
        <button class="btn-actionturma btn-print">
          <span class="icon-print"></span>
        </button>
        <form style="display: inline-block" method="GET" target="_blank" action="_include/RelatorioDeProgressoTurma.php">
          <button class="btn-actionturma">
            <input name="idturma" type="hidden" value="{{idturma}}">
            <span class="icon-chart-line"></span>
          </button>
        </form>
        <button class="btn-entrar btn-actionturma">
          <span class="icon-right-open"></span>
        </button>
      </td>
    </tr>
  </script>
  <script src="_js/jquery-2.1.4.min.js"></script>
  <script src="jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
  <script src="_js/paginaAdmin.js"></script>
</body>
</html>
<?php } ?>
