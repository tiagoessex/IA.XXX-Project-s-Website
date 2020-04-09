<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>

<style>
  .message {
    color:black; 
    background-color: #CCFFFF;
    font-weight: normal; 
    border: 1px solid #003399;
    border-radius: 5px;
}


.meter { 
      height: 20px;  /* Can be anything */
      position: relative;
      margin: 60px 0 20px 0; /* Just for demo spacing */
      /*background: #555;*/
      background-color: #88bbff;
      -moz-border-radius: 25px;
      -webkit-border-radius: 25px;
      border-radius: 25px;
      padding: 10px;
      -webkit-box-shadow: inset 0 -1px 1px rgba(255,255,255,0.3);
      -moz-box-shadow   : inset 0 -1px 1px rgba(255,255,255,0.3);
      box-shadow        : inset 0 -1px 1px rgba(255,255,255,0.3);

      background-image: 
        -moz-linear-gradient(
          -45deg, 
            rgba(0, 125, 255, .2) 25%, 
            transparent 25%, 
            transparent 50%, 
            rgba(0, 125, 255, .2) 50%, 
            rgba(0, 125, 255, .2) 75%, 
            transparent 75%, 
            transparent
         );
      z-index: 1;
      -webkit-background-size: 50px 50px;
      -moz-background-size: 50px 50px;
      -webkit-animation: move 2s linear infinite;
         -webkit-border-top-right-radius: 8px;
      -webkit-border-bottom-right-radius: 8px;
             -moz-border-radius-topright: 8px;
          -moz-border-radius-bottomright: 8px;
                 border-top-right-radius: 8px;
              border-bottom-right-radius: 8px;
          -webkit-border-top-left-radius: 20px;
       -webkit-border-bottom-left-radius: 20px;
              -moz-border-radius-topleft: 20px;
           -moz-border-radius-bottomleft: 20px;
                  border-top-left-radius: 20px;
               border-bottom-left-radius: 20px;
      overflow: hidden;
      
      

      
      
    }
  
@-webkit-keyframes move {
        0% {
           background-position: 0 0;
        }
        100% {
           background-position: 50px 50px;
        }
}   

</style>

<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/toggleswitch.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/datatables.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>
<link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.0/css/buttons.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/colreorder/1.5.1/css/colReorder.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">


