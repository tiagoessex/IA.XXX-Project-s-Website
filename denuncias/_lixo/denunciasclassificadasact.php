<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>
<?php
  include_once('../settings/config.php');
  include('../settings/database.php');
  

  $database = new Database();

  $conn = $database->getConnection();
  if (!$conn) die("Error connecting to database!");

  $query = "
    SELECT
      ID_ACT AS id, 
      COALESCE(PAI_ID_ACT,'#') AS parent,
      COALESCE(CONCAT (CODIGO, ' - ', DESIGNACAO),'ACTIVIDADES') AS text
    FROM
      ACTIVIDADE
  ";


  try {  
    $result=$conn->query($query);  
  } catch (PDOException $e) {
      die($e->getMessage());
  }


  $json = array();
  foreach ($result->fetchAll(PDO::FETCH_ASSOC) as $row)
  {
      $json[]= array(
          'id' => $row['id'],
          'parent' => $row['parent'],
          'text' => $row['text'],
      );
  }
?>



<style>
  .message {
    color:black; 
    background-color: #CCFFFF;
    font-weight: normal; 
    border: 1px solid #3399FF;
    border-radius: 5px;
    width: 100%;
}
</style>


<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/toggleswitch.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/datatables.css"/>
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/colreorder/1.5.1/css/colReorder.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>



<div class="container-fluid">    

<div class="loading" style="display:none;">Loading&#8230;</div>

<div class="row"> 
        <div class="col-sm-4" style="margin-left: 15px; background-color: rgb(106, 90, 205);border-radius: 10px; padding: 5px;color:white;"> 
            De: <input type="date" id="datepicker1" min="<?php echo MIN_DATE; ?>" max="<?php echo MAX_DATE; ?>" value='<?php echo TEMP_DENUNCIAS_OUT_START; ?>'>
            Até: <input type="date" id="datepicker2" min="<?php echo MIN_DATE; ?>" max="<?php echo MAX_DATE; ?>" value='<?php echo TEMP_DENUNCIAS_OUT_END; ?>'>      
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

<hr>

<div class="row">

    <div class="col-sm-4" style="height:90vh; width:100%; overflow-x:scroll;overflow-y:scroll;">
        <h5 class="display-9 text-center" style="background-color: #99CCFF;border-radius: 10px; padding: 5px;color:white;">Selecione uma Actividade</h5><br>
        <div id="jstree"></div>

        <br><hr>
        <div class="checkbox">
             <a href="javascript:void(0)" data-toggle="tooltip" title="Check para selecionar apenas as denúncias com a actividade exacta e não os filhos. Atenção: se unchecked o tempo de busca poderá ser excessivo!">
            <label style="background-color: #99CCFF;border-radius: 10px; padding: 5px;color:black;"><input type="checkbox" id="exacto" value="exacto" checked> Exacto</label></a>
        </div>

    
    </div>
  
    <div class="col-sm-8">

          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#tabela-denuncias">Tabela Real vs Previsto</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#stats">Stats</a>
            </li>
          </ul>


