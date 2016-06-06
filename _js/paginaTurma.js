//Função para exibir formularios
function exibirForm(form, titulo, textobt1, textobt2,funcao1,funcao2,id){
    form.find('input:not([type="radio"])').val('');
    form.find('textarea').val('');
    form.find('output').val('');
    form.find('input[type="checkbox"]').prop("checked",false);
    form.find('button').off();
    form.find('h1').html(titulo);
    form.find('#bt1').text(textobt1);
    form.find('#bt1').click(function(){
        if(typeof(funcao1)=='function'){
            funcao1();
        }
        form.css('display','none');
    });
    form.find('#bt2').text(textobt2);
    form.find('#bt2').click(function(){
        if(typeof(funcao2)=='function'){
            funcao2(id);
        }
    });
    form.css('display','inline-block');
    form.find('.foco div:first-child input').focus();
}

//Função para carregar e exibir Turmas e Deficiências
function carregarDeficiencias(){
  //$('#testes').append("<p>Carregando turmas e deficiencias</p>");
  $('#select-deficiencia').html('');
  $.ajax({
      url: '_include/CarregarTurmasDeficiencias.php',
      dataType: 'json',
      type: 'POST',
      tentativas: 0,
      success: function(data){
          //$('#testes').append(data);
          //alert(data);
          var deficiencias=data[0];
          var selectDeficiencia=$('#select-deficiencia');
          var edicaoselectDeficiencia=$('#edicaoselect-deficiencia');
          var option="<option value='{{value}}'>{{nome}}</option>";
          selectDeficiencia.append(option.replace('{{value}}',0).replace('{{nome}}','Selecione...'));
          edicaoselectDeficiencia.append(option.replace('{{value}}',0).replace('{{nome}}','Selecione...'));
          for(var i = 0; i<deficiencias.length;i+=1){
            selectDeficiencia.append(option.replace('{{value}}',deficiencias[i][0]).replace('{{nome}}',deficiencias[i][1]));
            edicaoselectDeficiencia.append(option.replace('{{value}}',deficiencias[i][0]).replace('{{nome}}',deficiencias[i][1]));
          }
      },
      error: function(data){
        //$('#testes').append('<br>error: '+this.tentativas);
        this.tentativas+=1;
        if(this.tentativas<3){
          $.ajax(this);
        }else{
          //$('#testes').append('<br>Conexão interrompida');
        }
      }
  });
}

//Função para, ao receber os dados, o elemento de saída e o endereço do arquivo php, cadastra-los e tratar os seus retornos
function cadastrarItem(dados,output,url,funcaoSucesso,funcaoFalha){
    //$('#testes').append("<p>Cadastrando Item</p>");
    output.text('');
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'POST',
        data: dados,
        tentativas: 0,
        success: function(data){
            //$('#testes').append('<br>success: '+this.tentativas);
            //$('#testes').append(data);
            output.text(data[1]);
            if($.isNumeric(data[0])){
              output.css('color','green');
              funcaoSucesso(dados,data);
            }else{
              if(funcaoFalha!=null){
                  funcaoFalha();
              }
              output.css('color','red');
              if(data[0]=="deficiencia"){
                carregarDeficiencias();
              }else{
                if(dados.operacao=="CADASTRAR"){
                  $('#'+data[0]).select();
                }else{
                  $('#edicao'+data[0]).select();
                }
              }
            }
        },
        error: function(data){
          //$('#testes').append('<br>error: '+this.tentativas);
          this.tentativas+=1;
          if(this.tentativas<3){
            $.ajax(this);
          }else{
            //$('#testes').append('<br>Conexão interrompida');
          }
        }
    });
}