<div class="container-fluid">

   <div class="loading" style="display:none;">Loading&#8230;</div>

   <div class="row">
      <div class="col-sm-4" style="margin-left: 15px; background-color: rgb(106, 90, 205);border-radius: 10px; padding: 5px;color:white;"> 
         De: <input type="date" id="datepicker1" min="<?php echo MIN_DATE; ?>" max="<?php echo MAX_DATE; ?>" value='<?php echo TEMP_DENUNCIAS_IN_START; ?>'>
         Até: <input type="date" id="datepicker2" min="<?php echo MIN_DATE; ?>" max="<?php echo MAX_DATE; ?>" value='<?php echo TEMP_DENUNCIAS_IN_END; ?>'>      
      </div>
      <button type="button" class="btn btn-danger" id="recalc" style="margin-left: 15px; border-radius: 10px; padding: 5px;color:white;" onclick="Actualizar();"><i class='fas fa-redo-alt' style='font-size:18px'></i> Actualizar</button>

      <div style="margin-top: -20px; position: absolute;  right: 5px;" >
         Class. 1
         <a href="javascript:void(0)" data-toggle="tooltip" title="Class. 1 => 1 actividade. Class. 2 => 3 actividades.">
         <label class="switchA">
         <input type="checkbox" id="model">
         <span class="sliderA"></span>
         </label>
          </a>
         Class. 2
      </div>
   </div>

   <br>

   <div class="row">
      <div class="col-sm-3"></div>
      <div class="col-sm-6">
         <button type="button" class="btn btn-primary btn-block" id="classificar" style="margin-left: 15px; border-radius: 10px; padding: 5px;color:white;"><i class='fas fa-edit' style='font-size:18px'></i> <b>INICIAR CLASSIFICAÇÃO</b></button>
      </div>
      <div class="col-sm-3">


      <div style="margin-top: -20px; position: absolute;  right: 5px;zoom: 0.8;-moz-transform: scale(0.8);" >
         Não Gravar
         <a href="javascript:void(0)" data-toggle="tooltip" title="Gravar ou não na base de dados.">
         <label class="switchA">
         <input type="checkbox" id="save">
         <span class="sliderA"></span>
         </label>
        </a>
         Gravar
      </div>


      </div>
   </div>

   <hr>

   <div class="row">
      <div class="col-sm-12">
         <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
               <a class="nav-link active" data-toggle="tab" href="#tabela-denuncias">Tabela</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" data-toggle="tab" href="#stats">Stats</a>
            </li>
           <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#confusion">Matriz de Confusão</a>
            </li>
         </ul>
         <!-- Tab panes -->
         <div class="tab-content">
            <div id="tabela-denuncias" class="tab-pane tab-pane active">
               <br>
               <table id="maintable" class="table table-bordered" style="width:100%">
                  <thead>
                     <tr>
                        <th></th>
                        <th colspan="2" class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">Competencia</th>
                        <th colspan="2" class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">Actividade</th>
                        <th colspan="2" class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">Infracção</th>
                     </tr>
                     <tr>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white; width:10%;">Denuncia</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #3399FF;color:white; width:20%">Real</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #FF9900;color:white;width:20%">Previsto</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #3399FF;color:white;width:20%">Real</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #FF9900;color:white;width:20%">Previsto</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #3399FF;color:white;width:5%">Real</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #FF9900;color:white;width:5%">Previsto</th>
                     </tr>
                  </thead>
                  <tbody>                   
                  </tbody>
                  <tfoot>
                  </tfoot>
               </table>

               <br>
  
  <div class="hidden-message" style="display: none;">
    <div class="row justify-content-center">
      <div class="message">
        <div class="col-sm-12 text-center table-message">
          <h5>Clique sobre uma denúncia para ver o respectivo texto.</h5>
        </div>
      </div>
    </div>
  </div>

            </div>
            <div id="stats" class="tab-pane tab-pane fade">
               <br>
               <div class="row">
                  <div class="message" style="margin-right: 20px;margin-left: 10px;">
                     <div class="col-sm-12">
                        <b>Nº de denúncias a classificar (com mensagem): </b><span id="den_2_class"><i class="fa fa-spinner fa-spin  fa-fw"></i></span>
                        <br>
                        <b>% de denúncias já classificadas: </b><span id="den_class">0</span> %
                     </div>
                  </div>
               </div>
               <br>
  
               <div class="row">
                  <div class="col-sm-6 text-center">
                    <h3>Distribuição de Actividades (real)</h3>
                    <canvas id="graph_dist_act_real" width="undefined" height="undefined"></canvas>
                  </div>
                  <div class="col-sm-6 text-center">
                    <h3>Distribuição de Actividades (previsto)</h3>
                    <canvas id="graph_dist_act_previsto" width="undefined" height="undefined"></canvas>
                  </div>
               </div>
               <br>
               <h3 class="text-center">Acertos/Falhas</h3>

               <div class="row">
                  <div class="col-sm-6">
                     <canvas id="graph_actividade" width="undefined" height="undefined"></canvas>
                  </div>
                  <div class="col-sm-6">
                     <canvas id="graph_infraccao" width="undefined" height="undefined"></canvas>
                  </div>
               </div>
               <br><br>
               <div class="row">
                  <div class="col-sm-6">
                     <canvas id="graph_competencia" width="undefined" height="undefined"></canvas>
                  </div>
                  <div class="col-sm-6">
                     <canvas id="graph_competencia_simples" width="undefined" height="undefined"></canvas>
                  </div>
               </div>
            </div>
            <div id="confusion" class="tab-pane tab-pane fade">
               <div class="row">
                  <div class="col-sm-12">
                    <?php include('_confusion_matrix.php'); ?>
                  </div>
               </div>
             </div>
         </div>
      </div>
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
                Neste página poderá visualizar em tempo-real o resultado da classificação sistemática das denúncias (dentro uma janela temporal), por classificar, por parte dos classificadores desenvolvidos.
              </p>
              <p>
                Ao selecionar <i><mark style="background-color: #33CCFF;border-radius: 5px; padding: 2px;color:black;">Class. 1</mark></i>, o classificador utilizado apenas irá apresentar uma actividade. Ao selecionar <i><mark style="background-color: #CCFF66;border-radius: 5px; padding: 2px;color:black;">Class. 2</mark></i>, o classificador apresentará até três actividades, em ordem decrescente de probabilidade.
              </p>   
              <p>
                Ao selecionar <i><mark style="background-color: #33CCFF;border-radius: 5px; padding: 2px;color:black;">Não Gravar</mark></i>, poderá visualizar a classificação sem que esta seja gravada na base de dados. 
              </p>
              <p>
                Para visualizar o conteúdo de cada denúncia, basta clicar sobre a linha correspondente.
              </p>
              <br>            
              <p>
                Por defeito, apenas são apresentadas as denúncias do último mês.
                Para classificar as denúncias de um outro periodo temporal, introduza o novo intervalo e clique em <i><mark style="border-radius: 5px; padding: 2px;color:white;" class="bg-danger">Actualizar</mark></i>.
              </p>
              <p>
                Notas
              </p>
              <ul>
                <li>Intervalos temporais largos, podem implicar um número excessivo de denúncias, que poderá levar à "lentidão" do sistema dependendo da plataforma do utilizador.
                Recomenda-se por isso, que selecione intervalos temporais não superiores a <i>3 meses</i>.
                </li>
                <li>
                  Neste momento, tanto os tempos de classificação de cada denúncia como o número de actividades varia com o classificador selecionado:<br>
                  <span class="text-primary">Class. 1</span> => 2 a 10 segundos & 1 actividade / denúncia<br>
                  <span class="text-success">Class. 2</span> => 5 a 10 segundos & 3 actividades / denúncia
                </li>
                <li>
                  Apenas denúncias com mensagens são apresentadas.
                </li>
              </ul>

            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
            </div>
         </div>
      </div>
   </div>
   <!-- END OF HELP MODAL -->


   <!-- DENUNCIA/RECLAMACAO MESSAGE MODAL -->
   <div class="modal" id="modal_message">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title modal-title-message">Mensagem</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-content-message">
               <div class="modal-body modal-body-message">
                  <div class="embed-responsive embed-responsive-16by9">
                     <iframe id="iframe" class="embed-responsive-item" allowfullscreen></iframe>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
            </div>
         </div>
      </div>
   </div>
   <!-- END OF DENUNCIA/RECLAMACAO MESSAGE MODAL -->


   <!-- TEMPORARY MESSAGE MODAL -->
   <div class="modal" id="modal_temporary">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title modal-title-message">Mensagem</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-content-message">
               <div class="modal-body modal-body-message text-justify">
                  <h4>Atenção: de momento, ignore os valores previstos das infrações.</h4>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
            </div>
         </div>
      </div>
   </div>
   <!-- TEMPORARY MESSAGE MODAL -->




