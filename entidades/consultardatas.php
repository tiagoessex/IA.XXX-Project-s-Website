<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>

<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>
<link href="<?php echo DOMAIN_URL; ?>external/tabulator/css/tabulator_modern.min.css" rel="stylesheet">

<div class="container-fluid">

    <br>

    <h1 class="text-center">
        Entidades sem qualquer tipo de envolvimento
    </h1>

    <br><br>

    <div class="row col-sm-12">
        <h3 style="background-color: #99CCFF;border-radius: 10px; padding: 5px;color:black;">
            <b>Número de entidades: </b>
            <span class="counter2">
          <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;"> 
      </span>
            <b>de</b>
            <span class="counter">
          <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;"> 
      </span>
        </h3>
    </div>

    <br>
    <!--
  <div class="row col-sm-12">
      <table style="width:100%" id="table">
      </table>
  </div>
  -->

    <div class="row text-center">
        <img src="../images/forbidden.png" alt="forbidden" style="width:128px;height:128px;" class="mx-auto d-block">
    </div>

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
                        Nesta página poderá consultar todas as entidades identificadas como "inúteis", isto é, entidades que não estiveram envolvidas em nenhuma acção ou procedimento por parte da XXX, para além de terem sido introduzidas.
                    </p>
                    <p>
                        Última "acção" que cada entidade esteve envolvida e datas respectivas (datas de introdução e update).

                    </p>
                    <p>
                        Tabelas e campos envolvidos na selecção:
                    </p>
                    <!--
            <table class="table">
              <thead class="text-left">
                <tr>
                  <th>      Tabela    </th>
                  <th>      Data    </th>
                  <th>      Entidade    </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>      amostras_fiscalizacoes    </td>
                  <td>      DT_COLHEITA   </td>
                  <td>      fisc_ent_id_alvo    </td>
                </tr>
                <tr>
                  <td>      apreensoes    </td>
                  <td>      DATA_APR    </td>
                  <td>      entidade_id_entidade    </td>
                </tr>
                <tr>
                  <td>      apreensoes    </td>
                  <td>      DT_SISTEMA    </td>
                  <td>      entidade_id_entidade    </td>
                </tr>
                <tr>
                  <td>      campo_ferias    </td>
                  <td>      DT_SISTEMA    </td>
                  <td>      id_entidade_promotora   </td>
                </tr>
                <tr>
                  <td>      campo_ferias    </td>
                  <td>      DT_SISTEMA    </td>
                  <td>      id_entidade_organizadora    </td>
                </tr>
                <tr>
                  <td>      correspondencias    </td>
                  <td>      DT_LOCK    </td>
                  <td>      entidade_id_entidade    </td>
                </tr>   
                <tr>
                  <td>      correspondencias    </td>
                  <td>      DT_REGISTO    </td>
                  <td>      entidade_id_entidade    </td>
                </tr> 
                <tr>
                  <td>      correspondencias    </td>
                  <td>      DT_EMISSAO_ORIGEM    </td>
                  <td>      entidade_id_entidade    </td>
                </tr>                                            
                <tr>
                  <td>      correspondencias    </td>
                  <td>      DT_SISTEMA    </td>
                  <td>      entidade_id_entidade    </td>
                </tr>
                <tr>
                  <td>      correspondencias    </td>
                  <td>      DT_INICIAL    </td>
                  <td>      entidade_id_entidade    </td>
                </tr>
                <tr>
                  <td>      correspondencias    </td>
                  <td>      DT_SITUACAO    </td>
                  <td>      entidade_id_entidade    </td>
                </tr> 
                <tr>
                  <td>      correspondencias    </td>
                  <td>      DT_LAST_LRE_HIST_CHECK    </td>
                  <td>      entidade_id_entidade    </td>
                </tr>                                                
                <tr>
                  <td>      decisoes_ent    </td>
                  <td>      DT_DECISAO    </td>
                  <td>      ent_decisora_id   </td>
                </tr>
                <tr>
                  <td>      declaracoes_de_transaccoes    </td>
                  <td>      DT_SISTEMA    </td>
                  <td>      entidade_id_entidade    </td>
                </tr>
                <tr>
                  <td>      entidade    </td>
                  <td>      DT_INICIAL    </td>
                  <td>      id_entidade   </td>
                </tr>
                <tr>
                  <td>      entidade    </td>
                  <td>      DT_SISTEMA    </td>
                  <td>      id_entidade   </td>
                </tr>
                <tr>
                  <td>      entidade    </td>
                  <td>      DT_VALIDACAO    </td>
                  <td>      id_entidade   </td>
                </tr>                                
                <tr>
                  <td>      entidade_processo   </td>
                  <td>      DT_SISTEMA    </td>
                  <td>      entidade_id_entidade    </td>
                </tr>
                <tr>
                  <td>      fisc_entidade   </td>
                  <td>      DT_AVERIG   </td>
                  <td>      entidade_id_entidade    </td>
                </tr>
                <tr>
                  <td>      fisc_entidade   </td>
                  <td>      DT_LOCK   </td>
                  <td>      entidade_id_entidade    </td>
                </tr>
                <tr>
                  <td>      fisc_entidade   </td>
                  <td>      DT_INICIAL   </td>
                  <td>      entidade_id_entidade    </td>
                </tr> 
                <tr>
                  <td>      fisc_entidade   </td>
                  <td>      DT_SISTEMA   </td>
                  <td>      entidade_id_entidade    </td>
                </tr>                                                 
                <tr>
                  <td>      processos   </td>
                  <td>      DT_SISTEMA    </td>
                  <td>      ent_destino_id    </td>
                </tr>
                <tr>
                  <td>      reclamacao    </td>
                  <td>      RECLAM_DATE   </td>
                  <td>      reclamante_id   </td>
                </tr>
                <tr>
                  <td>      reclamacao    </td>
                  <td>      RECLAM_DATE   </td>
                  <td>      entidade_visada_id    </td>
                </tr>
              </tbody>
              </table>
              -->

                    <div class="row text-center">
                        <img src="../images/forbidden.png" alt="forbidden" style="width:128px;height:128px;" class="mx-auto d-block">
                    </div>

                    <br>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
                </div>

            </div>
        </div>
    </div>
    <!-- END OF HELP MODAL -->

