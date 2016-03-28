//Função para exibir formularios
function exibirForm(form, titulo, textobt1, textobt2,funcao1,funcao2){
    form.find('input').val('');
    form.find('textarea').val('');
    form.find('output').val('');
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
            funcao2();
        }
    });
    form.css('display','inline-block');
    form.find('.foco div:first-child input').focus();
}

//Função para carregar e exibir Turmas e Deficiências
function carregarTurmasDeficiencias(){
  //$('#testes').append("<p>Carregando turmas e deficiencias</p>");
  $('#select-turmas').html('');
  $('#select-deficiencia').html('');
  $.ajax({
      url: '_include/CarregarTurmasDeficiencias.php',
      dataType: 'json',
      type: 'POST',
      tentativas: 0,
      success: function(data){
          var deficiencias=data[0];
          var turmas=data[1];
          //$('#testes').append('<br>success: '+this.tentativas);
          var selectTurmas=$('#select-turmas');
          var selectDeficiencia=$('#select-deficiencia');
          var option="<option value='{{value}}'>{{nome}}</option>";
          selectDeficiencia.append(option.replace('{{value}}',0).replace('{{nome}}','Selecione...'));
          for(var i = 0; i<deficiencias.length;i+=1){
            selectDeficiencia.append(option.replace('{{value}}',deficiencias[i][0]).replace('{{nome}}',deficiencias[i][1]));
          }
          selectTurmas.append(option.replace('{{value}}',0).replace('{{nome}}','Selecione...'));
          for(var i = 0; i<turmas.length;i+=1){
            selectTurmas.append(option.replace('{{value}}',turmas[i][0]).replace('{{nome}}',turmas[i][1]));
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
function cadastrarItem(dados,output,url,funcaoSucesso){
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
              output.css('color','red');
              if(data[0]=="turma"||data[0]=="deficiencia"){
                carregarTurmasDeficiencias();
              }else{
                $('#'+data[0]).select();
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

$('#btn-cadastrarEstudante').click(function(){
  exibirForm($('#cadastroEstudante'),'Cadastrar Estudante','Fechar','Cadastrar',null,function(){
    var output=$('#cadastroEstudante output');
    var dados={
      nomeusuario:$('#nomeusuario').val(),
      nome:$('#nome').val(),
      dataNascimento:$('#dataNascimento').val(),
      observacao:$('#observacao').val(),
      turma: $('#select-turmas').val(),
      deficiencia: $('#select-deficiencia').val(),
      senha: $('#password').val(),
      confirmasenha: $('#confirm-password').val()
    };
    if(dados.nomeusuario!=""&&dados.nome!=""&&dados.dataNascimento!=""&&dados.senha!=""&&dados.confirmasenha!=""&&dados.turma>0&&dados.deficiencia>0){
      cadastrarItem(dados,output,'_include/CadastrarEstudante.php',function(dadosEnviados,dadosRecebidos){
        var td=$('tr[value='+dadosEnviados.turma+'] td:nth-child(2)');
        td.text(parseInt(td.text())+1);
      });
    }else{
      output.text("Preencha todos os campos marcados com (*)");
    }
  });
});

$(document).on('click','.btn-cadastrarTurma',function(){
  exibirForm($('#cadastroTurma'),'Cadastrar Turma','Fechar','Cadastrar',null,function(){
    var output=$('#cadastroTurma output');
    var dados={
      nometurma: $('#nometurma').val(),
      periodoturma: $('#periodoturma').val()
    };
    if(dados.nometurma!=""){
      cadastrarItem(dados,output,'_include/CadastrarTurma.php',function(dadosEnviados,dadosRecebidos){
          var table=$('#tableTurmas');
          var linha='<tr value="'+dadosRecebidos[0]+'"><td>'+dadosEnviados.nometurma+(dadosEnviados.periodoturma==""?"":" - ")+dadosEnviados.periodoturma+'</td><td>0</td><td><form action="Turma.php" method="get"><button class="btn-entrar"><input type="hidden" name="idturma" value="'+dadosRecebidos[0]+'"><span class="icon-right-open"></span></button></form></td></tr>';
          table.find('tbody').append(linha);
          var option="<option value='"+dadosRecebidos[0]+"'>"+dadosEnviados.nometurma+"</option>";
          $('#select-turmas').append(option);
      });
    }else{
      output.text("Preencha todos os campos marcados com (*)");
    }
  });
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
      });
    }else{
      output.text("Preencha todos os campos marcados com (*)");
    }
  });
});

$(document).ready(function(){
  $( "#dataNascimento" ).datepicker({
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
  carregarTurmasDeficiencias();
});