</div>



<script src="<?php echo DOMAIN_URL; ?>js/utils.js"></script>


<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>



<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.colVis.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>

<!--
<script src="https://cdn.datatables.net/plug-ins/1.10.20/api/row().show().js"></script>
-->


<script>

    var ano_start = null;
    var mes_start = null;
    var ano_end = null;
    var mes_end = null;

    var datatable = null;

    // simulation
    var timer = null;
    var timeOuts = []; 
    var row = 0; 
    // var counter = 0;
    var stop = false;


    // stats
    // dynamic stats are calculated here and not through queries
    var denuncias_total = 0;
    var denuncias_analizadas = 0;
    var competencia_ok = 0;
    var competencia_soso = 0;
    var infraccao_ok = 0;
    var infraccao_soso = 0;
    var actividade_ok = 0;
    var actividade_in_3 = 0;

    var    dist_actividade_real = {
          'I':0,
          'II':0,
          'III':0,
          'IV':0,
          'V':0,
          'VI':0,
          'VII':0,
          'VIII':0,
          'IX':0,
          'X':0,
          'Z':0,
        };

    var    dist_actividade_previsto = {
          'I':0,
          'II':0,
          'III':0,
          'IV':0,
          'V':0,
          'VI':0,
          'VII':0,
          'VIII':0,
          'IX':0,
          'X':0,
          'Z':0,
        };

    showTable();


   
