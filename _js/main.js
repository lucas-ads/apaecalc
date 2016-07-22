var values_global;
var template_global;
var operacao_global;
var etapa_global;
var tempo_global;
var carreira_global=0;
var embaralhar_global;
var perdeu_global=false;

function getSymbol(){
    if(operacao_global==1)
        return "+";
    if(operacao_global==2)
        return "-";
    if(operacao_global==3)
        return "X";
    if(operacao_global==4)
        return "/";
}

function getNameCaption(){
    if(operacao_global==1)
        return "Adição";
    if(operacao_global==2)
        return "Subtração";
    if(operacao_global==3)
        return "Multiplicação";
    if(operacao_global==4)
        return "Divisão";
}

function replaceAll(text, oldText, newText){
    var pos = text.indexOf(oldText);
    while (pos > -1){
		text = text.replace(oldText, newText);
		pos = text.indexOf(oldText);
	}
    return (text);
}

function insertLine(line,totalLines,atualLine){
    var container=$('#container');
    if(totalLines>6){
        var metade = Math.ceil(totalLines/2);
        if(atualLine<=metade){
            container.find('.left').append(line);
        }else{
            container.find('.right').append(line);
        }
    }else{
        container.find('.left').append(line);
    }
}

function toogleLine(line){
    if(line.hasClass('enable')){
        line.find('input').attr('disabled',true);
        line.removeClass('enable');
        line.find('#correcao').attr('class', 'icon-check');
    }else{
        line.find('input').removeAttr('disabled');
        line.addClass('enable');
        line.find('input:first').focus();
        tempo_global=new Date();
    }
}

function checkLine(line){
    var reply=parseInt(line.find('input:first').val());
    var index=parseInt(line.attr('id'));
    var result=new Array(false,false);

    if(reply==values_global[index][2]){
        result[0]=true;
    }

    if(values_global[index][3]==undefined){
        values_global[index][3]=reply;
        values_global[index][4] = result[0];
        result[1]=true;
    }

    return result;
}

//Evento para impedir a digitação de valores não numéricos
$(document).on("keypress",'.line input',function(e){
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        if(e.which == 13){
            controlStage();
        }else{
            return false;
        }
    }
});

function controlStage(){
    var line=$('.enable');
    var resposta=line.find('input:first').val();
    if(resposta!=""){
        var situation = checkLine(line);
        var idline= parseInt(line.attr('id'));
        var tempoGasto=Math.round(parseInt(new Date()-tempo_global)/1000);
        salvarResposta(idline,parseInt(resposta),tempoGasto);
        showMatriz();

        if(situation[0]){
            toogleLine(line);
            if(idline==values_global.length-2){
                $('#continue').removeAttr('disabled');
                $('#continue').focus();
            }else{
                if(idline==values_global.length-1){
                    $('#nextstage').removeAttr('disabled');
                    $('#nextstage').focus();
                }else{
                    if(line.next().hasClass('line')==true){
                        toogleLine(line.next());
                    }else{
                        toogleLine($('.right').find('.line:first-child'));
                    }
                }
            }
        }else{
            if(idline==values_global.length-1){
              perdeu_global=true;
            }
            line.find('#correcao').attr('class', 'icon-cancel');
            tempo_global=new Date();
        }
    }
}

function buildBoss(){
    var container = $('#container');
    container.find('.side').html("");
    operation=getSymbol();
    var index = values_global.length-1;

    var text=replaceAll(template_global,'{{line}}', index);
    text=replaceAll(text,'{{v1}}', values_global[index][0]);
    text=replaceAll(text,'{{v2}}', values_global[index][1]);
    text=replaceAll(text,'{{symbol}}', operation);
    container.find('.left').append(text);
    toogleLine($('.line:first-child'));
}

function buildStage(){
    var container = $('#container');
    container.find('.side').html("");
    operation=getSymbol();
    for(var i=0;i<values_global.length-1;i+=1){
        var text=replaceAll(template_global,'{{line}}', i);
        text=replaceAll(text,'{{v1}}', values_global[i][0]);
        text=replaceAll(text,'{{v2}}', values_global[i][1]);
        text=replaceAll(text,'{{symbol}}', operation);
        $('#caption').html(getNameCaption());
        $('#stage').html(etapa_global);
        insertLine(text, values_global.length-1, i+1);
    }
    toogleLine($('.left .line:first-child'));
}

function showMatriz(){
    for(var i=0;i<values_global.length;i+=1){
        console.log(values_global[i]);
    }
}

function getQuantStages(operacao){
    switch(operacao){
        case 1: return 12; break;
        case 2: return 11; break;
        case 3: return 12; break;
        case 4: return 11; break;
        default: return "error";
    }
}

function toogleWindow(destino){
    var home=$('#home');
    var game=$('#game');
    if(destino=="home"){
        home.css('display','block');
        game.css('display','none');
    }else{
        home.css('display','none');
        game.css('display','block');
    }
}

