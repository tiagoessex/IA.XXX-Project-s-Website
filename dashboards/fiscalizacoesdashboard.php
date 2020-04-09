<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>



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

        <button type="button" class="btn btn-success" id="report" style="margin-left: 15px; border-radius: 10px; padding: 5px;color:white;" disabled><i class='fas fa-file-alt' style='font-size:18px'></i> Gerar Relatório</button>

    </div>

    <br>
    
    <div class="row">
        <div class="col-lg-2">
            <!--
            <div class='content is-showingSpinner' id="spinner_fiscalizacoes_total">
            -->
            <div class="card border-primary mx-sm-1 p-3 main-card">
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-bullhorn" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h5>Total  Fiscalizações</h5></div>
                <div class="text-primary text-center mt-2">
                    <h2 id="fiscalizacoes_total">
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
            <div class='content is-showingSpinner' id="spinner_fiscalizacoes_entidades">
            -->
            <div class="card border-primary mx-sm-1 p-3 main-card">
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-bullhorn" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h5>Entidades Fiscalizadas</h5></div>
                <div class="text-primary text-center mt-2">
                    <h2 id="fiscalizacoes_entidades">
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
            <div class='content is-showingSpinner' id="spinner_fiscalizacoes_num_infraccoes">
            -->
            <div class="card border-primary mx-sm-1 p-3 main-card">
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-bullhorn" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h5>Total de Infracções</h5></div>
                <div class="text-primary text-center mt-2">
                    <h2 id="fiscalizacoes_num_infraccoes">
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
        <div class="col-lg-12">
            <div class="card border-primary mx-sm-1 p-3 main-card">


            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#graph1">Fiscalizações</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#graph2">Alvos</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#graph3">Fiscalizações por Distrito</a>
              </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane container active" id="graph1">
                    <!--
                    <div class='content is-showingSpinner' id="spinner_graph_1">

                        <canvas id="graph_1" width="undefined" height="undefined">
                            
                        </canvas>

                    </div>
                    -->
                    <div class="text-center">
                    <img src="../images/forbidden.png" alt="forbidden" style="width:128px;height:128px;"> 
                    </div>
                </div>
                <div class="tab-pane container fade" id="graph2">
                    <!--
                    <div class='content is-showingSpinner' id="spinner_graph_2">

                        <canvas id="graph_2" width="undefined" height="undefined"></canvas>

                    </div>
                -->
                 <div class="text-center">
                    <img src="../images/forbidden.png" alt="forbidden" style="width:128px;height:128px;"> 
                    </div>
                </div>
                <div class="tab-pane container fade" id="graph3">
                    <!--
                    <div class='content is-showingSpinner' id="spinner_graph_3">

                        <canvas id="graph_3" width="undefined" height="undefined"></canvas>

                    </div>
                    -->
                    <div class="text-center">
                    <img src="../images/forbidden.png" alt="forbidden" style="width:128px;height:128px;"> 
                    </div>
                </div>
            </div>


            </div>
        </div>
    </div>
       

    <br>

    <div class="row">
        <div class="col-lg-12">
            <!--
            <div class='content is-showingSpinner' id="spinner_table_1">
            -->
                <div class="card border-primary mx-sm-1 p-3 main-card">
                    <!--
                    <div id="table1"></div>
                    -->

                    <table id="table" class="table display" style="width:100%">
          <thead>
            <tr>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">NUTS ID</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">PATH</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">FISCALIZAÇÕES</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">FISC./km&#xb2;</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">FISC./TOTAL HABITANTES</th>
               <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">FISC./TOTAL ENTIDADES</th>
     
            </tr>
          </thead>
          <tbody>            
          </tbody>
          <tfoot>            
          </tfoot>
        </table>
                <div class="text-center">
                    <img src="../images/forbidden.png" alt="forbidden" style="width:128px;height:128px;"> 
                    </div>
                </div>
            <!--
            </div>
            -->
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
                    Nesta página o utilizador pode consultar alguns dados gerais relacionado com as fiscalizações.
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

    //ShowTable();

    $( document ).ready(function() {
       Calc();

       
    });

     function Calc() {
        if (datatable) {
            datatable.clear();
            $("#spinner_table_1").addClass('is-showingSpinner');
        }


        var start = $('#datepicker1').val().split('-');
        ano_start = start[0];
        mes_start = start[1];
        var end = $('#datepicker2').val().split('-');
        ano_end = end[0];
        mes_end = end[1];


        $("#spinner_table_1").addClass('is-showingSpinner');
        $("#spinner_fiscalizacoes_total").addClass('is-showingSpinner');       
        $("#spinner_fiscalizacoes_entidades").addClass('is-showingSpinner');
        $("#spinner_fiscalizacoes_num_infraccoes").addClass('is-showingSpinner');
        $("#spinner_graph_1").addClass('is-showingSpinner');
        $("#spinner_graph_2").addClass('is-showingSpinner');
        $("#spinner_graph_3").addClass('is-showingSpinner');


        // why timeouts? for now leave it
        /*
        setTimeout(function(){ getData_table(); }, 10);
        setTimeout(function(){ getDataPanels(); }, 200);
        setTimeout(function(){ getDataGraph_1(); }, 300);
        setTimeout(function(){ getDataGraph_2(); }, 500);
        setTimeout(function(){ getDataGraph_3(); }, 700);
        */
    }