//**************************************************
//          GENERAL OPS
//**************************************************


	$(document).ready(function() {
      
        $('[data-toggle="tooltip"]').tooltip({trigger : 'hover'});

        getDenuncias();
        setDataGraphActPrevisto();
        setGraphsRightWrong(true);

       $('#modal_temporary').modal('show');

        

    } );

    // this prevent the #crap of being added to the url
    $('.nav-tabs').click(function(event){
        event.preventDefault();        
    });

    // this solves the table header/body disalignment problem
    // when in stats new data is displayed
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
      var target = $(e.target).attr("href") // activated tab
      if (target == "#tabela-denuncias") {
        datatable.draw();
      }
    });



    function updateDate() {
        var start = $('#datepicker1').val().split('-');
        ano_start = start[0];
        mes_start = start[1];
        var end = $('#datepicker2').val().split('-');
        ano_end = end[0];
        mes_end = end[1];        
    }


    function Actualizar() {
        btnClassificar(false);
        stopAnalisis();
        reset();
        getDenuncias();
        setDataGraphActPrevisto();
        setGraphsRightWrong(true);
    }

        // reset dynamic stats
    function reset() {
        row = 0;
        denuncias_total = 0;

        competencia_ok = 0;
        competencia_simples = 0;
        infraccao_ok = 0;
        actividade_ok = 0;
        actividade_in_3 = 0;

        dist_actividade_real = {
          'I':0,
          'II':0,
          'III':0,
          'IV':0,
          'V':0,
          'VI':0,
          'VII':0,
          'VIII':0,
          'IX':0,
          'X':0,
          'Z':0,
        };

        dist_actividade_previsto = {
          'I':0,
          'II':0,
          'III':0,
          'IV':0,
          'V':0,
          'VI':0,
          'VII':0,
          'VIII':0,
          'IX':0,
          'X':0,
          'Z':0,
        };

        resetTable('-');
    }


    function btnClassificar(onStart) {
      if (onStart) {
          $("#classificar").removeClass('btn-primary');
          $("#classificar").addClass('btn-danger');
          $("#classificar").html("<i class='far fa-hand-paper' style='font-size:18px'></i> <b>STOP CLASSIFICAÇÃO</b>");
      } else {
          $("#classificar").removeClass('btn-danger');
          $("#classificar").addClass('btn-primary');
          $("#classificar").html("<i class='fas fa-edit' style='font-size:18px'></i> <b>INICIAR  CLASSIFICAÇÃO</b>");
      }
    }

    $("#classificar").click(function(){
        if ($("#classificar").hasClass('btn-primary')) {
            btnClassificar(true);
            stop = false;
            analizarDenuncias();
        } else {
            btnClassificar(false);
            stopAnalisis();
        }        
    }); 


    $('#model').change(function() {
        btnClassificar(false);
        stopAnalisis();
        getDenuncias();
        setDataGraphActPrevisto();
        setGraphsRightWrong(true);
        reset();
    });


//**************************************************
//          COMPLAIN ANALYSIS
//**************************************************

// recursive function
function analizarDenuncias() {

      // exit condition
      if (!(!stop && row < datatable.data().length)) {  
            btnClassificar(false);
            stopAnalisis();
            return;
      }
 
        datatable.row(row).draw().show().draw(false)
        
        setColor(datatable.row(row).node(),'meter');
          
          var id_denuncia = datatable.row(row).data()['id_denuncia'];
      $.ajax({
          url: "<?php echo PYTHON_SRV_DOOR; ?>getanalysis",
         contentType: 'application/json;charset=8859-1',
         data: JSON.stringify({'g_id_denuncia':id_denuncia, 'model': $("#model").is(":checked")?2:1}, null, '\t'),
         type: 'POST',

         success: function(data){  
            populateRow(data);
            CheckRowValues(row);
            setDataGraphActPrevisto();
            setGraphsRightWrong();
            removeColor(datatable.row(row).node(),'meter');
            row++;
            denuncias_analizadas += 1;
            $("#den_class").html((denuncias_analizadas / denuncias_total * 100).toFixed(1));            
            analizarDenuncias();
         },
         error: function(data){
            //console.log("ERROR > ", data);
            errorsCommonPython(data);
            stopAnalisis();
            btnClassificar(false);
            removeColor(datatable.row(row).node(),'meter');
         },
         timeout: 240000 //in milliseconds
      });
    }
    

    function saveRow(id_denuncia, competencia, competencia_simples, actividade, infraccao) {
        console.log("[",id_denuncia, "]", competencia, " # ",  actividade, " # ", infraccao);

        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>denuncias/srv/saveanalysis.php",  
                    method:"POST",
                    data:{
                            model: $('#model').is(":checked")?2:1,
                            competencia:competencia,
                            actividade:actividade,
                            infraccao:infraccao,
                            id_denuncia:id_denuncia,
                            competencia_simples: competencia_simples,
                            changed: 0
                    },
                   success:function(response) {
                        console.log("saved: [",id_denuncia, "]", competencia, " # ", competencia_simples," # ",  actividade, " # ", infraccao);
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                        stopAnalisis();
                        btnClassificar(false);
                    }
        });
    }




    function stopAnalisis() {
        stop = true;
    }