function exibeEstagios(capitulo){
    var stages=getQuantStages(capitulo);
    var comboBoxStages=$('#comboEstagio');

    comboBoxStages.html('');

    for(var i=1;i<=stages;i+=1){
        var option="<option value="+i+">"+i+"</option>";
        comboBoxStages.append(option);
    }
}

function exibirMensagemConfirmacao(pergunta, textobt1, textobt2,funcao1,funcao2){
    var mensagem=$('#confirmacao');
    mensagem.find('button').off();
    mensagem.find('h1').html(pergunta);
    mensagem.find('#bt1').text(textobt1);
    mensagem.find('#bt1').click(function(){
        if(typeof(funcao1)=='function'){
            funcao1();
        }
        $('#confirmacao').css('display','none');
    });
    mensagem.find('#bt2').text(textobt2);
    mensagem.find('#bt2').click(function(){
        if(typeof(funcao2)=='function'){
            funcao2();
        }
        $('#confirmacao').css('display','none');
    });
    $('#confirmacao').css('display','inline-block');
}

function igualarDivs(){
    var carreira=$('#carreira');
    var arcade=$('#arcade');

    carreira.css('height','auto');
    arcade.css('height','auto');

    var heightCarreira=parseInt(carreira.css('height').replace('px',""));
    var heightArcade=parseInt(arcade.css('height').replace('px',""));

    if(heightArcade>heightCarreira){
        carreira.css('height',heightArcade+"px");
    }
    if(heightCarreira>heightArcade){
        arcade.css('height',heightCarreira+"px");
    }
}

function jogar(){
    buildStage();
    toogleWindow("game");
    showMatriz();
    perdeu_global=false;
    $('.enable [id*="input"]').focus();
};

//Evento do botão continuar para continuar jogo rumo a pergunta final da fase
$(document).on('click','#continue',function(){
    $(this).attr('id','nextstage');
    buildBoss();
    $(this).attr('disabled','true')
});

//Evento do botão continuar(nextstage) que leva para a próxima fase
$(document).on('click','#nextstage',function(){
    $(this).attr('id','continue');
    if(carreira_global==1){
      carregarPartida(1,0,0,0);
    }else{
      if(perdeu_global==false){
          var etapa;
          var operacao;
          if(etapa_global<getQuantStages(operacao_global)){
            etapa = etapa_global+1;
            operacao=operacao_global;
          }else{
            if(operacao_global<4){
              operacao=operacao_global+1;
              etapa=1;
            }else{
              operacao=1
              etapa=1;
            }
          }
          carregarPartida(0,operacao,etapa,embaralhar_global);
        }else{
          carregarPartida(0,operacao_global,etapa_global,embaralhar_global);
        }
    }
    $(this).attr('disabled','true');
});

//Evento para mostrar as fases do capitulo selecionado
$('#operacao').change(function(){
    exibeEstagios(parseInt($('#operacao').val()))
});

//Evento para checar a linha respondida
$(document).on("click",'.line input:button', controlStage);

//Evento do botão para continuar jogo de onde o jogador parou
$(document).on('click','#continuarJogo',function(){
  carreira_global=1;
  carregarPartida(1,0,0,0);
});

//Evento do botão para jogar determinada fase
$(document).on('click','#jogarEstagio',function(){
    var operacao = parseInt($('#operacao').val());
    var etapa = parseInt($('#comboEstagio').val());
    embaralhar_global=$('#embaralhar').prop('checked');
    carreira_global=-1;
    carregarPartida(-1,operacao,etapa,embaralhar_global);
});

//Evento de redimensionamento
$(window).resize(igualarDivs);

$(document).ready(function(){
    exibeEstagios(parseInt($('#operacao').val()));
    igualarDivs();
    template_global=$("#template-main").html();
});


$(document).on('click','#voltarMenu',function(){
    exibirMensagemConfirmacao('Deseja Sair?','Sim','Não',voltarAoMenu,null);
});

$(document).on('click','#btResetar',function(){
    exibirMensagemConfirmacao('Deseja Reiniciar?','Sim','Não',reiniciarFase,null);
});

function voltarAoMenu(){
    var values_global=undefined;
    var operacao_global=undefined;
    var etapa_global=undefined;
    var tempo_global=undefined;
    var carreira_global=0;
    var embaralhar_global=undefined;
    var perdeu_global=false;
    $('#container').find('.side').html("");
    toogleWindow("home");
    $('#nextstage').attr('id','continue');
    $('#continue').attr('disabled',true);
}

function reiniciarFase(){
    $('#nextstage').attr('id','continue');
    $('#continue').attr('disabled',true);
    if(carreira_global==1){
      carregarPartida(1,0,0,0);
    }else{
      carregarPartida(0,operacao_global,etapa_global,embaralhar_global);
    }
}

//Evento para impedir a perca de foco do input da linha ativa
$(document).on("focusout",'.enable input:first',function(){
    $(this).focus();
});
