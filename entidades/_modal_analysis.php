<!--

    Displays a modal with the resulting complain analysis


    INCLUDED IN:
        /entidades/singleentidade.php
        /entidades/activityentity.php
        /entidades/multipleentities.php
        /entidades/entitiesradius.php
        /entidades/novaentidade.php
        /denuncias/denunciasclassificadas.php
        /denuncias/denunciasanalisarclass.php
        /dashboards/geraldashboard.php
        /dashboards/denunciasdashboard.php
        /dashboards/entidadesdashboard.php
        /dashboards/fiscalizacoesdashboard.php
-->
<!-- DENUNCIA/RECLAMACAO MESSAGE ANALYSIS MODAL -->
<div class="modal" id="modalMessageAnalisys">
    <div class="modal-dialog" style=" display: table; overflow-x: auto;width: auto;max-width: 800px; ">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Análise da Mensagem</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body modal-body-modalMessageAnalisys">

                <div class="mMA-error1" style="display:none;">
                    <br>
                    <h3 class="text-center">NÃO É POSSIVEL ANALISAR A MENSAGEM</h3>
                    <br>
                </div>

                <div class="mMA-error2" style="display:none;">
                    <br>
                    <h3 class="text-center">ERRO AO ANALISAR A MENSAGEM</h3>
                    <br>
                </div>

                <div class="mMA-results" style="display:none;">
                    <table style="width:100%" class="table table-hover text-centered">

                        <caption>
                            <b>ID DENUNCIA:</b>
                            <span class="mMA-id_denuncia"></span>
                        </caption>

                        <thead>
                            <tr>
                                <td></td>
                                <th scope="col" style="width: 40%">Base de Dados</th>
                                <th scope="col" style="width: 40%">Modelo</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <th scope="row">COMPETENCIA</th>
                                <td class="mMA-competencia_real"></td>
                                <td class="mMA-competencia_model"></td>
                            </tr>
                            <tr>
                                <th scope="row">ACTIVIDADE</th>
                                <td class="mMA-actividade_real"></td>
                                <td class="mMA-actividade_model"></td>
                            </tr>
                            <tr>
                                <th scope="row">CLASS. INFRACÇÃO</th>
                                <td class="mMA-infraccao_real"></td>
                                <td class="mMA-infraccao_model"></td>
                            </tr>
                            <tr>
                                <th scope="row">REMETENTE</th>
                                <td class="mMA-remetente_real"></td>
                                <td class="mMA-remetente_model"></td>
                            </tr>
                            <tr>
                                <th scope="row">ENTIDADE VISADA</th>
                                <td class="mMA-ent_visada_real"></td>
                                <td class="mMA-ent_visada_model"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
            </div>

        </div>
    </div>
</div>
<!-- END OF DENUNCIA/RECLAMACAO MESSAGE ANALYSIS MODAL -->

<script type="text/javascript">



    // called in _modal_denuncia.php when "Analisar" button is clicked
    function analizeMsg() {
        // spin the spinner in then "Analisar" button
        $(".analyzer_spinner").addClass("fa-spin");


      $.ajax({
          url: "<?php echo PYTHON_SRV_DOOR; ?>getanalysis",
         contentType: 'application/json;charset=UTF-8',
         data: JSON.stringify({'g_id_denuncia':g_id_denuncia, 'model': $("#model").is(":checked")?2:1}, null, '\t'),
         type: 'POST',

         success: function(data){
              $(".analyzer_spinner").removeClass("fa-spin");
              showMessageAnalisys(data);
         },
         error: function(data){
            errorsCommonPython(data);
            $(".analyzer_spinner").removeClass("fa-spin");
         },
         timeout: 240000 //in milliseconds
      });
    }



    
    function showMessageAnalisys(msg) {       
        
        msg = JSON.parse(msg);

        if (msg.length == 0) {
            $('.mMA-error1').show();
            $('#modalMessageAnalisys').modal('show');
            return;
        }


        if (msg['status'] == 'ERROR') {
            $('.mMA-error2').show();
            $('#modalMessageAnalisys').modal('show');
            return;
        }

        $('.mMA-id_denuncia').html(msg['id_denuncia']);


        $('.mMA-competencia_real').html(msg['competencia']?msg['competencia']:'-');
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
        $('.mMA-competencia_model').html(str);


        $('.mMA-actividade_real').html(msg['actividade_db']?msg['actividade_db']:'-');
        str = '';
        var len = msg['actividades_model'].length;
        if (len > 3) len = 3;
        for (var i=0; i<len; i++) {
            str +=  (msg['actividades_model'][i]?msg['actividades_model'][i]:'-');
            str += "<br>";               
        }  
        if ($("#model").is(":checked") && len == 1)
          str += "<span style='color:red;background-color:yellow;'>Ocorreu algum problema com o Class. 2!</span>";
        $('.mMA-actividade_model').html(str);



        str = '';
        for (var i=0; i<msg['nat_juridica'].length; i++) {
            str +=msg['nat_juridica'][i];
            str += "<br>";
        }
        $('.mMA-infraccao_real').html(str.length == 0?'-':str);
        // infraccao only has one item
        $('.mMA-infraccao_model').html(msg['infraccao_model'][0]?msg['infraccao_model'][0]:'-');


        $('.mMA-remetente_real').html(msg['remetente']?msg['remetente']:'-');
        $('.mMA-remetente_model').html('-');


        $('.mMA-ent_visada_real').html(msg['entidade_visada']?msg['entidade_visada']:'-');
        $('.mMA-ent_visada_model').html('-');


        $('.mMA-results').show();
        $('#modalMessageAnalisys').modal('show');
   }
</script>
