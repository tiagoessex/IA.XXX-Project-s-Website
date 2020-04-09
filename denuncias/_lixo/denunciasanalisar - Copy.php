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
         <a href="javascript:void(0)" data-toggle="tooltip" title="Class. 1 => 1 actividade. Class. 2 => actividades em ordem decrescente de probabilidade.">
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
         </ul>
         <!-- Tab panes -->
         <div class="tab-content">
            <div id="tabela-denuncias" class="tab-pane tab-pane active">
               <br>
               <table id="maintable" class="table table-bordered" style="width:100%">
                  <thead>
                     <tr>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white; width:10%;">Denuncia</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">Competencia</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">Actividade</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">Infracção</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #003366;color:white; width:10%;">Operações</th>
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
                           <h5>Utilize <b>Procurar</b> para filtrar.</h5>
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
                        <br>
                        <b>Nº de denúncias alteradas: </b><span id="den_alter">0</span>
                     </div>
                  </div>
               </div>
               <br>
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
                  Ao selecionar <i><mark style="background-color: #33CCFF;border-radius: 5px; padding: 2px;color:black;">Class. 1</mark></i>, o classificador utilizado apenas irá apresentar uma actividade. Ao selecionar <i><mark style="background-color: #CCFF66;border-radius: 5px; padding: 2px;color:black;">Class. 2</mark></i>, o classificador apresentará até todas actividades, em ordem decrescente de probabilidade.
               </p>
               <p>
                  Ao selecionar <i><mark style="background-color: #33CCFF;border-radius: 5px; padding: 2px;color:black;">Não Gravar</mark></i>, poderá visualizar a classificação sem que esta seja gravada na base de dados. 
               </p>
               <p>
                  Para <i>iniciar / resumir</i> a classificação, clique em <button type="button" class="btn btn-primary" style="margin-left: 15px; border-radius: 10px; padding: 5px;color:white;"><i class='fas fa-edit' style='font-size:18px'></i> <b>INICIAR CLASSIFICAÇÃO</b></button>.
                  Pode parar quando quiser, bastando clicar em <button type="button" class="btn btn-danger" style="margin-left: 15px; border-radius: 10px; padding: 5px;color:white;"><i class='fas fa-edit' style='font-size:18px'></i> <b>PARAR CLASSIFICAÇÃO</b></button>.
                  No entanto, se alguma denúncia estiver a ser processada nesse momento, então a classificação apenas será parada após a sua conclusão.
               </p>
               <p>
                  Para visualizar o conteúdo de cada denúncia, basta clicar sobre o botão  <button type="button" class="btn btn-warning btn-xs"><i class='far fa-file-alt'></i></button>
               </p>
               <p>
                  Para <i>editar / alterar</i> o resultado da classificação, basta clicar sobre o botão  <button type="button" class="btn btn-primary btn-xs"><i class='fas fa-pen'></i></button>
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
                     <span class="text-success">Class. 2</span> => 5 a 10 segundos & todas actividades / denúncia, por ordem decrescente de probabilidade
                  </li>
                  <li>
                     Apenas denúncias com mensagens são apresentadas.
                  </li>
                  <li>
                     Todas as alterações serão gravadas na base de dados apenas se <i><mark style="background-color: #00ff00;border-radius: 5px; padding: 2px;color:black;">Gravar</mark></i> estiver selecionado.
                  </li>
                  <li>
                     Se <span class="text-success">Class. 2</span> e <i><mark style="background-color: #00ff00;border-radius: 5px; padding: 2px;color:black;">Gravar</mark></i>, apenas as três primeiras actividades são gravadas.
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

   <!-- CHANGE VALUES MODAL -->
   <div class="modal" id="modal-change">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title-change">Alterar</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body modal-body-change text-justify">
               <div class="form-group">
                  <label for="comp_simp">Competência Simples:</label>
                  <div class="form-check">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="comp_simples" value='comp_simples_XXX'>XXX
                     </label>
                  </div>
                  <div class="form-check">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="comp_simples" value='comp_simples_outra'>Outra
                     </label>
                  </div>
               </div>
               <div class="form-group">
                  <label for="comp">Competência:</label>
                  <div class="form-check">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="comp" value='XXX'>XXX
                     </label>
                  </div>
                  <div class="form-check">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="comp" value='XXX e outra Entidade'>XXX e outra Entidade
                     </label>
                  </div>
                  <div class="form-check">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="comp" value='Tribunal'>Tribunal
                     </label>
                  </div>
                  <div class="form-check">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="comp" value='Outra Entidade'>Outra Entidade
                     </label>
                  </div>
                  <div class="form-check">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="comp" value='Indeterminada'>Indeterminada
                     </label>
                  </div>
               </div>
               <label for="act">Actividade:</label>
               <form class="form-inline">
                  <div class="form-group">
                     <select class="form-control" id="act1">
                        <option value='I'>I</option>
                        <option value='II'>II</option>
                        <option value='III'>III</option>
                        <option value='IV'>IV</option>
                        <option value='V'>V</option>
                        <option value='VI'>VI</option>
                        <option value='VII'>VII</option>
                        <option value='VIII'>VIII</option>
                        <option value='IX'>IX</option>
                        <option value='X'>X</option>
                        <option value='Z'>Z</option>
                     </select>
                     <select class="form-control" id="act2" style="display: none;">
                        <option value='I'>I</option>
                        <option value='II'>II</option>
                        <option value='III'>III</option>
                        <option value='IV'>IV</option>
                        <option value='V'>V</option>
                        <option value='VI'>VI</option>
                        <option value='VII'>VII</option>
                        <option value='VIII'>VIII</option>
                        <option value='IX'>IX</option>
                        <option value='X'>X</option>
                        <option value='Z'>Z</option>
                     </select>
                     <select class="form-control" id="act3" style="display: none;">
                        <option value='I'>I</option>
                        <option value='II'>II</option>
                        <option value='III'>III</option>
                        <option value='IV'>IV</option>
                        <option value='V'>V</option>
                        <option value='VI'>VI</option>
                        <option value='VII'>VII</option>
                        <option value='VIII'>VIII</option>
                        <option value='IX'>IX</option>
                        <option value='X'>X</option>
                        <option value='Z'>Z</option>
                     </select>
                  </div>
               </form>
               <br>
               <div class="form-group">
                  <label for="inf">Infracção:</label>
                  <div class="form-check">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="infraccao" value='Crime'>Crime
                     </label>
                  </div>
                  <div class="form-check">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="infraccao" value='Contraordenação'>Contraordenação
                     </label>
                  </div>
                  <div class="form-check disabled">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="infraccao" value='Conflito de Consumo'>Conflito de Consumo
                     </label>
                  </div>
                  <div class="form-check disabled">
                     <label class="form-check-label">
                     <input type="radio" class="form-check-input" name="infraccao" value='Indefinido'>Indefinido
                     </label>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-success" data-dismiss="modal" onclick="saveDenuncia()">Alterar & Gravar</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
            </div>
         </div>
      </div>
   </div>
   <!-- END OF CHANGE VALUES MODAL -->


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
    var row_2_change = -1;
    // var counter = 0;
    var stop = false;

    // if true then a complaint was selected to be changed
    var change_denuncia = false;


    // stats
    // dynamic stats are calculated here and not through queries
    var denuncias_total = 0;
    var denuncias_analizadas = 0;
    var denuncias_alteradas = 0;


    showTable();


   