<!-- Tab panes -->
  <div class="tab-content">
    <div id="tabela-denuncias" class="tab-pane tab-pane active"><br>
        <table id="maintable" class="table table-striped table-bordered" style="width:100%">
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
        <div class="col-sm-12 text-center">
          <h5>Clique sobre uma denúncia para ver o respectivo texto.</h5>
        </div>
      </div>
    </div>
  </div>



    </div>
    <div id="stats" class="tab-pane tab-pane fade"><br>
            <div class="row">
                <div class="message" style="margin-right: 20px;margin-left: 10px;">
                <div class="col-sm-12"> 
                    <b>Nº total de denúncias: </b><span id="den_total"><i class="fa fa-spinner fa-spin  fa-fw"></i></span>
                    <br>
                    <b>Nº total de denúncias com mensagem: </b><span id="den_total_with_message"><i class="fa fa-spinner fa-spin  fa-fw"></i></span>
                    <br>                    
                    <b>% de denúncias classificadas: </b><span id="den_total_class"><i class="fa fa-spinner fa-spin  fa-fw"></i></span> %
                    <br>
                    <b>% de denúncias classificadas (1): </b><span id="den_total_class1"><i class="fa fa-spinner fa-spin  fa-fw"></i></span> %
                    <br>
                    <b>% de denúncias classificadas (2): </b><span id="den_total_class2"><i class="fa fa-spinner fa-spin  fa-fw"></i></span> %
                    <br>
                </div>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="message" style="margin-right: 20px;margin-left: 10px;">
                <div class="col-sm-12"> 
                    <b>Nº de denúncias no intervalo temporal: </b><span id="den_time"><i class="fa fa-spinner fa-spin  fa-fw"></i></span>
                    <br>
                    <b>% de denúncias classificadas no intervalo temporal: </b><span id="den_time_class"><i class="fa fa-spinner fa-spin  fa-fw"></i></span> %
                    <br>
                    <b>% de denúncias classificadas (1) no intervalo temporal: </b><span id="den_time_class1"><i class="fa fa-spinner fa-spin  fa-fw"></i></span> %
                    <br>
                    <b>% de denúncias classificadas (2) no intervalo temporal: </b><span id="den_time_class2"><i class="fa fa-spinner fa-spin  fa-fw"></i></span> %
                    <br>
                </div>
                </div>
            </div>
            <br>
            <div class="text-center">
              <h5>Selecione uma actividade para visualizar o resultado da classificação ...</h5>
            </div>
            <br>

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
                Neste página poderá visualizar:
              </p>
                <ul>
                  <li>todas as denúncias de uma determinada actividade (dentro do intervalo temporal especificado)</li>
                  <li>o texto das denúncias (selecione uma da tabela)</li>
                  <li>o resultado da aplicação dos classificadores</li>
                  <li>estatísticas relacionadas com o tema</li>
                </ul>                
              <br>
              <p>
                Ao selecionar <i><mark style="background-color: #33CCFF;border-radius: 5px; padding: 2px;color:black;">Class. 1</mark></i>, irá apresentar apenas uma actividade. Ao selecionar <i><mark style="background-color: #CCFF66;border-radius: 5px; padding: 2px;color:black;">Class. 2</mark></i>, serão apresentadas até três actividades, em ordem decrescente de probabilidade.
              </p>
              <br>
             
              <p>
                Ao selecionar a checkbox <i><mark style="background-color: #99CCFF;border-radius: 5px; padding: 2px;color:black;">Exacto</mark></i>, o sistema apenas irá devolver as denúncias com a categoria selecionada exacta. Se por outro lado a checkbox não se encontrar selecionada, o sistema irá devolver todas os as denúncias dentro da categoria selecionada, isto é, as denúncias com a categoria selecionada ou com qualquer subcategoria desta. Note no entanto que isto ocorre apenas para os categorias que seguem o esquema: <b>X.Y.Z</b>. 
                Se por exemplo, se selecionar a folha <b>AA</b> da árvore, não serão apresentados, todas as denúncias abaixo desta. Mas se selecionar, a folha <b>I</b>, então, serão apresentados todas as denúncias classificadas como: <b>I, I.1, ...</b> e assim por adiante.
              </p>
              <br>
              <p>
                Por defeito, apenas são apresentadas as denúncias já analizadas dos últimos 3 meses.
                Introduza um novo intervalo temporal e clique em <i><mark style="border-radius: 5px; padding: 2px;color:white;" class="bg-danger">Actualizar</mark></i>.
              </p>
              <br>
              <p>
                Notas:
              </p>
              <ul>
                <li>
                  Existem actividades, tal como <i>Restaurantes</i>, que poderão possuir milhares de denúncias num espaço temporal relativamente curto, o que poderá implicar um tempo excessivo de busca.
                  Recomenda-se por isso, que selecione intervalos temporais não maiores de <i>3 meses</i>. 
                </li>   
                <li>
                  Outro ponto a considerar, é no facto de apenas serem apresentadas denúncias já analizadas pelos classificadores desenvolvidos.
                  Portanto, aconcelha-se visitar a página <a href="<?php echo DOMAIN_URL; ?>denuncias/denunciasanalisar.php">Denúncias Por Analisar</a> para proceder/continuar a classificação automática das denúncias.
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>



