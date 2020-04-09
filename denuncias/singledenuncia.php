<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>

<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/denuncias.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/toggleswitch.css"/>



<div class="container-fluid">    

  <div class="loading" style="display:none;">Loading&#8230;</div>


  <br>

  <div class="row">
      <div class="col-sm-8">
          <label for="comment">Cole, escreva ou selecione um <label class="btn btn-outline-primary btn-file btn-sm" style="font-weight: bold;">ficheiro
              <input type="file" id="file" name="file" style="display: none;" accept='text/plain' onchange='openFile(event)'/>
          </label> com a denúncia:</label>
      </div>
      <div class="col-sm-4">
        <div class="toggle_stats_table" style="margin-left: 15px;margin-top: -20px; position: absolute;  right: 10px;" >
              Class. 1
              <label class="switchA">
                  <input type="checkbox" id="model">
                  <span class="sliderA"></span>
              </label>
              Class. 2
          </div>
      </div>
  </div>

  <div class="row">
      <div class="col-sm-12">  
          <textarea class="form-control" rows="8" id="denuncia"></textarea>
      </div> 
  </div>

  <br>

  <div class="row">
      <div class="col-sm-5"></div>
      <div class="col-sm-2">
           <button type="button" class="btn btn-primary btn-lg" onClick="analizeDenuncia();" id="classificar" disabled>Classificar</button>
       </div>
       <div class="col-sm-5"></div>
  </div>



  <!--RESULTS MODAL -->
  <div class="modal" id="modal_results">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">

              <div class="modal-header">
                  <h4 class="modal-title modal-title-message">Resultados</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              <div class="modal-content-message">
                  <div class="modal-body modal-body-message">
                              <table class="table">
                              <thead>
                                <tr>
                                  <th class="text-center" style="background-color: #0000FF;color:white;width: 30%;">Campo</th>
                                  <th class="text-center" style="background-color: #0000FF;color:white;width: 35%;">Resultados</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td class="font-weight-bold" style="background-color: #CCFFFF;color:black;">COMPETÊNCIA</td>
                                  <td class="text-center" id="competencia">-</td>
                                </tr>
                                <tr>
                                  <td class="font-weight-bold" style="background-color: #CCFFFF;color:black;">ACTIVIDADES</td>
                                  <td class="text-center" id="actividades">-</td>
                                </tr>
                                <tr>
                                  <td class="font-weight-bold" style="background-color: #CCFFFF;color:black;">CLASSE DE INFRACÇÃO</td>
                                  <td class="text-center" id="infraccoes">-</td>
                                </tr>
                              </tbody>
                            </table>

                  </div>
              </div>

              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
              </div>

          </div>
      </div>
  </div>
  <!-- END OF RESULTS MODAL -->


  <!-- HELP MODAL -->
    <div class="modal" id="modal-help">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
        
          <div class="modal-header">
            <h4 class="modal-title">Ajuda</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          
          <div class="modal-body modal-body-help text-justify">
              <p>
                Neste página poderá introduzir uma denúncia e experimentar os classificadores desenvolvidos até ao momento.
              </p>
              <p>
                Existem dois classificadores. Ao selecionar <i><mark style="background-color: #33CCFF;border-radius: 5px; padding: 2px;color:black;">Class. 1</mark></i>, a classificação irá apresentar uma actividade. Ao selecionar <i><mark style="background-color: #CCFF66;border-radius: 5px; padding: 2px;color:black;">Class. 2</mark></i>, serão apresentadas até 11 actividades, em ordem decrescente de probabilidade.
              </p>
              <p>
                A denúncia pode ser introduzida manualmente, colada ou através de um ficheiro de texto.
              </p>
              
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
          </div>
          
        </div>
      </div>
    </div>
    <!-- END OF HELP MODAL -->

</div>



<script>
	$(document).ready(function() {

        // as soon there is a character in the box, activate class. button
        $('#denuncia').bind('input propertychange', function() {
          if(this.value.length){
              $("#classificar").prop("disabled", false);
          } else {
              $("#classificar").prop("disabled", true);
          }
        });
    } );


    var openFile = function(event) {
        var input = event.target;
        var reader = new FileReader();
        reader.onload = function(){
            var text = reader.result;
            $("#denuncia").val(text);
            if($("#denuncia").val().length){
              $("#classificar").prop("disabled", false);
            } else {
              $("#classificar").prop("disabled", true);
            }
        };
        reader.readAsText(input.files[0]);
    };



    function analizeDenuncia() { 
        if ($("#denuncia").val().length == 0) return;
        $(".loading").show(); 
        var model = $("#model").is(":checked")?2:1;
        $.ajax({
              url: "<?php echo PYTHON_SRV_DOOR; ?>analyzedenuncia",
             contentType: 'application/json;charset=UTF-8',
             data: JSON.stringify(
                  {'denuncia':$("#denuncia").val(), 'model': model}, 
                  null, 
                  '\t'),
             type: 'POST',

             success: function(data){
                  showResults(data,1);                  
             },
             error: function(data){
                $(".loading").hide();
                var s = '<b>Erro! Servidor Python ou serviço web não encontrado.</b></br>';
                $('.modal-body-modalDBError').html(s);
                $('#modalDBError').modal('show');
             },
             timeout: 240000 //in milliseconds
          });
    }


    function showResults(data) {
        $(".loading").hide();        

        $("#competencia").html('-');
        $("#actividades").html('-');
        $("#infraccoes").html('-');

        $('#modal_results').modal('show');

        msg = JSON.parse(data);

        if (msg.length == 0) {
            var str = '<br><h3 class="text-center">NÃO É POSSIVEL ANALISAR A DENÚNCIA</h3><br>'
            $('.modal-body-modalDBError').html(str);
            $('#modalDBError').modal('show');
            return;
        }

        if (msg['status'] == 'ERROR') {
            var str = '<br><h3 class="text-center">ERRO AO ANALISAR A DENÚNCIA</h3><br>'
            $('.modal-body-modalDBError').html(str);
            $('#modalDBError').modal('show');
            return;
        }
        var str = '';
        for (var i=0; i<msg['comp_model'].length; i++) {
            if (msg['comp_model_simples'][i] && msg['comp_model_simples'][i] == 1) {
                 str += "XXX";
            } else {
                 str += "OUTROS";
            }
            str += " (" + (msg['comp_model'][i]?msg['comp_model'][i]:'-') + ")";
            str += "<br>";
        }
        $("#competencia").html(str);

        str = '';
        var len = msg['actividades_model'].length;
        //if (len > 3) len = 3;
        for (var i=0; i<len; i++) {
            str +=  (msg['actividades_model'][i]?msg['actividades_model'][i]:'-');
            str += "<br>";               
        }
        if ($("#model").is(":checked") && len == 1)
          str += "<span style='color:red;background-color:yellow;'>Ocorreu algum problema com o Class. 2!</span>";
        $("#actividades").html(str);

        // infraccao only has one item
        str = '-';
        $("#infraccoes").html(msg['infraccao_model'][0]?msg['infraccao_model'][0]:'-');
    }



</script>




<?php include('../footer.php'); ?>