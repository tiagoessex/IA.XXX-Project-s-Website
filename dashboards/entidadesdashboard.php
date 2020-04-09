<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>
<?php require('_modal_report.php'); ?>


<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/dashboards.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/datatables.css"/>
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/colreorder/1.5.1/css/colReorder.dataTables.min.css" rel="stylesheet">


<div class="container-fluid">    
   
    <div class="row">
        <div class="col-sm-4" style="margin-left: 15px; background-color: rgb(106, 90, 205);border-radius: 10px; padding: 5px;color:white;"> 
            De: <input type="date" id="datepicker1" min="<?php echo MIN_DATE; ?>" max="<?php echo MAX_DATE; ?>" value="<?php echo MIN_DATE; ?>">
            Até: <input type="date" id="datepicker2" min="<?php echo MIN_DATE; ?>" max="<?php echo MAX_DATE; ?>" value="<?php echo MAX_DATE; ?>">    
        </div>
        <button type="button" class="btn btn-danger" id="recalc" style="margin-left: 15px; border-radius: 10px; padding: 5px;color:white;" onclick="Calc();"><i class='fas fa-redo-alt' style='font-size:18px'></i> Actualizar</button>

        <button type="button" class="btn btn-success" id="report" style="margin-left: 15px; border-radius: 10px; padding: 5px;color:white;"  data-target="#modal_report" data-toggle="modal" onclick="generateReport();"><i class='fas fa-file-alt' style='font-size:18px'></i> Gerar Relatório</button>

        
    </div>

    <br>

    <div class="row">
        <div class="col-lg-2">
            <div class='content is-showingSpinner' id="spinner_total_entidades">
            <div class="card border-primary mx-sm-1 p-3 main-card">
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-industry" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h5>Total Entidades</h5></div>
                <div class="text-primary text-center mt-2"><h2 class="entidades">-</h2></div>
            </div>
            </div>
        </div>
        
        <div class="col-lg-2">
            <div class='content is-showingSpinner' id="spinner_ent_cae">
            <div class="card border-primary mx-sm-1 p-3 main-card">
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-tag" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h5>Com CAE</h5></div>
                <div class="text-primary text-center mt-2"><h2 class="ent_cae">-</h2></div>
            </div>
            </div>
        </div>

        <div class="col-lg-2">
            <div class='content is-showingSpinner' id="spinner_actividade">
            <div class="card border-primary mx-sm-1 p-3 main-card">
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-barcode" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h5>Com Actividade</h5></div>
                <div class="text-primary text-center mt-2"><h2 class="actividade">-</h2></div>
            </div>
            </div>
        </div>        

        <div class="col-lg-2">
            <div class='content is-showingSpinner' id="spinner_denuncias">
            <div class="card border-primary mx-sm-1 p-3 main-card">
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-bullhorn" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h5>Com Denúncias</h5></div>
                <div class="text-primary text-center mt-2"><h2 class="denuncias">-</h2></div>
            </div>
            </div>
        </div>

        <div class="col-lg-2">
            <!--
            <div class='content is-showingSpinner' id="spinner_processos">
            -->
            <div class="card border-primary mx-sm-1 p-3 main-card">
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-file" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h5>Em Processos</h5></div>
                <div class="text-primary text-center mt-2">
                    <h2 class="processos">
                        <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;"> 
                    </h2>
                </div>
            </div>
            <!--
            </div>
            -->
        </div>

        <div class="col-lg-2">
            <!--
            <div class='content is-showingSpinner' id="spinner_fiscalizacoes">
            -->
            <div class="card border-primary mx-sm-1 p-3 main-card">
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-edit" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h5>Fiscalizadas</h5></div>
                <div class="text-primary text-center mt-2">
                    <h2 class="fiscalizacoes">
                        <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;"> 
                    </h2>
                </div>
            </div>
            <!--
            </div>
            -->
        </div>

    </div>
    
    <br>

    <div class="row">
        <div class="col-lg-6">
            <div class='content is-showingSpinner' id="spinner_graph_1">
                <div class="card border-primary mx-sm-1 p-3 main-card">
                    <canvas id="graph_1" width="undefined" height="undefined"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class='content is-showingSpinner' id="spinner_graph_2">
                <div class="card border-primary mx-sm-1 p-3 main-card">
                    <canvas id="graph_2" width="undefined" height="undefined"></canvas>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-lg-12">
            <div class='content is-showingSpinner' id="spinner_table_1">
                <div class="card border-primary mx-sm-1 p-3 main-card">                    
                    <table id="table" class="table display" style="width:100%">
          <thead>
            <tr>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">NUTS ID</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">PATH</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">ENTIDADES</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">ENTIDADES/km&#xb2;</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">ENTIDADES/TOTAL HABITANTES</th>
     
            </tr>
          </thead>
          <tbody>            
          </tbody>
          <tfoot>            
          </tfoot>
        </table>
                </div>
            </div>
        </div>        
    </div>




    <!-- DATABASE HELP MODAL -->
    <div class="modal" id="modal-help">
      <div class="modal-dialog">
        <div class="modal-content">
        
          <div class="modal-header">
            <h4 class="modal-title">Ajuda</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          
          <div class="modal-body modal-body-help text-justify">
                <p>
                    Nesta página o utilizador pode consultar alguns dados gerais relacionado com as entidades.
                </p>

                <hr>
                <p>
                    <b>
                        Notas:
                    </b>
                </p>
                <p>
                    Especifique o <i><mark style="background-color: rgb(106, 90, 205);border-radius: 5px; padding: 2px;color:white;">intervalo temporal</mark></i> e pressione <i><mark style="background-color: #FF0000;border-radius: 5px; padding: 2px;color:white;">Actualizar</mark></i> para actualizar os valores de acordo com esse intervalo.
                </p>
                <ul>
                    <li><b>Funcionários</b>: apenas os activos</li>
                    <li><b>Viaturas</b>: apenas as disponíveis</li>
                </ul>

                <p>
                    <mark>
                        A contabilização foi efectuada por NUTS.
                        Caso um registo não o possua, este não é contabilizado.
                    </mark>
                </p>
                <p>
                    Os dashboards encontram-se em fase de desenvolvimento - tanto em termos de design como em termos de acesso aos dados.
                    Devido a isto, é extremamente provável que não seja possível carregar certos gráficos e algumas métricas em tempo útil, resultando na apresentação de uma ou várias mensagens de erro.
                </p>
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
          </div>
          
        </div>
      </div>
    </div>
    <!-- END OF DATABASE HELP MODAL -->