//**************************************************
//          GENERAL OPS
//**************************************************


  $(document).ready(function() {
      
        $('[data-toggle="tooltip"]').tooltip({trigger : 'hover'});

        getDenuncias();
        
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
    }

        // reset dynamic stats
    function reset() {
        row = 0;
        denuncias_total = 0;
        row_2_change = -1;
        denuncias_analizadas = 0;
        denuncias_alteradas = 0;
        $("#den_alter").html(0);
        $("#den_class").html(0);
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
         timeout: 120000 //in milliseconds
      });
    }
    

    function saveRow(id_denuncia, competencia, competencia_simples, actividade, infraccao, changed = 0) {

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
                            changed: changed
                    },
                   success:function(response) {
                        console.log("saved: [",id_denuncia, "]", competencia, " # ", competencia_simples," # ",  actividade, " # ", infraccao, " # ", changed);
                        if (changed > 0) {
                            denuncias_alteradas += 1
                            $("#den_alter").html(denuncias_alteradas);
                        }
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                        stopAnalisis();
                        btnClassificar(false);
                    },
                    timeout: 120000 //in milliseconds
        });
    }




    function stopAnalisis() {
        stop = true;
    }

//**************************************************
//          SIMULATION
//**************************************************


    function populateRow(data) {
      data = JSON.parse(data)
     // console.log(data);
      //var id_denuncia = data['id_denuncia'][0];
      var comp_simples = data['comp_model_simples'][0];
      var comp = data['comp_model'][0];
      

      var inf = $('#model').is(":checked")?data['infraccao'][0]:'-';

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

        var row_node = datatable.row(row).node();
        var row_data = datatable.row(row).data()        

        var comp_text = (comp_simples?'XXX':'OUTRO') + ' (' + comp + ')';
        $(row_node.childNodes[1]).text(comp_text);
        $(row_node.childNodes[2]).text(act);
        $(row_node.childNodes[3]).text(inf);

        $(row_node.childNodes[4]).html(`<div class="d-inline-block">
                    <button type="button" class="btn btn-warning btn-xs btn_edit" onclick="showDenuncia(` + data['id_denuncia'] + `)"><i class='far fa-file-alt'></i></button> 
                    <button type="button" class="btn btn-primary btn-xs btn_edit" onclick="changeDenuncia(` + data['id_denuncia'] + `)"><i class='fas fa-pen'></i></button>                     
                </div>`);

                
        // necessary for the export
        row_data['competencia_ai'] = comp_text;
        row_data['actividade_ai'] = act;
        row_data['infraccao_ai'] = inf;

        datatable.draw(false);

        if($('#save').is(":checked"))
          saveRow(row_data['id_denuncia'], comp, comp_simples?1:0, act, inf, 0);        
    }


 