</div>


<script src="<?php echo DOMAIN_URL; ?>js/utils.js"></script>


<script type="text/javascript" src="<?php echo DOMAIN_URL; ?>external/tabulator/js/tabulator.min.js"></script>



<script>

    var last_id = -1;
    var block_size = 60;
    var timerloop = null;

    $( document ).ready(function() {
    //  getNumberOfNullEntities();
    //  getData();
    });



    // *********** TABLE ****************
    var table = new Tabulator("#table", {
        layout:"fitColumns",      //fit columns to width of table
        responsiveLayout:"hide",  //hide columns that dont fit on the table
        tooltips:true,            //show tool tips on cells
        movableColumns:true,      //allow column order to be changed
        resizableRows:true,       //allow row order to be changed
        placeholder:"Não existem entidades!",
        addRowPos:"bottom",
        pagination:"local",       //paginate the data
        paginationSize:15,         //allow 20 rows per page of data
            columns:[
                {title:"ID", width:150, field:"id", headerSort:false},
                {title:"NOME", field:"nome", headerSort:false, align:"left"},
                {title:"NIF", width:150, field:"nif", headerSort:false, align:"left"},
                {title:"DATA IN", width:150, field:"data_primeira", headerSort:false, align:"left"},
                {title:"DATA ULTIMA", width:150, field:"date_ultima", headerSort:false, align:"left"},
            ],
        pageLoaded:function(pageno){
          getData()
        },
    });

    function getNumberOfNullEntities() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/getcounternullentities.php",  
                    method:"POST",
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                      $(".counter").html(setCommas(response['COUNTER_TOTAL']));
                      $(".counter2").html(setCommas(response['COUNTER_NULL']));
                   },
                   error: function( jqXHR, status ) {
                      errorsCommon(jqXHR, status);                   
                    }
        });
    };

    function getData() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/genulltentsdata.php",  
                    method:"POST",
                    cache: false,
                    data:{last_id: last_id, block_size:block_size},
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                      populateTable(response);
                      last_id = response[response.length - 1]['id'];
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);                    
                    }
        });
    };


    function populateTable(data) {
      if (last_id < 0) {
        table.setData(data);
        return;
      }


       table.addData(data, false);
        // necessary only if table and buttons are not inside the same viewport
        window.scrollTo(0,0);
        window.scrollTo(0, document.body.scrollHeight);
    }



</script>

<?php include('../footer.php'); ?>