</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>

<script src="<?php echo DOMAIN_URL; ?>js/utils.js"></script>

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


<script>

    var ano_start = null;
    var mes_start = null;
    var ano_end = null;
    var mes_end = null;

    var datatable = null;

    ShowTable();

    $( document ).ready(function() {


        createReport(`
            <div class="report">
                <h1>Entidades</h1>
                <br>
                <br>
                <span  style="font-size: 125%;">
                    <b>De</b>: <span class="data_init">-</span><br>
                    <b> Até</b>: <span class="data_end">-</span><br>
                </span>
                <br>
                <span  style="font-size: 100%;">
                    <b>Total Entidades</b>: <span class="entidades">-</span><br>
                    <b>Com CAE</b>: <span class="ent_cae">-</span><br>
                    <b>Com Actividade</b>: <span class="actividade">-</span><br>
                    <b>Com Denúncias</b>: <span class="denuncias">-</span><br>
                </span>

                <br>
                <hr>
                <br>

                <canvas id="graph_1_R" width="350" height="350"></canvas>
                <canvas id="graph_2_R" width="350" height="350"></canvas>
            </div>
            `
            );


       // $('#datepicker2').val(new Date(Date.now()).toISOString().split('T')[0]);
        Calc();

        
    });

    // only for date dependent data
    function Calc() {
        if (datatable) {
             datatable.clear();
        }

        var start = $('#datepicker1').val().split('-');
        ano_start = start[0];
        mes_start = start[1];
        var end = $('#datepicker2').val().split('-');
        ano_end = end[0];
        mes_end = end[1];


        // getReportReference() def in _modal_report.php
        // since the report is create in an entire new page (iframe)
        // this function returns the reference of a tag in that page
        getReportReference(".data_init").text($('#datepicker1').val());
        getReportReference(".data_end").text($('#datepicker2').val());

        $("#spinner_total_entidades").addClass('is-showingSpinner');
        $("#spinner_ent_cae").addClass('is-showingSpinner');
        $("#spinner_actividade").addClass('is-showingSpinner');
        $("#spinner_denuncias").addClass('is-showingSpinner');
        $("#spinner_processos").addClass('is-showingSpinner');
        $("#spinner_fiscalizacoes").addClass('is-showingSpinner');
        $("#spinner_graph_1").addClass('is-showingSpinner');
        $("#spinner_graph_2").addClass('is-showingSpinner');
        $("#spinner_table_1").addClass('is-showingSpinner');


        // why timeouts? for now leave it
        setTimeout(function(){ getDataPanels(); }, 10);
        setTimeout(function(){ getData_table(); }, 1000);
        setTimeout(function(){ getDataGraph_1(); }, 500);
        setTimeout(function(){ getDataGraph_2(); }, 1500);

    }


    // coping a graph from one place to another
    // from dashboard page to report page     
    // it's an unnecessary stupidly complex
    function copyGraph(counter) {
        var source = "graph_"+ counter;
        var destiny = "#graph_"+ counter + "_R";
        var destinationCtx = getReportCanvasContext(destiny);
        var canvas = document.getElementById(source);
        destinationCtx.canvas.width = canvas.width;
        destinationCtx.canvas.height = canvas.height;
        destinationCtx.drawImage(canvas, 0, 0);
        counter++;
        if (counter > 2) {
            return;
        } else {
            printGraph(counter);
        }
    }


    function printGraph(counter) {
        $('a[href="#' + "graph"+ counter + '"]').tab('show');
        setTimeout(copyGraph, 200, counter);
    }


    function generateReport() {
        printGraph(1);
    }