//**************************************************
//          TABLE
//**************************************************
    function showTable() {
        datatable = $('#maintable').DataTable({ 
            //data: data,
            
            "columns": [
                { "data": "id_denuncia" },
                { "data": "competencia_ai" },
                { "data": "actividade_ai" },
                { "data": "infraccao_ai" },
                { "data": "operacoes" }
                
            ],
            
            "columnDefs": [
                {
                    "targets": 2,
                    "className": 'text-center'
                },
                {
                    "targets": 4,
                    "className": 'text-center'
                },
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

        var id_denuncia = $(this).closest('tr').find('td:eq(0)').text();
        var competencia = $(this).closest('tr').find('td:eq(1)').text();
        var actividade = $(this).closest('tr').find('td:eq(2)').text();
        var infraccao = $(this).closest('tr').find('td:eq(3)').text();

        var tr = $(this).closest("tr");
        row_2_change = tr.index();

        if (!competencia || competencia == '') {
          row_2_change = -1;
          return;
        }



        if (change_denuncia) {

          // table values -> modal's form
          var comp_simples = competencia.substr(0, competencia.indexOf(' '));
          if (comp_simples == "XXX")
            $('input:radio[name=comp_simples]')[0].checked = true;
          else
            $('input:radio[name=comp_simples]')[1].checked = true;


          var comp = competencia.substring(
                competencia.lastIndexOf("(") + 1, 
                competencia.lastIndexOf(")")
          );
          if (comp == "XXX")
            $('input:radio[name=comp]')[0].checked = true;
          else if (comp == "XXX e outra Entidade")
            $('input:radio[name=comp]')[1].checked = true;          
          else if (comp == "Tribunais")
            $('input:radio[name=comp]')[2].checked = true;
          else if (comp == "Outra Entidade")
            $('input:radio[name=comp]')[3].checked = true;
          else
             $('input:radio[name=comp]')[4].checked = true;

          if (!$('#model').is(":checked")) {
              $("#act1").val(actividade);
              $("#act2").hide();
              $("#act3").hide();
          } else {
              $("#act2").show();
              $("#act3").show();
              var acts = actividade.split(", ");              
              $("#act1").val(acts[0]);
              if (acts.length > 0)
                $("#act2").val(acts[1]);
              if (acts.length > 1)
                $("#act3").val(acts[2]);
          }


          if (infraccao == "Crime")
            $('input:radio[name=infraccao]')[0].checked = true;
          else if (infraccao == "Contraordenação")
            $('input:radio[name=infraccao]')[1].checked = true;
          else if (infraccao == "Conflito de Consumo")
            $('input:radio[name=infraccao]')[2].checked = true;
          else
             $('input:radio[name=infraccao]')[3].checked = true;


           // now show modal with the respective values
          $('.modal-title-change').html('Alterar - Denúncia [' + id_denuncia + ']');
          $('#modal-change').modal('show')
        }
        

    } );  
    

    function showDenuncia(id) {
        getDenuncia(id);
    }

    function changeDenuncia(id) {
        change_denuncia = true;
        stopAnalisis();
    }

    // timeout to ensure $('#maintable tbody').on('click', 'tr', function ()
    // is called first, otherwise, it might not change
    function saveDenuncia() {
      setTimeout(function(){ saveDenuncia2(); }, 200);
    }

    function saveDenuncia2() {
        // console.log("save denuincia");
        // row_2_change = -1;

            var row_node = datatable.row(row_2_change).node();
            var row_data = datatable.row(row_2_change).data()
            
            var comp = $('input[name=comp]:checked').val();
            var comp_simples = $('input:radio[name=comp_simples]')[0].checked?true:false;
            var act = $("#act1").val();
            if ($('#model').is(":checked")) {
              if ($("#act2").val() != '') {
                  act += ', ' + $("#act2").val();
                  if ($("#act3").val() != '') {
                    act += ', ' + $("#act3").val();
                }
              }
            }

            var inf = $('input[name=infraccao]:checked').val();
            var comp_text = (comp_simples?'XXX':'OUTRO') + ' (' + comp + ')';


            console.log(comp,comp_simples,act,inf,comp_text);
            
            var comp_text = (comp_simples?'XXX':'OUTRO') + ' (' + comp + ')';
            $(row_node.childNodes[1]).text(comp_text);
            $(row_node.childNodes[2]).text(act);
            $(row_node.childNodes[3]).text(inf);
            
                    
            // necessary for the export
            row_data['competencia_ai'] = comp_text;
            row_data['actividade_ai'] = act;
            row_data['infraccao_ai'] = inf;

            datatable.draw(false);



            if($('#save').is(":checked"))
              saveRow(row_data['id_denuncia'], comp, comp_simples?1:0, act, inf, 1);

    }
    

  function getDenuncias() {
        $(".loading").show();
        updateDate();
        reset();
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>denuncias/srv/getdenuncias2.php",  
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
                       showMessage(response, id_denuncia); 
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    }
        });
    };

    function showMessage(msg, id_denuncia) {
        $('.modal-title-message').html('Mensagem - Denúncia [' + id_denuncia + ']');
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

</script>




<?php include('../footer.php'); ?>