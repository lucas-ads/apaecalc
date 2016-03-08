<?php
    require_once '_include/_classes/Estudante.php';
    session_start();

    if(!isset($_SESSION['estudante'])){
        header("Location:index.php");
    }else{
        $inactive=9000;
        if(isset($_SESSION['timeout'])){
          $session_life = time() - $_SESSION['timeout'];
          if($session_life > $inactive){
            session_destroy();
            header("Location: deslogarEstudante.php");
          }else{
              $_SESSION['timeout'] = time();
          }
        }else{
          $_SESSION['timeout'] = time();
        }

        if(isset($_SESSION['partida'])){
            unset($_SESSION['partida']);
        }
        $estudante=$_SESSION['estudante'];
        $etapa=$estudante->get_etapa();
        $operacao=$estudante->get_operacao();
        $nomeoperacao=$estudante->get_nomeoperacao();

        $nomes=explode(" ",utf8_encode($estudante->get_nome()));
        $doisnomes=(isset($nomes[0])?$nomes[0]:"")." ".(isset($nomes[1])?$nomes[1]:"");
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="_estilos/PaginaInicial.css">
    <link rel="stylesheet" href="_fonts/entypo.css">
</head>
<body>
    <header id="cabecalho">
        <img src="_imagens/logo_apae.png" id="logoApae" alt="Logo Apae" />
        <h1><?php echo $doisnomes; ?></h1>
        <img src="_imagens/logo_ifms.png" id="logoIfms" alt="Logo IFMS">
    </header>
    <main>
        <div id="home">
           <div>
                <div id="carreira" class="foco">
                    <h1>Modo Sequencial</h1>
                    <h2 id="capitulo" value="<?php echo $operacao;?>">Estágio: <span><?php echo $nomeoperacao?></span></h2>
                    <h2 id="estagio" value="<?php echo $etapa;?>">Fase: <span><?php echo $etapa;?></span></h2>
                    <button id="continuarJogo">Continuar jogo</button>
                </div>
                <div id="arcade" class="foco">
                    <h1>Modo Livre</h1>
                    <div id="formModoLivre">
                        <div id="selectsModoLivre">
                            <select name="operacao" id="operacao">
                                <option value="1">Soma</option>
                                <option value="2">Subtração</option>
                                <option value="3">Multiplicação</option>
                                <option value="4">Divisão</option>
                            </select>
                            <select name="comboEstagio" id="comboEstagio">

                            </select>
                        </div>
                        <div id="checkEmbaralhar">
                            <input type="checkbox" id="embaralhar">
                            <label class="label-checkbox" for="embaralhar">Embaralhar</label>
                        </div>
                    </div>
                    <button id="jogarEstagio">Jogar</button>
                </div>
            </div>
            <form id="formLogout" action="deslogarEstudante.php" method="post">
                <button class="icon-logout foco"></button>
            </form>
        </div>
        <div id="game">
            <h1 id="titlefase">
                <span id="caption" value="1"></span>:
                <span id="stage" value="1"></span>
            </h1>
            <button id="btResetar" class="icon-cw foco"></button>
            <div id="container" class="foco">
                <div>
                    <div class="side left"></div>
                    <div class="side right"></div>
                </div>
            </div>
            <button class="foco icon-left-thin" id="voltarMenu"></button>
            <button class="foco" id="continue" disabled>Continuar</button>
        </div>
    </main>
    <footer id="testes">

    </footer>
    <div id="confirmacao">
        <div class="foco">
            <h1></h1>
            <button id="bt1"></button>
            <button id="bt2"></button>
        </div>
    </div>
    <script id="template-main" type="text/template">
        <div class="line" id="{{line}}">
            <label for='input{{line}}'>{{v1}} {{symbol}} {{v2}} =</label>
            <input id="input{{line}}" min="0" type='number' disabled required>
            <input type='button' value='CORRIGIR' disabled>
            <div id="correcao" class="icon-"></div>
        </div>
    </script>
    <script src="_js/jquery-2.1.4.min.js"></script>
    <script src="_js/ajax.js"></script>
    <script src="_js/main.js"></script>
</body>
</html>
<?php } ?>
