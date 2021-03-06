<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>
<?php require('_modal_report.php'); ?>


<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/dashboards.css"/>
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

        <button type="button" class="btn btn-success" id="report" style="margin-left: 15px; border-radius: 10px; padding: 5px;color:white;"  data-target="#modal_report" data-toggle="modal"><i class='fas fa-file-alt' style='font-size:18px'></i> Gerar Relatório</button>

    </div>

	<br>
	<div class="row">
		<div class="col-sm-3">
            <div class='content is-showingSpinner' id="spinner_1">
            <div class="card border-primary mx-sm-1 p-3 main-card">
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-industry" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h4>Entidades</h4></div>
                <div class="text-primary text-center mt-2"><h1 class="entidades">-</h1></div>
            </div>
            </div>
        </div>
		<div class="col-sm-3">
            <div class='content is-showingSpinner' id="spinner_2">
            <div class="card border-primary mx-sm-1 p-3 main-card">
                
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-bullhorn" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h4>Denúncias</h4></div>
                <div class="text-primary text-center mt-2"><h1 class="denuncias">-</h1></div>
            </div>
            </div>
        </div>
		<div class="col-sm-3">
            <!--
            <div class='content is-showingSpinner' id="spinner_3">
            -->
            <div class="card border-primary mx-sm-1 p-3 main-card">
                
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-edit" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h4>Fiscalizações</h4></div>
                <div class="text-primary text-center mt-2"><h1 class="fiscalizacoes">
                    <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;"> 
                </h1></div>
            <!--
            </div>
            -->
            </div>
        </div>
		<div class="col-sm-3">
            <!--
            <div class='content is-showingSpinner' id="spinner_4">
            -->
            <div class="card border-primary mx-sm-1 p-3 main-card">
                
                <div class="card border-primary shadow text-primary p-3 main-card-symbol" ><span class="fas fa-file" aria-hidden="true"></span></div>
                <div class="text-primary text-center mt-3"><h4>Processos</h4></div>
                <div class="text-primary text-center mt-2">
                    <h1 class="processos">
                        <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;">
                    </h1>
                </div>
            </div>
            <!--
            </div>
            -->
        </div>
	</div>

    <br>

    <div class="row">
        <div class="col-sm-3">
            <div class='content is-showingSpinner' id="spinner_5">
            <div class="card border-info mx-sm-1 p-3 main-card">
                
                <div class="card border-info shadow text-info p-3 main-card-symbol" ><span class="fas fa-info" aria-hidden="true"></span></div>
                <div class="text-info text-center mt-3"><h5>Pedidos de Informação</h5></div>
                <div class="text-info text-center mt-2">
                    <h1 class='informacoes'>
                         -
                    </h1>
                </div>
            </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class='content is-showingSpinner' id="spinner_6">
            <div class="card border-info mx-sm-1 p-3 main-card">
                
                <div class="card border-info shadow text-info p-3 main-card-symbol" ><span class="fas fa-frown" aria-hidden="true"></span></div>
                <div class="text-info text-center mt-3"><h4>Reclamações</h4></div>
                <div class="text-info text-center mt-2"><h1 class="reclamacoes">-</h1></div>
            </div>
            </div>
        </div>
        <div class="col-sm-3">
            <!--
            <div class='content is-showingSpinner' id="spinner_7">
            -->
            <div class="card border-info mx-sm-1 p-3 main-card">
                
                <div class="card border-info shadow text-info p-3 main-card-symbol" ><span class="fas fa-copy" aria-hidden="true"></span></div>
                <div class="text-info text-center mt-3"><h4>Nº Coimas</h4></div>
                <div class="text-info text-center mt-2">
                    <h1 class="coimas_numero">
                        <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;">        
                    </h1>
                </div>
            </div>
            <!--
            </div>
            -->
        </div>
        <div class="col-sm-3">
            <!--
             <div class='content is-showingSpinner' id="spinner_8">
             -->
            <div class="card border-info mx-sm-1 p-3 main-card">
               
                <div class="card border-info shadow text-info p-3 main-card-symbol" ><span class="fas fa-dollar-sign" aria-hidden="true"></span></div>
                <div class="text-info text-center mt-3"><h4>Coimas Total</h4></div>
                <div class="text-info text-center mt-2">
                    <h1 class="coimas_valor">
                        <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;">        
                    </h1>
                </div>
            </div>
            <!--
            </div>
            -->
        </div>
    </div>
    
    <br>

    <div class="row">
        <div class="col-sm-3">
            <!--
            <div class='content is-showingSpinner' id="spinner_9">
            -->
            <div class="card border-dark mx-sm-1 p-3 main-card">
                
                <div class="card border-dark shadow text-dark p-3 main-card-symbol" ><span class="fas fa-user" aria-hidden="true"></span></div>
                <div class="text-dark text-center mt-3"><h4>Funcionários</h4></div>
                <div class="text-dark text-center mt-2">
                    <h1 class="funcionarios">
                        <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;">
                    </h1>
                </div>
            </div>
            <!--
            </div>
            -->
        </div>
        <div class="col-sm-3">
            <!--
            <div class='content is-showingSpinner' id="spinner_10">
            -->
            <div class="card border-dark mx-sm-1 p-3 main-card">
                
                <div class="card border-dark shadow text-dark p-3 main-card-symbol" ><span class="fas fa-user-friends" aria-hidden="true"></span></div>
                <div class="text-dark text-center mt-3"><h4>Brigadas</h4></div>
                <div class="text-dark text-center mt-2">
                    <h1 class="brigadas">
                        <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;">                      
                    </h1>
                </div>
            </div>
            <!--
            </div>
            -->
        </div>
        <div class="col-sm-3">
            <!--
            <div class='content is-showingSpinner' id="spinner_11">
            -->
            <div class="card border-dark mx-sm-1 p-3 main-card">
                
                <div class="card border-dark shadow text-dark p-3 main-card-symbol" ><span class="fas fa-car" aria-hidden="true"></span></div>
                <div class="text-dark text-center mt-3"><h4>Veiculos</h4></div>
                <div class="text-dark text-center mt-2">
                    <h1 class="viaturas">
                        <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;">
                    </h1>
                </div>
            </div>
            <!--
            </div>
            -->
        </div>
    </div>

    <br>

     <div class="row">
        <div class="col-sm-3">
            <div class='content is-showingSpinner' id="spinner_12">
            <div class="card border-success mx-sm-1 p-3 main-card">
                
                <div class="card border-success shadow text-success p-3 main-card-symbol" ><span class="fas fa-map" aria-hidden="true"></span></div>
                <div class="text-success text-center mt-3"><h4>Distritos/R.A.</h4></div>
                <div class="text-success text-center mt-2"><h1 class='distritos'>-</h1></div>
            </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class='content is-showingSpinner' id="spinner_13">
            <div class="card border-success mx-sm-1 p-3 main-card">
                
                <div class="card border-success shadow text-success p-3 main-card-symbol" ><span class="fas fa-map" aria-hidden="true"></span></div>
                <div class="text-success text-center mt-3"><h4>Concelhos</h4></div>
                <div class="text-success text-center mt-2"><h1 class='concelhos'>-</h1></div>
            </div>
            </div>
        </div>
        <div class="col-sm-3">
             <div class='content is-showingSpinner' id="spinner_14">
            <div class="card border-success mx-sm-1 p-3 main-card">
               
                <div class="card border-success shadow text-success p-3 main-card-symbol" ><span class="fas fa-map" aria-hidden="true"></span></div>
                <div class="text-success text-center mt-3"><h4>Freguesias</h4></div>
                <div class="text-success text-center mt-2"><h1 class='freguesias'>-</h1></div>
            </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class='content is-showingSpinner' id="spinner_15">
            <div class="card border-success mx-sm-1 p-3 main-card">
                
                <div class="card border-success shadow text-success p-3 main-card-symbol" ><span class="fas fa-map" aria-hidden="true"></span></div>
                <div class="text-success text-center mt-3"><h4>Unidades Reg./Op.</h4></div>
                <div class="text-success text-center mt-2"><h1 class='zonas'>-</h1></div>
            </div>
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-primary mx-sm-1 p-3 main-card">
                <div class='content is-showingSpinner' id="spinner_table">
                <table id="table" class="table display" style="width:100%">
          <thead>
            <tr>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">NUTS ID</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">PATH</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">POPULAÇÃO</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">ÁREA [km&#xb2;]</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">ENTIDADES</th>

              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">DENUNCIAS</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">RECLAMAÇÕES</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">FISCALIZAÇÕES</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">INFORMAÇÕES</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">PROCESSOS</th>             
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


    <!-- HELP MODAL -->
    <div class="modal" id="modal-help">
      <div class="modal-dialog">
        <div class="modal-content">
        
          <div class="modal-header">
            <h4 class="modal-title">Ajuda</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          
          <div class="modal-body modal-body-help text-justify">
                <p>
                    Nesta página o utilizador pode consultar alguns dados gerais.
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
    <!-- END OF HELP MODAL -->


</div>



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
            <h2>Informações Gerais</h2>
            <br>
            <span  style="font-size: 120%;">
                <b>De</b>: <span class="data_init">-</span><br>
                <b> Até</b>: <span class="data_end">-</span><br>
            </span>
            <br>
            <span  style="font-size: 100%;">
                <b>Entidades</b>: <span class="entidades">-</span><br>
                <b>Denuncias</b>: <span class="denuncias">-</span><br>
                <b>Reclamacoes</b>: <span class="reclamacoes">-</span><br>
                <b>Informacoes</b>: <span class="informacoes">-</span><br>
                <b>Distritos</b>: <span class="distritos">20</span><br>
                <b>Concelhos</b>: <span class="concelhos">308</span><br>
                <b>Freguesias</b>: <span class="freguesias">3,091</span><br>
                <b>Unidades Regionais</b>: 3<br>
                <b>Unidades Operacionais</b>: 13<br>
            </span>
        </div>
            `
            );

       Calc();

       
    });

    function Calc() {
        if (datatable) {
            datatable.clear();
            $("#spinner_table").addClass('is-showingSpinner');
        }
        $("#spinner_1").addClass('is-showingSpinner');
        $("#spinner_2").addClass('is-showingSpinner');
        $("#spinner_3").addClass('is-showingSpinner');
        $("#spinner_4").addClass('is-showingSpinner');
        $("#spinner_5").addClass('is-showingSpinner');
        $("#spinner_6").addClass('is-showingSpinner');
        $("#spinner_7").addClass('is-showingSpinner');
        $("#spinner_8").addClass('is-showingSpinner');
        $("#spinner_9").addClass('is-showingSpinner');
        $("#spinner_10").addClass('is-showingSpinner');
        $("#spinner_11").addClass('is-showingSpinner');
        $("#spinner_12").addClass('is-showingSpinner');
        $("#spinner_13").addClass('is-showingSpinner');
        $("#spinner_14").addClass('is-showingSpinner');
        $("#spinner_15").addClass('is-showingSpinner');
        

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

        // why timeouts? for now leave it
        setTimeout(function(){ getData_1_to_11(); }, 10);
        setTimeout(function(){ setData_12_13_14_15(); }, 250);
        setTimeout(function(){ getData_table(); }, 500);

    }



// ******************************************
// TABLES
// ******************************************
 function ShowTable() {
        datatable =  $('#table').DataTable( {
         //   "scrollY": 400,

         "columns": [
                { "data": "id" },
                { "data": "nome" },
                { "data": "populacao" },
                { "data": "area" },
                { "data": "entidades" },
                { "data": "denuncias" },
                { "data": "reclamacoes" },
                { "data": "fiscalizacoes" },
                { "data": "informacoes" },
                { "data": "processos" }
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
    function getData_1_to_11() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>dashboards/srv/getgeralcount.php",  
                    method:"POST",
                    data:{ano_start:ano_start, mes_start:mes_start, ano_end:ano_end, mes_end:mes_end},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        setData_1_to_11(response);
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);              
                    },
                    timeout: 60000
        });
    };




    // table densidade
    function getData_table() {
        $.ajax({  
                    url:"<?php echo DOMAIN_URL; ?>dashboards/srv/gettablegeral.php",  
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


// ******************************************
// POPULATE
// ******************************************

    function setData_1_to_11(data) { 
        $(".entidades").html(setCommas(data['entidades']));
        $(".denuncias").html(setCommas(data['denuncias']));
        //$("#fiscalizacoes").html(setCommas(data['fiscalizacoes']));
        //$("#processos").html(setCommas(data['processos']));
        $(".reclamacoes").html(setCommas(data['reclamacoes']));
        $(".informacoes").html(setCommas(data['informacoes']));

        //$("#funcionarios").html(setCommas(data['funcionarios']));
        //$("#brigadas").html(setCommas(data['brigadas']));
        //$("#viaturas").html(setCommas(data['viaturas']));

        $("#spinner_1").removeClass('is-showingSpinner');
        $("#spinner_2").removeClass('is-showingSpinner');
        $("#spinner_3").removeClass('is-showingSpinner');
        $("#spinner_4").removeClass('is-showingSpinner');
        $("#spinner_5").removeClass('is-showingSpinner');
        $("#spinner_6").removeClass('is-showingSpinner');

        $("#spinner_9").removeClass('is-showingSpinner');
        $("#spinner_10").removeClass('is-showingSpinner');
        $("#spinner_11").removeClass('is-showingSpinner');


        getReportReference(".entidades").html(setCommas(data['entidades']));
        getReportReference(".denuncias").html(setCommas(data['denuncias']));
        getReportReference(".reclamacoes").html(setCommas(data['reclamacoes']));
        getReportReference(".informacoes").html(setCommas(data['informacoes']));

    }


    function setData_12_13_14_15() {
        $(".distritos").html('20');
        $(".concelhos").html('308');
        $(".freguesias").html('3,091');
        $(".zonas").html('3/13');

        $("#spinner_12").removeClass('is-showingSpinner');
        $("#spinner_13").removeClass('is-showingSpinner');
        $("#spinner_14").removeClass('is-showingSpinner');
        $("#spinner_15").removeClass('is-showingSpinner');
    }

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

        $("#spinner_table").removeClass('is-showingSpinner');

    }



// ******************************************
// 
// ******************************************

</script>


<?php include('../footer.php'); ?>