// ******************************************
// GRAPHS
// ******************************************

var ctx = document.getElementById('graph_1').getContext('2d');
var chart_tipos_ent = new Chart(ctx, {
    type: 'bar',
    responsive : true,
    data: {
        datasets: [{
            label: ' # numero de entidades',
            data: [],
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor:'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        title: {
            display: true,
            text: 'Tipos de Entidades'
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
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

var ctx2 = document.getElementById('graph_2').getContext('2d');
var chart_natureza = new Chart(ctx2, {
    type: 'bar',
    responsive : true,
    data: {
        datasets: [{
            label: ' # numero de entidades',
            data: [],
            backgroundColor: 'rgba(99, 255, 132, 0.2)',
            borderColor:'rgba(99, 255, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        title: {
            display: true,
            text: 'Natureza Juridica'
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
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






// ******************************************
// TABLES
// ******************************************


function ShowTable() {
        datatable =  $('#table').DataTable( {
         //   "scrollY": 400,

         "columns": [
                { "data": "id" },
                { "data": "nome" },
                { "data": "entidades" },
                { "data": "entidades_area" },
                { "data": "entidades_populacao" }
            ],


            "scrollX": true,
            "colReorder": true,
            "bDestroy": true,
            "pagingType": "full_numbers",

            "order": [[ 0, "asc" ]],


            "language": {
                "lengthMenu": "Mostrar _MENU_ entidades por página",
                "zeroRecords": "Nada encontrado",
                "info": "Mostrando _PAGE_ de _PAGES_",
                "infoEmpty": "Não existem entidades disponiveis",
                "infoFiltered": "(filtrado de um total de _MAX_ entidades)",
                 "paginate": {
                      "previous": "<i class='fas fa-angle-left'></i>",
                      "next": "<i class='fas fa-angle-right'></i>",
                      "first": "<i class='fas fa-angle-double-left'></i>",
                      "last": "<i class='fas fa-angle-double-right'></i>"
                  },
                "search" : "Procurar",
                buttons: {
                    pageLength: {
                        _: "Mostrar %d entidades",
                        '-1': "Mostrar todos"
                    }
                }
            },

            lengthChange: false,
           buttons: ['pageLength', { extend: 'colvis', text: 'Colunas'}, 'copy', 'excel', 'csv', 'pdf', 'print'],

            lengthMenu: [
                [ 5, 10, 25, 50, -1 ],
                [ '5 entidades', '10 entidades', '25 entidades', '50 entidades', 'Mostrar todas' ]
              ]
        } );


        datatable.buttons().container().appendTo( '#table_wrapper .col-md-6:eq(0)' ); 


      }





// ******************************************
// GET DATA
// ******************************************

    function getData_table() {
        $.ajax({
                url:"<?php echo DOMAIN_URL; ?>dashboards/srv/gettableentidades.php",  
                method:"POST",
                data:{ano_start:ano_start, mes_start:mes_start, ano_end:ano_end, mes_end:mes_end},
                cache: false,
                dataType:"json", 
                contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                success:function(response) {
                    setData_table(response);
                },
                error: function( jqXHR, status ) {
                    errorsCommon(jqXHR, status);
                },
                timeout: 60000
        });
    }



    function getDataPanels() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getentscount.php",  
                    method:"POST",
                   data:{ano_start:ano_start, mes_start:mes_start, ano_end:ano_end, mes_end:mes_end},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        //console.log(response);
                        setDataPanels(response);

                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    },
                    timeout: 60000
        });
    };

    function getDataGraph_1() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getentstipo.php",  
                    method:"POST",
                   data:{ano_start:ano_start, mes_start:mes_start, ano_end:ano_end, mes_end:mes_end},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                   //     console.log(response);
                        setDataGraph_1(response);

                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    },
                    timeout: 60000
        });
    };

    function getDataGraph_2() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getentsnatureza.php",  
                    method:"POST",
                   data:{ano_start:ano_start, mes_start:mes_start, ano_end:ano_end, mes_end:mes_end},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                  //      console.log(response);
                        setDataGraph_2(response);

                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    },
                    timeout: 60000
        });
    };