<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/colreorder/1.5.1/js/dataTables.colReorder.min.js"></script>


<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>



<script>

    var ano_start = null;
    var mes_start = null;
    var ano_end = null;
    var mes_end = null;

    // if false => display stats else display table
    var isStatsShowing = false;

    var datatable = null;

    // stats
    // dynamic stats are calculated here and not through queries
    var denuncias_total = 0;
    var competencia_ok = 0;
    var competencia_soso = 0;
    var infraccao_ok = 0;
    var infraccao_soso = 0;
    var actividade_ok = 0;
    var actividade_in_3 = 0;

    showTable();
    




    
//**************************************************
//          GENERAL OPS
//**************************************************




	$(document).ready(function() {
        
        $('[data-toggle="tooltip"]').tooltip({trigger : 'hover'});

        // ***** TREE *****
        var tree = <?php echo json_encode($json) ?>;

        $('#jstree').jstree({ 'core' : {
            'data' : tree
        } });

        $('#jstree').bind('ready.jstree', function(e, data) {
        // invoked after jstree has loaded
            $(this).jstree("open_node", $("#-1"));
            $(this).jstree("open_node", $("#1"));
            $(this).jstree("open_node", $("#2"));
            $(this).jstree("open_node", $("#154"));
            $(this).jstree("open_node", $("#155"));
            $(this).jstree("open_node", $("#265"));
        }).jstree();
          

          updateDate();

        // get all static stats
        getDenunciasTotal();
        getDenunciasTimeTotal()

        setGraphs(true);

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


    // reset dynamic stats
    function reset() {
        denuncias_total = 0;
        competencia_ok = 0;
        competencia_simples = 0;
        infraccao_ok = 0;
        actividade_ok = 0;
        actividade_in_3 = 0;
    }



    function updateDate() {
        var start = $('#datepicker1').val().split('-');
        ano_start = start[0];
        mes_start = start[1];
        var end = $('#datepicker2').val().split('-');
        ano_end = end[0];
        mes_end = end[1];        
    }

    function Actualizar() {
      reset();  // ?????
      getDenunciasTimeTotal();
        if ($("#jstree").jstree("get_selected")[0]) {
            getDenunciasOfCat($("#jstree").jstree("get_selected")[0]);
        }
    }

  $('#jstree').on("changed.jstree", function (e, data) {        
    getDenunciasOfCat(data.selected[0]);
  });

    $('#model').change(function() {
      Actualizar()
    });



//**************************************************
//          GENERAL TABLE
//**************************************************
// ***** TABLE *****
    function showTable(data) {
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
                "zeroRecords": "Nada encontrado",
                "info": "Mostrando _PAGE_ de _PAGES_",
                "infoEmpty": "Não existem denúncias classificadas para a actividade selecionada",
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
                        _: "Mostrar %d",
                        '-1': "Mostrar todas"
                    }
                }
            },
            lengthChange: false,
            buttons: ['pageLength', { extend: 'colvis', text: 'Colunas', columns: ':not(:first-child)'}, 'copy', 'excel', 'csv', 'pdf', 'print'],


            lengthMenu: [
                [ 5, 10, 25, 50, -1 ],
                [ '5 denúncias', '10 denúncias', '25 denúncias', '50 denúncias', 'Mostrar todas' ]
              ] 
        });

        datatable.buttons().container().appendTo( '#maintable_wrapper .col-md-6:eq(0)' ); 
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




  function getDenunciasOfCat(id_act) {
        $(".loading").show();
        updateDate();
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>denuncias/srv/denunciasbycategory.php",  
                    method:"POST",
                    data:{
                            id_act: id_act, 
                            exact: ($('#exacto').is(":checked"))?1:0, 
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
        CheckTableValues();
        setGraphs();
        $(".loading").hide();

        if (datatable.data().length > 0) {
          $(".hidden-message").show();
        }
    }


    function CheckTableValues() {
        reset();
        datatable.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            
            denuncias_total += 1;

            var cols = [];
            $(this.node()).find('td').each (function( column, td) {
                cols.push(td);
            });


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
            //actividades[col_real] = actividades[col_real] + 1;
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
            
        } );

    }

    // color must be css class colors
    function setColor(element, color ) {
        var value = element.textContent;
        //element.classList.remove("invalid");
        element.classList.add(color);
    }


    // text format: '+ word1 + word2 ...'
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

