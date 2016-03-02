function carregarPartida(carreira,operacao,etapa,embaralhar){
  embaralhar=embaralhar+"";
  var dados;
  if(carreira==1){
    dados={modo: 'sequencial'};
  }else{
    dados={modo: 'arcade',operacao: operacao, etapa: etapa,embaralhar: embaralhar};
  }

  $.ajax({
      url: '_include/CriarPartida.php',
      dataType: 'json',
      data: dados,
      type: 'POST',
      tentativas: 0,
      success: function(data){
        //  $('#testes').append('<br>success: '+this.tentativas);
          var dados=data;
          operacao_global=dados[dados.length-1][0];
          etapa_global=dados[dados.length-1][1];
          dados.splice(-1);
          values_global=dados;
          jogar();
      },
      error: function(data){
//        $('#testes').append('<br>error: '+this.tentativas);
        this.tentativas+=1;
        if(this.tentativas<3){
          $.ajax(this);
        }else{
  //        $('#testes').append('<br>Conexão interrompida');
        }
      }
  });
}

function salvarResposta(pergunta,valor,tempo){
    $.ajax({
        url: '_include/gravarResposta.php',
        dataType: 'text',
        data: {valor:valor,tempo:tempo,pergunta:pergunta},
        type: 'POST',
        tentativas: 0,
        success: function(data){
      //      $('#testes').append('<br>success: '+this.tentativas);
        //    $('#testes').append(data);
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