//Povoa o formulário de Edição de Estudante
function povoarEdicaoEstudante(id){
  $('#idEstudanteEdicao').val(id);
  var dados = {idEstudante:id};
  $.ajax({
      url: '_include/CarregarDadosPessoaisEstudante.php',
      dataType: 'json',
      type: 'POST',
      data: dados,
      tentativas: 0,
      success: function(data){
        //$('#testes').append("1" + data);
          if(data[0]==1){
            $('#edicaonome').val(data[1]);
            $('#edicaonomeusuario').val(data[2]);
            $('#edicaodataNascimento').val(data[3]);
            $('#edicaoselect-deficiencia option:eq("'+data[4]+'")').prop('selected', true);
            $('#edicaoobservacao').val(data[5]);
            if(data[6]==0){
              $('#edicaoradio-noembaralhar').prop('checked',true);
            }else{
              $('#edicaoradio-embaralhar').prop('checked',true);
            }
          }
      },
      error: function(data){
        $('#testes').append('<br>error: '+this.tentativas);
        this.tentativas+=1;
        if(this.tentativas<3){
          $.ajax(this);
        }else{
          //$('#testes').append('<br>Conexão interrompida');
        }
      }
  });
}

//Se id<=0 a função irá cadastrar uma turma, caso contrário a função irá tentar editar a turma referente ao id
function cadastrarEstudante(id){
  var output=$('#cadastroEstudante output');
  var dados={
    operacao: "CADASTRAR",
    nomeusuario:$('#nomeusuario').val(),
    nome:$('#nome').val(),
    dataNascimento:$('#dataNascimento').val(),
    observacao:$('#observacao').val(),
    turma: $('#select-turmas').val(),
    deficiencia: $('#select-deficiencia').val(),
    senha: $('#password').val(),
    confirmasenha: $('#confirm-password').val(),
    embaralharjogo: $("input[name='radio-embaralhar']:checked").val()
  };
  if(dados.nomeusuario!=""&&dados.nome!=""&&dados.dataNascimento!=""&&dados.senha!=""&&dados.confirmasenha!=""&&dados.turma>0&&dados.deficiencia>0&&dados.embaralharjogo>-1&&dados.embaralharjogo<2){
    cadastrarItem(dados,output,'_include/CadastrarEditarEstudante.php',function(dadosEnviados,dadosRecebidos){
      var template=$('#template-linhaestudante').text();
      var nome="";
      if(dadosEnviados.nome.length<=25){
        nome=dadosEnviados.nome;
      }else{
        nome=dadosEnviados.nome.substring(0,22)+"...";
      }
      template=template.replace('{{idestudante}}',dadosRecebidos[0])
                      .replace('{{nomeestudante}}',nome)
                      .replace('{{nomeusuario}}',dadosEnviados.nomeusuario)
                      .replace('{{datadenascimento}}',dadosEnviados.dataNascimento);
      $('#tableEstudantes tbody').append(template);
      $('#formCadEstudante').find('#nome,#nomeusuario,#dataNascimento,#observacao').val('');
    },null);
  }else{
    output.text("Preencha todos os campos marcados com (*)");
  }
}

function editarEstudante(id){
  var output=$('#edicaoDadosGerais output');
  var dados={
    operacao: "EDITAR",
    idEstudante: $('#idEstudanteEdicao').val(),
    nomeusuario:$('#edicaonomeusuario').val(),
    nome:$('#edicaonome').val(),
    dataNascimento:$('#edicaodataNascimento').val(),
    observacao:$('#edicaoobservacao').val(),
    deficiencia: $('#edicaoselect-deficiencia').val(),
    senha: $('#edicaopassword').val(),
    alterarsenha: $('#check-alterarsenha').prop("checked")==true?1:0,
    confirmasenha: $('#edicaoconfirm-password').val(),
    embaralharjogo: $("input[name='edicaoradio-embaralhar']:checked").val()
  };
  if(dados.nomeusuario!=""&&dados.nome!=""&&dados.dataNascimento!=""&&dados.deficiencia>0&&dados.embaralharjogo>-1&&dados.embaralharjogo<2&&!(dados.alterarsenha==1&&(dados.senha==""||dados.confirmasenha==""))){
    cadastrarItem(dados,output,'_include/CadastrarEditarEstudante.php',function(dadosEnviados,dadosRecebidos){
      var linhaestudante=$('#tableEstudantes tbody tr[value="'+dadosEnviados.idEstudante+'"]');
      if(dadosEnviados.nome.length<=25){
        linhaestudante.find('td:nth-child(2)').text(dadosEnviados.nome);
      }else{
        linhaestudante.find('td:nth-child(2)').text(dadosEnviados.nome.substring(0,22)+"...");
      }
      linhaestudante.find('td:nth-child(3)').text(dadosEnviados.nomeusuario);
      linhaestudante.find('td:nth-child(4)').text(dadosEnviados.dataNascimento);
      $("#edicaoDadosGerais #bt1").text("FECHAR");
    },null);
  }else{
    output.text("Preencha todos os campos marcados com (*)");
  }
}

