<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>


<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/denuncias.css"/>
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
   </div>

   <br>

   <div class="row">
      <div class="col-sm-4"></div>
      <div class="col-sm-4">
         <button type="button" class="btn btn-success btn-block btn-lg" id="export" style="margin-left: 15px; border-radius: 10px; padding: 5px;color:white;" onclick="Export();"><i class='fas fa-sign-in-alt' style='font-size:18px'></i> <b>EXPORTAR & TREINAR (class. 2)</b></button>
      </div>
      <div class="col-sm-4">
      </div>
   </div>

   <br>

   <table id="maintable" class="table table-bordered" style="width:100%">
      <thead>
         <tr>
            <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">Denuncia</th>
            <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">Competencia</th>
            <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">Actividade</th>
            <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">Infraccao</th>
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

   <br>

    <div class="hidden-message" style="display: none;">
      <div class="row justify-content-center">
         <div class="message2">
            <div class="col-sm-12 text-center table-message">
               <h5>A criação, exportação e treino de um número elevado de denúncias pode lever muito tempo.</h5>
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

   <!-- PROCESS MODAL -->
   <div class="modal" id="modal_export">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <h4 class="modal-title modal-title-export">Exportar & Treinar</h4>
               <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-content-export">
               <div class="modal-body modal-body-export text-justify">
                  <h4>Operações:</h4>
                  <ul>
                     <li>
                        <h5>Criando ficheiro: <span id="create_file"><i class="fa fa-spinner fa-spin  fa-fw"></i></span></h5>
                     </li>
                     <li class="sending" style="display: none;">
                        <h5>Enviando [<b><span id="filename"></span></b>] para a máquina [<?php echo CLASSIFICADOR_LUIS_IP; ?>]: </b><span id="send_file"><i class="fa fa-spinner fa-spin  fa-fw"></i></span></h5>
                     </li>
                     <li class="training" style="display: none;">
                        <h5>Treinando o classificador 2: </b><span id="training_class"><i class="fa fa-spinner fa-spin  fa-fw"></i></span></h5>
                     </li>
                  </ul>
                  <br>
                  <h4 class="training" style="display: none;">Pode fechar a janela, mas espere pelo menos <b>24</b> horas antes de utilizar o classificador 2.</h4>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
            </div>
         </div>
      </div>
   </div>
   <!-- PROCESS MODAL -->

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




<script>

    var ano_start = null;
    var mes_start = null;
    var ano_end = null;
    var mes_end = null;

    var datatable = null;


    showTable();


   
//**************************************************
//          GENERAL OPS
//**************************************************


	$(document).ready(function() {
      
        $('[data-toggle="tooltip"]').tooltip({trigger : 'hover'});

        getDenuncias();

     //  $('#modal_export').modal('show');

        

    } );



    function updateDate() {
        var start = $('#datepicker1').val().split('-');
        ano_start = start[0];
        mes_start = start[1];
        var end = $('#datepicker2').val().split('-');
        ano_end = end[0];
        mes_end = end[1];        
    }


    function Actualizar() {
        reset();
        getDenuncias();
    }

        // reset dynamic stats
    function reset() {
        row = 0;
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
                { "data": "actividade" },
                { "data": "infraccao" },
                
            ],
            "columnDefs": [
                {
                    "targets": [1,2],
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
            buttons: [
              'pageLength', 
                { 
                    extend: 'colvis', 
                    text: 'Colunas', 
                    columns: ':not(:first-child)'
                }, 
                'copy', 
                {
                    extend: 'excel',
                    text: 'Excel',
                    title:'',                    
                    download: 'open',
                    orientation:'landscape',
                    first: false,
                    exportOptions: {
                      columns: ':visible'
                    }
                },
                'csv', 
                'pdf', 
                'print'
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


  function Export() {

      $('#create_file').html('<i class="fa fa-spinner fa-spin  fa-fw"></i>');
      $('#send_file').html('<i class="fa fa-spinner fa-spin  fa-fw"></i>');
      $('.training').hide();
      $('.sending').hide();

      $('#modal_export').modal('show');
      createExcel();
  }




  function getDenuncias() {
        $(".loading").show();
        updateDate();
        reset();
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>denuncias/srv/getdenuncias4.php",  
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
                        populateTable(response);
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    },
                    timeout: 240000 //in milliseconds
        });
    };


  function createExcel() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>denuncias/srv/createexcel.php",  
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
                      var filename = response['filename'].substring(2);
                      $('#filename').html(filename);
                      $('#create_file').html("<b><span class='text-success'>OK</span></b>");
                      $('.sending').show();
                      sendFile(filename);
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                        $('#create_file').html("<b><span class='text-danger'>ERROR</span></b>");
                    },
                    timeout: 240000 //in milliseconds
        });
    };


    function sendFile(filename) {
        console.log("send File > ", filename);

        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>denuncias/srv/sendfile.php",  
                    method:"POST",
                    data:{filename:filename},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                      if (response['status'] == 'OK') {
                          $('#send_file').html("<b><span class='text-success'>OK</span></b>");
                          $('.training').show();
                      } else {
                          $('#send_file').html("<b><span class='text-danger'>ERROR</span></b>");
                      }                      
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                        $('#send_file').html("<b><span class='text-danger'>ERROR</span></b>");
                    }
        });

    }


    function populateTable(data) {
        datatable.clear();
        datatable.rows.add(data);
        datatable.draw();


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



</script>




<?php include('../footer.php'); ?>