// ******************************************
// POPULATE
// ******************************************

    function setData_table(data) {
        if (data.length == 0) {
            datatable.clear();
            datatable.draw();
            $("#spinner_table_1").removeClass('is-showingSpinner');
            return;
        }
        datatable.clear();
        datatable.rows.add(data);
        datatable.draw();
        $("#spinner_table_1").removeClass('is-showingSpinner');
    }

    function setDataPanels(data) { 
        $(".entidades").html(setCommas(data['entidades']));
        $(".ent_cae").html(setCommas(data['ent_cae']));
        $(".actividade").html(setCommas(data['ent_act']));
        $(".denuncias").html(setCommas(data['ent_den']));
        //$(".processos").html(setCommas(data['ent_proc']));
        //$(".fiscalizacoes").html(setCommas(data['ent_fisc']));

        $("#spinner_total_entidades").removeClass('is-showingSpinner');
        $("#spinner_ent_cae").removeClass('is-showingSpinner');
        $("#spinner_actividade").removeClass('is-showingSpinner');
        $("#spinner_denuncias").removeClass('is-showingSpinner');
        $("#spinner_processos").removeClass('is-showingSpinner');
        $("#spinner_fiscalizacoes").removeClass('is-showingSpinner');


        getReportReference(".entidades").html(setCommas(data['entidades']));
        getReportReference(".ent_cae").html(setCommas(data['ent_cae']));
        getReportReference(".actividade").html(setCommas(data['ent_act']));
        getReportReference(".denuncias").html(setCommas(data['ent_den']));

    }

    function setDataGraph_1(data) {
        $("#spinner_graph_1").removeClass('is-showingSpinner');
        if (data.length == 0) {
            return;
        }
       
       var labels = [];
       var values = [];
        for(var i=0;i<data.length;i++) {
            labels[i] = (data[i]['TIPO_DESC'] != ''?data[i]['TIPO_DESC']:'Sem classe');
            values[i] = parseInt(data[i]['COUNT_TIPOS']);
        }

        chart_tipos_ent.data.labels = labels;
        chart_tipos_ent.data.datasets[0].data = values;
        chart_tipos_ent.update();

    }

    function setDataGraph_2(data) {
        $("#spinner_graph_2").removeClass('is-showingSpinner');
        if (data.length == 0) {
            return;
        }
       
       var labels = [];
       var values = [];
        for(var i=0;i<data.length;i++) {            
            labels[i] = (data[i]['TIPO_DESC'] != ''?data[i]['TIPO_DESC']:'Sem classe');
            values[i] = parseInt(data[i]['COUNT_NATUREZA']);
        }

        //console.log(data);

        chart_natureza.data.labels = labels;
        chart_natureza.data.datasets[0].data = values;
        chart_natureza.update();

    }

// ******************************************
//
// ******************************************

</script>


<?php include('../footer.php'); ?>