$('#btn-cadastrarEstudante').click(function(){
  exibirForm($('#cadastroEstudante'),'Cadastrar Estudante','Fechar','Cadastrar',null,cadastrarEstudante,0);
});

$(document).on('click','.btn-editarestudante',function(){
  exibirForm($('#edicaoDadosGerais'),'Atualizar Informações','Cancelar','Salvar',null,editarEstudante,0);
  var id=parseInt($(this).parent().parent().attr('value'));
  povoarEdicaoEstudante(id);
});

$(document).on('click','.btn-cadDeficiencia',function(){
  exibirForm($('#cadastroDeficiencia'),'Cadastrar Deficiência','Fechar','Cadastrar',null,function(){
    var output=$('#cadastroDeficiencia output');
    var dados={
      nomedeficiencia: $('#nomedeficiencia').val()
    };
    if(dados.nomedeficiencia!=""){
      cadastrarItem(dados,output,'_include/CadastrarDeficiencia.php', function(dadosEnviados,dadosRecebidos){
        var option="<option value='"+dadosRecebidos[0]+"'>"+dadosEnviados.nomedeficiencia+"</option>";
        $('#select-deficiencia').append(option);
        $('#edicaoselect-deficiencia').append(option);
      },null);
    }else{
      output.text("Preencha todos os campos marcados com (*)");
    }
  },0);
});

function verificaCheckboxs(checkboxs){
  var checados=0;
  checkboxs.each(function(){
    if($(this).prop("checked")==true){
        checados=+1;
    }
   });
   return checados;
}

function abilitaSelecao(){
  var elements=$('.td-nome,.td-nomeusuario');
  elements.css('cursor','pointer');
  elements.off();
  elements.click(function(){
    var check=$(this).parent().find('.check-estudante');
    if(check.prop("checked")==true){
      check.prop("checked",false);
      if(verificaCheckboxs($(".check-estudante"))==0){
        $('#btn-transferir').addClass('disabled');
        desabilitaSelecao();
      }
    }else{
      check.prop("checked",true);
    }
  });
}

function desabilitaSelecao(){
  var elements=$('.td-nome,.td-nomeusuario');
  elements.css('cursor','auto');
  elements.off();
}

$(document).on('click','.check-estudante',function(){
  var checkboxs=$('.check-estudante');
  checados=verificaCheckboxs(checkboxs);
   if(checados){
     $('#btn-transferir').removeClass("disabled");
     abilitaSelecao();
   }else{
     $('#btn-transferir').addClass('disabled');
     desabilitaSelecao();
   }
});

$(document).on('scroll', function () {
  var distanceToTop = $(window).scrollTop();
  if(distanceToTop>150){
    $("#btn-transferir").addClass("fixed");
  }else{
    $("#btn-transferir").removeClass("fixed");
  }
});

$(document).on('blur','#formCadEstudante input#nome',function(){
  var texto = $('#formCadEstudante input#nome').val().split(' ');
  if(texto.length>=2){
    $('#formCadEstudante input#nomeusuario').val((texto[0]+texto[1]).toLowerCase().replace(/[áàâã]/g,'a').replace(/[éèê]/g,'e').replace(/[îíì]/g,'i').replace(/[óòôõ]/g,'o').replace(/[úùû]/g,'u').replace(/[ç]/g,'c'));
  }
});

$(document).ready(function(){
  $( "#dataNascimento,#edicaodataNascimento" ).datepicker({
    maxDate: '-1Y',
    dateFormat: 'dd/mm/yy',
    changeMonth: true,
    changeYear: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']
  });
  carregarDeficiencias();
});
