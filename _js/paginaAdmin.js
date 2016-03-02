function exibirFormEstudante(titulo, textobt1, textobt2,funcao1,funcao2){
    var formEstudante=$('#cadastroEstudante');
    formEstudante.find('input').val('');
    formEstudante.find('textarea').val('');
    formEstudante.find('output').val('');
    formEstudante.find('button').off();
    formEstudante.find('h1').html(titulo);
    formEstudante.find('#bt1').text(textobt1);
    formEstudante.find('#bt1').click(function(){
        if(typeof(funcao1)=='function'){
            funcao1();
        }
        formEstudante.css('display','none');
    });
    formEstudante.find('#bt2').text(textobt2);
    formEstudante.find('#bt2').click(function(){
        if(typeof(funcao2)=='function'){
            funcao2();
        }
    });
    formEstudante.css('display','inline-block');
    carregarTurmasDeficiencias();
}

function cadastrarEstudante(){
  var nomeusuario=$('#nomeusuario').val();
  var nome=$('#nome').val();
  var dataNascimento=$('#dataNascimento').val();
  var observacao=$('#observacao').val();
  var turma = $('#select-turmas').val();
  var deficiencia = $('#select-deficiencia').val();
  var senha= $('#password').val();
  var confirmasenha= $('#confirm-password').val();

  var output=$('#cadastroEstudante output');
  if(nomeusuario!=""&&nome!=""&&dataNascimento!=""&&senha!=""&&confirmasenha!=""){
    output.text('');
    $.ajax({
        url: '_include/CadastrarEstudante.php',
        dataType: 'json',
        type: 'POST',
        data: {nome:nome,nomeusuario:nomeusuario,dataNascimento:dataNascimento,observacao:observacao,turma:turma,deficiencia:deficiencia,senha:senha,confirmasenha:confirmasenha},
        tentativas: 0,
        success: function(data){
            //$('#testes').append('<br>success: '+this.tentativas);
            //$('#testes').append(data);
            output.text(data[1]);
            if(data[0]==1){
              output.css('color','green');
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
//          $('#testes').append('<br>error: '+this.tentativas);
          this.tentativas+=1;
          if(this.tentativas<3){
            $.ajax(this);
          }else{
  //          $('#testes').append('<br>Conexão interrompida');
          }
        }
    });
  }else{
    output.text("Preencha todos os campos marcados com (*)");
  }
}

function carregarTurmasDeficiencias(){
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
          for(var i = 0; i<deficiencias.length;i+=1){
            selectDeficiencia.append(option.replace('{{value}}',deficiencias[i][0]).replace('{{nome}}',deficiencias[i][1]));
          }
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

$('#btn-cadastrarEstudante').click(function(){
  exibirFormEstudante('Cadastrar Estudante','Fechar','Cadastrar',null,cadastrarEstudante);
});

$('#btn-cadastrarTurma').click(function(){
  exibirFormEstudante('Cadastrar Estudante','Fechar','Cadastrar',null,cadastrarEstudante);
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
});
