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
            <div class='content is-showingSpinner' id="spinner_denuncias_total">
            <div class="card border-primary mx-sm-1 p-3 main-card">
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-bullhorn" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h5>Total Denúncias</h5></div>
                <div class="text-primary text-center mt-2"><h2 class="denuncias_total">-</h2></div>
            </div>
            </div>
        </div>

        <div class="col-lg-2">
            <div class='content is-showingSpinner' id="spinner_denuncias_entidades">
            <div class="card border-primary mx-sm-1 p-3 main-card">
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-bullhorn" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h5>Entidades com Den.</h5></div>
                <div class="text-primary text-center mt-2"><h2 class="denuncias_entidades">-</h2></div>
            </div>
            </div>
        </div>

        <div class="col-lg-2">
            <div class='content is-showingSpinner' id="spinner_denuncias_cumpridas">
            <div class="card border-info mx-sm-1 p-3 main-card">
                <div class="card border-info shadow text-info p-3 main-card-symbol" ><span class="fas fa-thumbs-up" aria-hidden="true"></span></div>
                <div class="text-info text-center mt-3"><h5>Cumpridas</h5></div>
                <div class="text-info text-center mt-2"><h2 class="denuncias_cumpridas">-</h2></div>
            </div>
            </div>
        </div>

        <div class="col-lg-2">
            <div class='content is-showingSpinner' id="spinner_denuncias_pendentes">
            <div class="card border-info mx-sm-1 p-3 main-card">
                <div class="card border-info shadow text-info p-3 main-card-symbol" ><span class="fas fa-thumbs-down" aria-hidden="true"></span></div>
                <div class="text-info text-center mt-3"><h5>Pendentes</h5></div>
                <div class="text-info text-center mt-2"><h2 class="denuncias_pendentes">-</h2></div>
            </div>
            </div>
        </div>

        <div class="col-lg-2">
            <div class='content is-showingSpinner' id="spinner_denuncias_infraccoes">
            <div class="card border-danger mx-sm-1 p-3 main-card">
                <div class="card border-danger shadow text-danger p-3 main-card-symbol" ><span class="fas fa-minus-circle" aria-hidden="true"></span></div>
                <div class="text-danger text-center mt-3"><h5>Infrações (D)</h5></div>
                <div class="text-danger text-center mt-2"><h2 class="denuncias_infraccoes">-</h2></div>
            </div>
            </div>
        </div>

    </div>

    <br>


    <div class="row">
        <div class="col-lg-12">
            <div class="card border-primary mx-sm-1 p-3 main-card">


            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#graph1">Estado de Averiguação</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#graph2">Competências</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#graph3">Por Actividade</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#graph4">Classificação das Infrações</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#graph5">Denúncias por Ano</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#graph6">Por Distrito</a>
              </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane container active" id="graph1">
                    <div class='content is-showingSpinner' id="spinner_graph_1">
                        <canvas id="graph_1" width="undefined" height="undefined"></canvas>
                    </div>
                </div>
                <div class="tab-pane container fade" id="graph2">
                    <div class='content is-showingSpinner' id="spinner_graph_2">
                        <canvas id="graph_2" width="undefined" height="undefined"></canvas>
                    </div>
                </div>
                <div class="tab-pane container fade" id="graph3">
                    <div class='content is-showingSpinner' id="spinner_graph_3">
                        <canvas id="graph_3" width="undefined" height="undefined"></canvas>
                    </div>
                </div>
                <div class="tab-pane container fade" id="graph4">
                     <div class='content is-showingSpinner' id="spinner_graph_4">
                        <canvas id="graph_4" width="undefined" height="undefined"></canvas>
                    </div>
                </div>
                <div class="tab-pane container fade" id="graph5">
                     <div class='content is-showingSpinner' id="spinner_graph_5">
                        <canvas id="graph_5" width="undefined" height="undefined"></canvas>
                    </div>
                </div>
                <div class="tab-pane container fade" id="graph6">
                     <div class='content is-showingSpinner' id="spinner_graph_6">
                        <canvas id="graph_6" width="undefined" height="undefined"></canvas>
                    </div>
                </div>
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
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">NUTS</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">DENUNCIAS</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">DEN./km&#xb2;</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">DEN./TOTAL HABITANTES</th>

              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">DEN./TOTAL ENTIDADES</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">RECLAMAÇÕES</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">REC./km&#xb2;</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">REC./TOTAL HABITANTES</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">REC./TOTAL ENTIDADES</th>             
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
                    Nesta página o utilizador pode consultar alguns dados gerais relacionado com as denúncias.
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
                <h1>Denúncias</h1>
                <br>
                <br>
                <span  style="font-size: 125%;">
                    <b>De</b>: <span class="data_init">-</span><br>
                    <b> Até</b>: <span class="data_end">-</span><br>
                </span>
                <br>
                <span  style="font-size: 100%;">
                    <b>Denuncias Total</b>: <span class="denuncias_total">-</span><br>
                    <b>Denuncias Entidades</b>: <span class="denuncias_entidades">-</span><br>
                    <b>Denuncias Cumpridas</b>: <span class="denuncias_cumpridas">-</span><br>
                    <b>Denuncias Pendentes</b>: <span class="denuncias_pendentes">-</span><br>
                    <b>Denuncias com Infraccoes</b>: <span class="denuncias_infraccoes">-</span><br>
                </span>

                <br>
                <hr>
                <br>

                <canvas id="graph_1_R" width="350" height="350"></canvas>
                <canvas id="graph_2_R" width="350" height="350"></canvas>
                <canvas id="graph_3_R" width="350" height="350"></canvas>
                <canvas id="graph_4_R" width="350" height="350"></canvas>
                <canvas id="graph_5_R" width="350" height="350"></canvas>
                <canvas id="graph_6_R" width="350" height="350"></canvas>
            </div>
            `
            );


       Calc();

       
    });




    // this prevent the #crap of being added to the url
    $('.nav-tabs').click(function(event){
        event.preventDefault();        
    });



     function Calc() {
        if (datatable) {
             datatable.clear();
        //    $("#spinner_table_1").addClass('is-showingSpinner');
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


        $("#spinner_table_1").addClass('is-showingSpinner');
        $("#spinner_denuncias_total").addClass('is-showingSpinner');
        $("#spinner_denuncias_entidades").addClass('is-showingSpinner');
        $("#spinner_denuncias_cumpridas").addClass('is-showingSpinner');
        $("#spinner_denuncias_pendentes").addClass('is-showingSpinner');
        $("#spinner_denuncias_infraccoes").addClass('is-showingSpinner');
        $("#spinner_graph_1").addClass('is-showingSpinner');
        $("#spinner_graph_2").addClass('is-showingSpinner');
        $("#spinner_graph_3").addClass('is-showingSpinner');
        $("#spinner_graph_4").addClass('is-showingSpinner');
        $("#spinner_graph_5").addClass('is-showingSpinner');
        $("#spinner_graph_6").addClass('is-showingSpinner');


        // why timeouts? for now leave it
        setTimeout(function(){ getDataPanels(); }, 10);
        setTimeout(function(){ getData_table(); }, 500);
        setTimeout(function(){ getDataGraph_1(); }, 1000);
        setTimeout(function(){ getDataGraph_2(); }, 1250);
        setTimeout(function(){ getDataGraph_3(); }, 1500);
        setTimeout(function(){ getDataGraph_4(); }, 1750);
        setTimeout(function(){ getDataGraph_5(); }, 250);
        setTimeout(function(){ getDataGraph_6(); }, 750);
    
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
        if (counter > 6) {
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
//var ctx = $(".graph_1")[0].getContext('2d');
var chart_estados_averiguacao = new Chart(ctx, {
    type: 'doughnut',
    responsive : true,
    data: {
        datasets: [{
            label: ' # denuncias',
            data: [],
            backgroundColor: ['#FF0000', '#FF9900', '#0066CC', '#9933CC'],
          //  borderColor:'rgba(255, 99, 132, 1)',
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
            text: 'Estado de Averiguação'
        },/*
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },*/
        showDatapoints: true,
        plugins: {
            labels: {
                render: 'value',
                fontSize: 16,
                fontColor: '#fff',
            }
        }  
    }
});

var ctx2 = document.getElementById('graph_2').getContext('2d');
var chart_denuncias_competencias = new Chart(ctx2, {
    type: 'bar',
    responsive : true,
    data: {
        datasets: [{
            label: ' # denuncias',
            data: [],
            backgroundColor: 'rgba(99, 255, 132, 0.2)',
            borderColor:'rgba(99, 255, 132, 1)',
            borderWidth: 1,
            datalabels: {
                align: 'top',
                color: '#000000',
                font: {
                    size: '24'
                }
            }
        }]
    },
    options: {
        title: {
            display: true,
            text: 'Competências'
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



var ctx3 = document.getElementById('graph_3').getContext('2d');
var chart_actividade = new Chart(ctx3, {
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
            display: true,
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


var ctx4 = document.getElementById('graph_4').getContext('2d');
var chart_classificacao = new Chart(ctx4, {
    type: 'bar',
    responsive : true,
    data: {
        datasets: [{
            label: ' # denuncias',
            data: [],
            backgroundColor: 'rgba(255, 128, 64, 0.2)',
            borderColor:'rgba(255, 128, 64, 1)',
            borderWidth: 1,
            datalabels: {
                align: 'top',
                color: '#000000',
                font: {
                    size: '24'
                }
            }
        }]
    },
    options: {
        title: {
            display: true,
            text: 'Classificação das Infrações'
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




var ctx5 = document.getElementById('graph_5').getContext('2d');
//var ctx5 = $(".graph_5")[0].getContext('2d');
var chart_denuncias_ano = new Chart(ctx5, {
    type: 'line',
    responsive : true,
    data: {
        datasets: [{
            label: ' # denuncias',
            data: [],
            backgroundColor: 'rgba(132, 99, 255, 0.2)',
            borderColor:'rgba(132, 99, 255, 1)',
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
            display: true,
            text: 'Denuncias por Ano'
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


var ctx6 = document.getElementById('graph_6').getContext('2d');
var chart_por_distrito = new Chart(ctx6, {
    type: 'bar',
    responsive : true,
    data: {
        datasets: [{
            label: ' # denuncias',
            data: [],
            backgroundColor: 'rgba(99, 99, 255, 0.2)',
            borderColor:'rgba(99, 99, 255, 1)',
            borderWidth: 1,
            datalabels: {
                align: 'top',
                color: '#000000',
                font: {
                    size: '12'
                }
            }
        }]
    },
    options: {
        title: {
            display: true,
            text: 'Denuncias por distrito'
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

// ******************************************
// TABLES
// ******************************************

function ShowTable() {
        datatable =  $('#table').DataTable( {
         //   "scrollY": 400,

         "columns": [
                { "data": "id" },
                { "data": "nome" },
                { "data": "denuncias" },
                { "data": "denuncias_area" },
                { "data": "denuncias_populacao" },
                { "data": "denuncias_entidades" },
                { "data": "reclamacoes" },
                { "data": "reclamacoes_area" },
                { "data": "reclamacoes_populacao" },
                { "data": "reclamacoes_entidades" }
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
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/gettabledenuncias.php",  
                    method:"POST",
                    data:{ano_start:ano_start, mes_start:mes_start, ano_end:ano_end, mes_end:mes_end},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                       // console.log(response);
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
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getdencount.php",  
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
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getdenestadoave.php",  
                    method:"POST",
                   data:{ano_start:ano_start, mes_start:mes_start, ano_end:ano_end, mes_end:mes_end},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
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
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getdencompetencias.php",  
                    method:"POST",
                   data:{ano_start:ano_start, mes_start:mes_start, ano_end:ano_end, mes_end:mes_end},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        setDataGraph_2(response);

                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    },
                    timeout: 60000
        });
    };




    function getDataGraph_3() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getdenactividades.php",  
                    method:"POST",
                   data:{ano_start:ano_start, mes_start:mes_start, ano_end:ano_end, mes_end:mes_end},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        setDataGraph_3(response);

                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    },
                    timeout: 60000
        });
    };

    function getDataGraph_4() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getdenclasseinfraccoes.php",  
                    method:"POST",
                   data:{ano_start:ano_start, mes_start:mes_start, ano_end:ano_end, mes_end:mes_end},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        setDataGraph_4(response);

                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    },
                    timeout: 60000
        });
    };


    function getDataGraph_5() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getdenano.php",  
                    method:"POST",
                   data:{ano_start:ano_start, mes_start:mes_start, ano_end:ano_end, mes_end:mes_end},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        setDataGraph_5(response);

                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    },
                    timeout: 60000
        });
    };

    function getDataGraph_6() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getdendistrito.php",  
                    method:"POST",
                   data:{ano_start:ano_start, mes_start:mes_start, ano_end:ano_end, mes_end:mes_end},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        setDataGraph_6(response);

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
        
        $(".denuncias_total").html(setCommas(data['denuncias_total']));
        $(".denuncias_entidades").html(setCommas(data['denuncias_entidades']));
        $(".denuncias_cumpridas").html(setCommas(data['denuncias_cumpridas']));
        $(".denuncias_pendentes").html(setCommas(data['denuncias_pendentes']));
        $(".denuncias_infraccoes").html(setCommas(data['denuncias_infraccoes']));
        

        $("#spinner_denuncias_total").removeClass('is-showingSpinner');
        $("#spinner_denuncias_entidades").removeClass('is-showingSpinner');
        $("#spinner_denuncias_cumpridas").removeClass('is-showingSpinner');
        $("#spinner_denuncias_pendentes").removeClass('is-showingSpinner');
        $("#spinner_denuncias_infraccoes").removeClass('is-showingSpinner');


        getReportReference(".denuncias_total").html(setCommas(data['denuncias_total']));
        getReportReference(".denuncias_entidades").html(setCommas(data['denuncias_entidades']));
        getReportReference(".denuncias_cumpridas").html(setCommas(data['denuncias_cumpridas']));
        getReportReference(".denuncias_pendentes").html(setCommas(data['denuncias_pendentes']));
        getReportReference(".denuncias_infraccoes").html(setCommas(data['denuncias_infraccoes']));
        
    }

    function setDataGraph_1(data) {

        $("#spinner_graph_1").removeClass('is-showingSpinner');
        if (data.length == 0) {
            return;
        }
       
       var labels = [];
       var values = [];

        for(var i=0;i<data.length;i++) {
            labels[i] = data[i]['DESIGNACAO'];
            values[i] = parseInt(data[i]['COUNT_ESTADOS']);
        } 

        chart_estados_averiguacao.data.labels = labels;
        chart_estados_averiguacao.data.datasets[0].data = values;
        chart_estados_averiguacao.update();
    }


    function setDataGraph_2(data) {

        $("#spinner_graph_2").removeClass('is-showingSpinner');
        if (data.length == 0) {
            return;
        }
       
       var labels = [];
       var values = [];

        for(var i=0;i<data.length;i++) {
            labels[i] = (data[i]['DESIGNACAO']?data[i]['DESIGNACAO']:'Sem Comp.');
            values[i] = parseInt(data[i]['COUNT_COMPETENCIA']);
        } 

        chart_denuncias_competencias.data.labels = labels;
        chart_denuncias_competencias.data.datasets[0].data = values;
        chart_denuncias_competencias.update();
    }



    function setDataGraph_3(data) {

        $("#spinner_graph_3").removeClass('is-showingSpinner');
        if (data.length == 0) {
            return;
        }
       
        var labels = [];
        var values = [];
        for(var i=0;i<data.length;i++) {
            labels[i] = (data[i]['CODIGO'] != ''?data[i]['CODIGO']:'Sem Código');
            if (data[i]['CODIGO'] == null) labels[i] = 'Nulo';
            values[i] = parseInt(data[i]['COUNT_ACTIVIDADES']);
        }        


        chart_actividade.data.labels = labels;
        chart_actividade.data.datasets[0].data = values;
        chart_actividade.update();
    }


    function setDataGraph_4(data) {

        $("#spinner_graph_4").removeClass('is-showingSpinner');
        if (data.length == 0) {
            return;
        }
       
        var labels = [];
        var values = [];
        for(var i=0;i<data.length;i++) {
            labels[i] = data[i]['TIPO'];
            values[i] = parseInt(data[i]['COUNT_TIPO']);
        }        


        chart_classificacao.data.labels = labels;
        chart_classificacao.data.datasets[0].data = values;
        chart_classificacao.update();
    }  



    function setDataGraph_5(data) {

        $("#spinner_graph_5").removeClass('is-showingSpinner');
        if (data.length == 0) {
            return;
        }
       
        var labels = [];
        var values = [];
        for(var i=0;i<data.length;i++) {
            labels[i] = data[i]['ANO'];
            values[i] = parseInt(data[i]['COUNT_ANO']);
        }        


        chart_denuncias_ano.data.labels = labels;
        chart_denuncias_ano.data.datasets[0].data = values;
        chart_denuncias_ano.update();
    }


    function setDataGraph_6(data) {

        $("#spinner_graph_6").removeClass('is-showingSpinner');
        if (data.length == 0) {
            return;
        }
       
        var labels = [];
        var values = [];
        for(var i=0;i<data.length;i++) {
            labels[i] = (data[i]['DISTRITO']?data[i]['DISTRITO']:'Sem Distrito');
            values[i] = parseInt(data[i]['COUNT_DISTRITO']);
        }        


        chart_por_distrito.data.labels = labels;
        chart_por_distrito.data.datasets[0].data = values;
        chart_por_distrito.update();
    } 

// ******************************************
//
// ******************************************

</script>


<?php include('../footer.php'); ?>
