<?php
  require_once '_include/_classes/Teacher.php';
  require_once '_include/_classes/Estudante.php';
  require_once '_include/_cruds/daoTurmas.php';
  require_once '_include/_cruds/daoEstudante.php';
  session_start();

  if(!isset($_SESSION['professor'])){
      header("Location:index.php");
  }else{
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
    <div id="local">
      <a href="Admin.php">Turmas</a><h2><?php echo $nometurma;?></h2>
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
                      <td><input type="checkbox"></td>
                      <td>'.$estudantes[$i]->get_nomeabrev().'</td>
                      <td>'.$estudantes[$i]->get_nomeusuario().'</td>
                      <td>'.$estudantes[$i]->get_datanascimento().'</td>
                      <td>
                      <button class="btn-editarestudante btn-actionestudante">
                        <img src="_imagens/icone-editar.png" alt="Editar"/>
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
      <form action="deslogarProfessor.php" method="post">
          <button type="submit">Sair</button>
      </form>
      <button id="btn-cadastrarEstudante">Cadastrar Estudante</button>
      <button class="btn-cadastrarTurma">Cadastrar Turma</button>
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