//**************************************************
//          SIMULATION
//**************************************************


    var INFRACCOES= ['Crime','Contraordenação','Conflito de Consumo','Indefinido'];
    

    function getRandomInfraccao() {
      return INFRACCOES[Math.floor(Math.random()*INFRACCOES.length)];
    }

    function populateRow(data) {
      data = JSON.parse(data)
     // console.log(data);
      //var id_denuncia = data['id_denuncia'][0];
      var comp_simples = data['comp_model_simples'][0];
      var comp = data['comp_model'][0];
      
      var inf = getRandomInfraccao();

      var act;
      if (!$('#model').is(":checked")) {
          act = data['actividades_model'][0].substr(0, data['actividades_model'][0].indexOf(' '));
      } else {
          act = '';
          var size = data['actividades_model'].length>3?3:data['actividades_model'].length;
          for (var i = 0; i < size; i++) {
            act += data['actividades_model'][i].substr(0, data['actividades_model'][i].indexOf(' '));
            if (i < size - 1)
              act += ', ';
          }
      }

      //console.log(act);

        var row_node = datatable.row(row).node();
        var row_data = datatable.row(row).data()
        

        var comp_text = (comp_simples?'XXX':'OUTRO') + ' (' + comp + ')';
        $(row_node.childNodes[2]).text(comp_text);
        $(row_node.childNodes[4]).text(act);
        $(row_node.childNodes[6]).text(inf);

        // necessary for the export
        row_data['competencia_ai'] = comp_text;
        row_data['actividade_ai'] = act;
        row_data['infraccao_ai'] = inf;

        datatable.draw(false);

        if($('#save').is(":checked"))
          saveRow(row_data['id_denuncia'], comp, comp_simples?1:0, act, inf);        
    }


 