// ******************************************
// GRAPHS
// ******************************************

var ctx1 = document.getElementById('graph_1').getContext('2d');
var chart_fisc_ano = new Chart(ctx1, {
    type: 'line',
    responsive : true,
    data: {
        datasets: [{
            label: ' # fiscalizações',
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
            text: 'Entidades Fiscalizadas por Ano'
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
        showDatapoints: true       
    }
});



var ctx2 = document.getElementById('graph_2').getContext('2d');
var chart_fisc_alvos = new Chart(ctx2, {
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
            text: 'Tipo de Alvos'
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
        showDatapoints: true       
    }
});


var ctx3 = document.getElementById('graph_3').getContext('2d');
var chart_fisc_distrito = new Chart(ctx3, {
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
                    size: '18'
                }
            }
        }]
    },
    options: {
        title: {
            display: true,
            text: 'Fiscalizações por distrito'
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
        showDatapoints: true       
    }
});


// ******************************************
// TABLES
// ******************************************
/*
 var table = new Tabulator("#table1", {
   height:"400px",
  layout:"fitColumns",      //fit columns to width of table
  movableColumns:true,      //allow column order to be changed
  initialSort:[             //set the initial sort order of the data
    {column:"id", dir:"asc"},
  ],
      columns:[
        {title:"NUTS ID", field:"id", sorter:"string", align:"left"},
        {title:"NUTS", field:"nome", sorter:"string", align:"left"},
        {title:"FISCALIZAÇÕES", field:"fiscalizacoes", sorter:"number", align:"left"},
        {title:"FISC./km&#xb2;", field:"fiscalizacoes_area", sorter:"number", align:"left"},
        {title:"FISC./TOTAL HABITANTES", field:"fiscalizacoes_populacao", sorter:"number", align:"left"},
        {title:"FISC./TOTAL ENTIDADES", field:"fiscalizacoes_entidades", sorter:"number", align:"left"},
      ],
      rowClick:function(e, row){
           //getData2(row.getIndex());
      },
  });
  */


function ShowTable() {
        datatable =  $('#table').DataTable( {
         //   "scrollY": 400,

         "columns": [
                { "data": "id" },
                { "data": "nome" },
                { "data": "fiscalizacoes" },
                { "data": "fiscalizacoes_area" },
                { "data": "fiscalizacoes_populacao" },
                { "data": "fiscalizacoes_entidades" }
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
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/gettablefiscalizacoes.php",  
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
    };


    function getDataPanels() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getfisccount.php",  
                    method:"POST",
                   data:{ano_start:ano_start, mes_start:mes_start, ano_end:ano_end, mes_end:mes_end},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
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
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getfiscano.php",  
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
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getfisctiposalvo.php",  
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
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getfiscdistrito.php",  
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
        
        $("#fiscalizacoes_total").html(setCommas(data['fiscalizacoes_total']));
        $("#fiscalizacoes_entidades").html(setCommas(data['fiscalizacoes_entidades']));
        $("#fiscalizacoes_num_infraccoes").html(setCommas(data['fiscalizacoes_num_infraccoes']));


        $("#spinner_fiscalizacoes_total").removeClass('is-showingSpinner');       
        $("#spinner_fiscalizacoes_entidades").removeClass('is-showingSpinner');
        $("#spinner_fiscalizacoes_num_infraccoes").removeClass('is-showingSpinner');


    }


    function setDataGraph_1(data) {

        $("#spinner_graph_1").removeClass('is-showingSpinner');
        if (data.length == 0) {
            return;
        }
       
        var labels = [];
        var values = [];
        for(var i=0;i<data.length;i++) {
            labels[i] = data[i]['ANO'];
            values[i] = parseInt(data[i]['COUNT_ANO']);
        }        


        chart_fisc_ano.data.labels = labels;
        chart_fisc_ano.data.datasets[0].data = values;
        chart_fisc_ano.update();

    }


    function setDataGraph_2(data) {

        $("#spinner_graph_2").removeClass('is-showingSpinner');
        if (data.length == 0) {
            return;
        }
       
        var labels = [];
        var values = [];
        for(var i=0;i<data.length;i++) {
            labels[i] = data[i]['TIPO'];
            values[i] = parseInt(data[i]['COUNT_TIPO']);
        }        


        chart_fisc_alvos.data.labels = labels;
        chart_fisc_alvos.data.datasets[0].data = values;
        chart_fisc_alvos.update();

    }


    function setDataGraph_3(data) {

        $("#spinner_graph_3").removeClass('is-showingSpinner');
        if (data.length == 0) {
            return;
        }
       
        var labels = [];
        var values = [];
        for(var i=0;i<data.length;i++) {
            labels[i] = data[i]['DISTRITO'];
            values[i] = parseInt(data[i]['COUNT_DISTRITO']);
        }        


        chart_fisc_distrito.data.labels = labels;
        chart_fisc_distrito.data.datasets[0].data = values;
        chart_fisc_distrito.update();

    }

// ******************************************
//
// ******************************************

</script>


<?php include('../footer.php'); ?>