//**************************************************
//          STATS
//**************************************************


  function getDenunciasTotal() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>denuncias/srv/denunciastotal.php",  
                    method:"POST",
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        populateStaticStats(response);
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    },
                    timeout: 30000 //in milliseconds
        });
    };



  function getDenunciasTimeTotal() {
        updateDate();

        $("#den_time").html('<i class="fa fa-spinner fa-spin  fa-fw"></i>');
        $("#den_time_class").html('<i class="fa fa-spinner fa-spin  fa-fw"></i>');
        $("#den_time_class1").html('<i class="fa fa-spinner fa-spin  fa-fw"></i>');
        $("#den_time_class2").html('<i class="fa fa-spinner fa-spin  fa-fw"></i>');


        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>denuncias/srv/denunciastimetotal.php",  
                    method:"POST",
                    data:{
                            ano_start:ano_start, 
                            mes_start:mes_start, 
                            ano_end:ano_end, 
                            mes_end:mes_end
                    },
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        populateStaticTimeStats(response);
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    },
                    timeout: 30000 //in milliseconds
        });
    };

    function populateStaticStats(response) {
        var total = response['denuncias'];
        $("#den_total").html(setCommas(total));
        $("#den_total_with_message").html(setCommas(response['denuncias_with_message']));
        $("#den_total_class").html((response['den_total_class'] / response['denuncias_with_message'] * 100).toFixed(1));
        $("#den_total_class1").html((response['den_total_class1'] / response['denuncias_with_message'] * 100).toFixed(1));
        $("#den_total_class2").html((response['den_total_class2'] / response['denuncias_with_message'] * 100).toFixed(1));
    }


    function populateStaticTimeStats(response) {
      //console.log(response);
        $("#den_time").html(setCommas(response['den_time']));
        $("#den_time_class").html((response['den_time_class'] / response['den_time'] * 100).toFixed(1));
        $("#den_time_class1").html((response['den_time_class1'] / response['den_time'] * 100).toFixed(1));
        $("#den_time_class2").html((response['den_time_class2'] / response['den_time'] * 100).toFixed(1));
    }



    function setGraphs(initial = false) {
        var labels = ['OK','NOT OK'];
        var values = [competencia_ok,denuncias_total-competencia_ok];
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
          values = [competencia_simples,denuncias_total-competencia_simples];
        chart_competencia_simples.data.labels = labels;
        chart_competencia_simples.data.datasets[0].data = values;
        chart_competencia_simples.update();


        labels = ['OK','NOT OK'];
        if (initial)
            values = [1,0];
        else 
          values = [infraccao_ok,denuncias_total-infraccao_ok];
        chart_infraccao.data.labels = labels;
        chart_infraccao.data.datasets[0].data = values;
        chart_infraccao.update();



        labels = ['OK','NOT OK'];
        if (initial)
            values = [1,0];
        else 
          values = [actividade_ok,denuncias_total-actividade_ok];
        chart_actividade.data.labels = labels;
        chart_actividade.data.datasets[0].data = values;
        chart_actividade.update();

    }


//**************************************************
//          GRAPHICS
//**************************************************

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
        plugins: {
            labels: {
                render: 'value',
                fontSize: 14,
                fontColor: '#fff',
            }
        }       
    }
});




</script>




<?php include('../footer.php'); ?>