//**************************************************
//          TABLE
//**************************************************
    function showTable() {
        datatable = $('#maintable').DataTable({ 
            //data: data,
            
            "columns": [
                { "data": "id_denuncia" },
                { "data": "competencia" },
                { "data": "competencia_ai" },
                { "data": "actividade" },
                { "data": "actividade_ai" },
                { "data": "infraccao" },
                { "data": "infraccao_ai" }
                
            ],
            "columnDefs": [
                {
                    "targets": 3,
                    "className": 'text-center'
                },
                {
                    "targets": 4,
                    "className": 'text-center'
                }
            ],

            "scrollX": true,
            "bDestroy": true,
            "pagingType": "full_numbers",

            "order": [[ 0, "asc" ]],


            "language": {
                "lengthMenu": "Mostrar _MENU_ denúncias por página",
                "zeroRecords": "Não existem denúncias a classificar. Selecione outro intervalo temporal!",
                "info": "Mostrando _PAGE_ de _PAGES_",
                "infoEmpty": "Não existem denúncias a classificar",
                "infoFiltered": "(filtrado de um total de _MAX_ denúncias)",
                 "paginate": {
                      "previous": "<i class='fas fa-angle-left'></i>",
                      "next": "<i class='fas fa-angle-right'></i>",
                      "first": "<i class='fas fa-angle-double-left'></i>",
                      "last": "<i class='fas fa-angle-double-right'></i>"
                  },
                "search" : "Procurar",
                buttons: {
                    pageLength: {
                        _: "Mostrar %d"/*,
                        '-1': "Mostrar todas"*/
                    }
                }
            },
             lengthMenu: [
                [ 5, 10, 25, 50, -1 ],
                [ '5 denúncias', '10 denúncias', '25 denúncias', '50 denúncias'/*, 'Mostrar todas' */]
            ],
            lengthChange: false,
            //dom: 'Bfrtip',
            buttons: ['pageLength', { extend: 'colvis', text: 'Colunas', columns: ':not(:first-child)'}, 'copy', 'excel', 'csv', 'pdf', 'print']

           
              


        });

        datatable.buttons().container().appendTo( '#maintable_wrapper .col-md-6:eq(0)' );


        // from https://github.com/DataTables/Plugins/blob/master/api/row().show().js
        // used to show the page of the selected row
        $.fn.dataTable.Api.register('row().show()', function() {
            var page_info = this.table().page.info();
            // Get row index
            var new_row_index = this.index();
            // Row position
            var row_position = this.table().rows()[0].indexOf( new_row_index );
            // Already on right page ?
            if( row_position >= page_info.start && row_position < page_info.end ) {
                // Return row object
                return this;
            }
            // Find page number
            var page_to_display = Math.floor( row_position / this.table().page.len() );
            // Go to that page
            this.table().page( page_to_display );
            // Return row object
            return this;
        });


    }

    

    $('#maintable tbody').on('click', 'tr', function () {
            if (!$(this).hasClass('row_selected_purple')) {
                $("#maintable tbody tr").removeClass('row_selected_purple');     
                $(this).addClass('row_selected_purple');
                // this only works if columns' order is immutable
                var id = $(this).closest('tr').find('td').first().text();
                getDenuncia(id);
            } else {
                $("#maintable tbody tr").removeClass('row_selected_purple');
            } 
    } );  




  function getDenuncias() {
        $(".loading").show();
        updateDate();
        reset();
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>denuncias/srv/getdenuncias.php",  
                    method:"POST",
                    data:{
                            model: ($('#model').is(":checked"))?2:1,
                            ano_start:ano_start, 
                            mes_start:mes_start, 
                            ano_end:ano_end, 
                            mes_end:mes_end
                    },
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        populateTable(response);
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                        $(".loading").hide();
                    },
                    timeout: 240000 //in milliseconds
        });
    };

    function populateTable(data) {
        datatable.clear();
        datatable.rows.add(data);
        datatable.draw();

        denuncias_total = datatable.data().length;
        $("#den_2_class").html(setCommas(denuncias_total));
        $("#den_class").html(0);
        CheckTableValues();
        setDataGraphActReal();

        $(".loading").hide();

        if (datatable.data().length > 0) {
          $(".hidden-message").show();
        }
    }


   function getDenuncia(id_denuncia) {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/messagecontent.php",  
                    method:"POST",
                    data:{id_correspondencia: id_denuncia, den_or_rec: 'D'},
                    cache: false,
                    dataType:"text", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                       showMessage(response); 
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    }
        });
    };

    function showMessage(msg) {
        if (msg == '' || !msg) 
          msg = "<h1 class='text-center'>NÃO EXISTE MENSAGEM</h1>";
        var doc = document.getElementById("iframe").contentWindow.document;
        doc.open();
        if (msg.indexOf('<html') !== -1)
           doc.write(msg);
        else
            doc.write('<pre>' + msg + '</pre>');        
        doc.close();
        $('#modal_message').modal('show');
    }


    // color must be css class colors
    function setColor(element, color ) {
        //var value = element.textContent;
        //element.classList.remove("invalid");
        element.classList.add(color);
    }

    function removeColor(element, color ) {
        //var value = element.textContent;
        element.classList.remove(color);
    }

    function CheckRowValues(rownumber) {
        
            var row_node = datatable.row(rownumber).node();

            // legacy
            var cols = [];
            for (var key in row_node.childNodes) {
              cols.push(row_node.childNodes[key]);
            }


            // competencia
            var col_real = $(cols[1]).text().toUpperCase();
            var col_model = $(cols[2]).text().toUpperCase();
            var comp_complex = col_model.substring(
                col_model.lastIndexOf("(") + 1, 
                col_model.lastIndexOf(")")
            );
            var comp_simples = col_model.substr(0, col_model.indexOf(' '));

            if ((col_real.includes('XXX') && comp_simples == 'XXX')) {
                competencia_simples += 1;
                if (col_real == comp_complex || col_real.replace('XXX','').includes(comp_complex)) {
                    competencia_ok += 1;
                    setColor(cols[2],'ok');
                } else {
                  setColor(cols[2],'so-so');
                }
            } else if (col_real == comp_complex && col_real.replace('XXX','').includes(comp_complex)) {
                    competencia_simples += 1;
                    competencia_ok += 1;
                    setColor(cols[2],'ok');
            } else if (col_real.includes(comp_complex)) {
                competencia_ok += 1;
                setColor(cols[2],'so-so');                
            } else {
                setColor(cols[2],'not-ok');
            }


            // actividade
            col_real = $(cols[3]).text().toUpperCase();
            col_model = $(cols[4]).text().toUpperCase();
            var temp = col_real.substr(0, col_real.indexOf('.'));
            if (temp != '') {
              col_real = temp;
            }
            dist_actividade_previsto[col_model] += 1;
            var value = getTableValue(col_real, col_model);
            if (value == '-')
              value = 0;
            else
              value = parseInt(value);
            setTableValue(col_real, col_model, value + 1);
            if ($('#model').is(":checked")) {                
                if (col_real == col_model) {
                    setColor(cols[4],'ok');
                    actividade_ok += 1;
                } else if (hasWord(col_model, col_real)) {
                    setColor(cols[4],'so-so');
                    actividade_in_3 += 1;   // ????
                    actividade_ok += 1;     // ?????
                } else {
                    setColor(cols[4],'not-ok');
                }
            } else {
                if (col_real == col_model) {
                    setColor(cols[4],'ok');
                    actividade_ok += 1;
                } else {
                    setColor(cols[4],'not-ok');
                }
            }


            // infraccao
            col_real = $(cols[5]).text().toUpperCase();
            col_model = $(cols[6]).text().toUpperCase();
            if (col_real == col_model) {
                setColor(cols[6],'ok');
                infraccao_ok += 1;
            } else if ((col_real.includes(col_model) || col_model.includes(col_real)) && (col_real != '' && col_model != '')) {
                setColor(cols[6],'so-so');
                infraccao_soso += 1;
            } else {
                setColor(cols[6],'not-ok');
            }
    }


    function CheckTableValues() {
        datatable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            
            // legacy
            var cols = [];
            $(this.node()).find('td').each (function( column, td) {
                cols.push(td);
            });

            // actividade
            col_real = $(cols[3]).text().toUpperCase();
            col_model = $(cols[4]).text().toUpperCase();
            var temp = col_real.substr(0, col_real.indexOf('.'));
            if (temp != '') {
              col_real = temp;
            }

            dist_actividade_real[col_real] += 1;

          });
    }



    // text format: 'word1,word2,...'
    function hasWord(text, word) {
        var words = text.split(',');
        //words.shift();
        for (x in words) {
            if (words[x].trim() === word) {
                return true;
            }
        }
        return false;
    }


//**************************************************
//          GRAPHICS
//**************************************************


var ctx_act_real = document.getElementById('graph_dist_act_real').getContext('2d');
var chart_actividade_real = new Chart(ctx_act_real, {
    type: 'bar',
    responsive : true,
    data: {
        datasets: [{
            label: ' # denuncias',
            data: [],
            backgroundColor: 'rgba(255, 99, 255, 0.2)',
            borderColor:'rgba(255, 99, 255, 1)',
            borderWidth: 1,
            datalabels: {
                align: 'top',
                color: '#000000',
                font: {
                    size: '18'
                }
            }
        }]
    },
    options: {
        title: {
            display: false,
            text: 'Denuncias por Actividade'
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                },
            }],
            xAxes: [{
                gridLines: {
                  drawBorder: false,
                  display: false,
                  //color: "grey"
                },
                ticks: {
                  autoSkip: false,
                  display: true,
                  fontSize: 12,
                 // fontColor: 'red',
                  min: 0
                }
            }]
        },
        showDatapoints: true,
        plugins: {
            labels: {
                render: 'value'
            }
        }  
    }
});

  function setDataGraphActReal() {
       
        var labels = [];
        var values = [];

        var i = 0;
        for (var codigo in dist_actividade_real) {
            labels[i] = codigo;
            values[i] = dist_actividade_real[codigo];
            i += 1;
        }


        chart_actividade_real.data.labels = labels;
        chart_actividade_real.data.datasets[0].data = values;
        chart_actividade_real.update();

    }




var ctx_act_previsto = document.getElementById('graph_dist_act_previsto').getContext('2d');
var chart_actividade_previsto = new Chart(ctx_act_previsto, {
    type: 'bar',
    responsive : true,
    
    data: {
        datasets: [{
            label: ' # denuncias',
            data: [],
            backgroundColor: 'rgba(99, 255, 255, 0.2)',
            borderColor:'rgba(99, 255, 255, 1)',
            borderWidth: 1,
            datalabels: {
                align: 'top',
                color: '#000000',
                font: {
                    size: '18'
                }
            }
        }]
    },
    options: {
        title: {
            display: false,
            text: 'Denuncias por Actividade'
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                },
            }],
            xAxes: [{
                gridLines: {
                  drawBorder: false,
                  display: false,
                  //color: "grey"
                },
                ticks: {
                  autoSkip: false,
                  display: true,
                  fontSize: 12,
                 // fontColor: 'red',
                  min: 0
                }
            }]
        },
        showDatapoints: true,
        plugins: {
            labels: {
                render: 'value'
            }
        },
        events: []     
    }
});

  function setDataGraphActPrevisto() {
       
        var labels = [];
        var values = [];

        var i = 0;
        for (var codigo in dist_actividade_previsto) {
            labels[i] = codigo;
            values[i] = dist_actividade_previsto[codigo];
            i += 1;
        }


        chart_actividade_previsto.data.labels = labels;
        chart_actividade_previsto.data.datasets[0].data = values;
        chart_actividade_previsto.update();

    }




var ctx_comp = document.getElementById('graph_competencia').getContext('2d');
var chart_competencia = new Chart(ctx_comp, {
    type: 'doughnut',
    responsive : true,
    data: {
        datasets: [{
            label: ' # denuncias',
            data: [],
            backgroundColor: ['#006600', '#FF0000'],
            borderWidth: 1,
             datalabels: {
               // align: 'top',
                color: '#FFFFFF',
                font: {
                    size: '24'
                }
            }
        }]
    },
    options: {
        title: {
            display: true,
            text: 'Competência'
        },

        showDatapoints: true,
        events: [],
        plugins: {
            labels: {
                render: 'value',
                fontSize: 14,
                fontColor: '#fff',
            }
        }  
    }
});



var ctx_comp_simples = document.getElementById('graph_competencia_simples').getContext('2d');
var chart_competencia_simples = new Chart(ctx_comp_simples, {
    type: 'doughnut',
    responsive : true,
    data: {
        datasets: [{
            label: ' # denuncias',
            data: [],
            backgroundColor: ['#006600', '#FF0000'],
            borderWidth: 1,
             datalabels: {
               // align: 'top',
                color: '#FFFFFF',
                font: {
                    size: '24'
                }
            }
        }]
    },
    options: {
        title: {
            display: true,
            text: 'Competência Simples'
        },

        showDatapoints: true,
        events: [],
        plugins: {
            labels: {
                render: 'value',
                fontSize: 14,
                fontColor: '#fff',
            }
        }
    }
});




var ctx_actividade = document.getElementById('graph_actividade').getContext('2d');
var chart_actividade = new Chart(ctx_actividade, {
    type: 'doughnut',
    responsive : true,
    data: {
        datasets: [{
            label: ' # denuncias',
            data: [],
            backgroundColor: ['#006600', '#FF0000'],
            borderWidth: 1,
             datalabels: {
               // align: 'top',
                color: '#FFFFFF',
                font: {
                    size: '24'
                }
            }
        }]
    },
    options: {
        title: {
            display: true,
            text: 'Actividade'
        },

        showDatapoints: true,
        events: [],
        plugins: {
            labels: {
                render: 'value',
                fontSize: 14,
                fontColor: '#fff',
            }
        }
    }
});




var ctx_infraccao = document.getElementById('graph_infraccao').getContext('2d');
var chart_infraccao = new Chart(ctx_infraccao, {
    type: 'doughnut',
    responsive : true,
    data: {
        datasets: [{
            label: ' # denuncias',
            data: [],
            backgroundColor: ['#006600', '#FF0000'],
            borderWidth: 1,
             datalabels: {
               // align: 'top',
                color: '#FFFFFF',
                font: {
                    size: '24'
                }
            }
        }]
    },
    options: {
        title: {
            display: true,
            text: 'Class. Infracção'
        },

        showDatapoints: true,
        events: [],
        plugins: {
            labels: {
                render: 'value',
                fontSize: 14,
                fontColor: '#fff',
            }
        }
    }
});


    function setGraphsRightWrong(initial = false) {
        var labels = ['OK','NOT OK'];
        var values = [competencia_ok,denuncias_analizadas-competencia_ok];
        if (initial) {
            values = [1,0];
        }
        chart_competencia.data.labels = labels;
        chart_competencia.data.datasets[0].data = values;
        chart_competencia.update();


        labels = ['OK','NOT OK'];
        if (initial)
            values = [1,0];
        else 
          values = [competencia_simples,denuncias_analizadas-competencia_simples];
        chart_competencia_simples.data.labels = labels;
        chart_competencia_simples.data.datasets[0].data = values;
        chart_competencia_simples.update();


        labels = ['OK','NOT OK'];
        if (initial)
            values = [1,0];
        else 
          values = [infraccao_ok,denuncias_analizadas-infraccao_ok];
        chart_infraccao.data.labels = labels;
        chart_infraccao.data.datasets[0].data = values;
        chart_infraccao.update();


        labels = ['OK','NOT OK'];
        if (initial)
            values = [1,0];
        else 
          values = [actividade_ok,denuncias_analizadas-actividade_ok];
        chart_actividade.data.labels = labels;
        chart_actividade.data.datasets[0].data = values;
        chart_actividade.update();

    }



</script>




<?php include('../footer.php'); ?>