<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>



<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/toggleswitch.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/datatables.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/denuncias.css"/>
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
      <div style="margin-top: -20px; position: absolute;  right: 45px;" >
         Class. 1
         <a href="javascript:void(0)" data-toggle="tooltip" title="Class. 1. Class. 2 => actividades em ordem decrescente de probabilidade.">
         <label class="switchA">
         <input type="checkbox" id="model">
         <span class="sliderA"></span>
         </label>
         </a>
         Class. 2
      </div>
   </div>

   <br>

   <hr>

   <div class="row">
      <div class="col-sm-12">
               <table id="maintable" class="table table-bordered" style="width:100%">
                  <thead>
                     <tr>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white; width:10%;">Denuncia</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">Competencia</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">Actividade</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">Infracção</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #003366;color:white; width:20%;">Operações</th>
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
                     <div class="message-important">
                        <div class="col-sm-12 text-center table-message">
                           <h5>Clique sobre as células para as gravar individualmente.</h5>
                        </div>
                     </div>
                  </div>
               </div>
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
                  Neste página poderá visualizar e alterar o resultado da classificação das denúncias (dentro uma janela temporal).
               </p>
               <p>
                  Ao selecionar <i><mark style="background-color: #33CCFF;border-radius: 5px; padding: 2px;color:black;">Class. 1</mark></i>, apenas serão apresentadas as denúncias classificadas com o classificador 1.
                  Ao selecionar <i><mark style="background-color: #CCFF66;border-radius: 5px; padding: 2px;color:black;">Class. 2</mark></i>, apenas serão apresentadas as denúncias classificadas com o classificador 2.
               </p>
               <p>
                  Para visualizar o conteúdo de cada denúncia, basta clicar sobre o botão  <button type="button" class="btn btn-warning btn-xs"><i class='far fa-file-alt'></i></button>
               </p>
               <p>
                  Para <i>editar / alterar</i> o resultado da classificação, basta clicar sobre o botão  <button type="button" class="btn btn-primary btn-xs"><i class='fas fa-pen'></i></button>
                  Ao seleccionar <button type="button" class="btn btn-primary btn-xs">Alterar</button>, todas as alterações serão feitas mas qualquer resultado da analise da linha que já se encontre na base de dados será removido.
                  Ao seleccionar <button type="button" class="btn btn-success btn-xs">Alterar & Gravar</button>, todas as alterações serão feitas e toda a linha será gravada na base de dados.
               </p>
               <p>
                  Para <i>apagar</i> o resultado da classificação que já se encontre na base de dados, basta clicar sobre o botão  <button type="button" class="btn btn-danger btn-xs"><i class='fa fa-times'></i></button>
               </p>
               <p>
                  Para <i>gravar</i> o resultado da classificação da linha inteira, basta clicar sobre o botão  <button type="button" class="btn btn-success btn-xs"><i class='fa fa-check'></i></button>
               </p>
               <br>            
               <p>
                  Células vazias significa que o resultado da classificação desse campo não foi validado.
                  Para introduzir um novo valor, basta clicar em <button type="button" class="btn btn-primary btn-xs"><i class='fas fa-pen'></i></button> e introduzir os novos valores.
               </p>
               <br>            
               <p>
                  Para apenas gravar o resultado presente numa célula, basta clicar sobre esta. Verde, significa que foi guardada.
                  Atenção: um segundo clique, não irá apagar a gravação. Se quiser apagar, utilize outros métodos descritos aqui.
               </p>

               <br>            
               <p>
                  Por defeito, apenas são apresentadas as denúncias do último mês.
                  Para outro periodo temporal, introduza o novo intervalo e clique em <i><mark style="border-radius: 5px; padding: 2px;color:white;" class="bg-danger">Actualizar</mark></i>.
               </p>
               <p>
                  Notas
               </p>
               <ul>
                  <li>Intervalos temporais largos, podem implicar um número excessivo de denúncias, que poderá levar à "lentidão" do sistema dependendo da plataforma do utilizador.
                     Recomenda-se por isso, que selecione intervalos temporais não superiores a <i>3 meses</i>.
                  </li>
                  <li>
                     Apenas denúncias já classificadas pelos classificadores são apresentadas.
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
               <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="applyChanges(false)">Alterar</button>
               <button type="button" class="btn btn-success" data-dismiss="modal" onclick="applyChanges(true)">Alterar & Gravar</button>
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




<script>

    var ano_start = null;
    var mes_start = null;
    var ano_end = null;
    var mes_end = null;

    var datatable = null;


    // which row was selected to change
    var row_2_change = -1;

    // if true then a complaint was selected to be changed
    var change_denuncia = false;


    showTable();


   
//**************************************************
//          GENERAL OPS
//**************************************************


  $(document).ready(function() {
      
        //$('[data-toggle="tooltip"]').tooltip({trigger : 'hover'});

        getDenuncias();
        
    } );

    // this prevent the #crap of being added to the url
    $('.nav-tabs').click(function(event){
        event.preventDefault();        
    });


    function updateDate() {
        var start = $('#datepicker1').val().split('-');
        ano_start = start[0];
        mes_start = start[1];
        var end = $('#datepicker2').val().split('-');
        ano_end = end[0];
        mes_end = end[1];        
    }


    // 'Actualizar' button was clicked ...
    function Actualizar() {
        reset();
        getDenuncias();
    }

    function reset() {
        row_2_change = -1;
    }


    // when classifier is changed, then clear everything
    // and fetch whethever complaints have yet to be analized
    // by the selected classifier
    $('#model').change(function() {
        getDenuncias();
        reset();
    });




//**************************************************
//          TABLE
//**************************************************
    function showTable() {
        datatable = $('#maintable').DataTable({ 
            
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
            buttons: ['pageLength', { extend: 'colvis', text: 'Colunas', columns: ':not(:first-child)'}, 'copy', 'excel', 'csv', 'pdf', 'print']

        });

        datatable.buttons().container().appendTo( '#maintable_wrapper .col-md-6:eq(0)' );


        // from https://github.com/DataTables/Plugins/blob/master/api/row().show().js
        // used to show the page of the current row in analisys
        
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

    
    // which line was clicked 
    $('#maintable tbody').on('click', 'tr', function () {
        var tr = $(this).closest("tr");
        row_2_change = tr.index();

        var competencia = $(this).closest('tr').find('td:eq(1)').text();
        if (!competencia || competencia == '') {
          row_2_change = -1;
          return;
        }
    } );  


   // a single cell was clicked => save cell 
   $('#maintable tbody').on('click', 'td', function () {
      var cell = $(this);
      var text = cell.text();
      var id_denuncia = $(this).closest('tr').find('td:eq(0)').text();
      var column = datatable.cell( this ).index().columnVisible;
      if (column > 0 && column < 4 && text != '') {
         saveSingleCell(this, id_denuncia, column, text);
      }
   } ); 
    
   // get complains that still hasn't being classified
   function getDenuncias() {
        $(".loading").show();
        updateDate();
        reset();
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>denuncias/srv/getdenuncias3.php",  
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
                        console.log(response);
                        populateTable(response);
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                        $(".loading").hide();
                    },
                    timeout: 240000 //in milliseconds
        });
    };

    // data from complaints directly into table 
    // and add the 4 buttons in each line
    function populateTable(data) {
      datatable.clear();
      datatable.rows.add(data);
      datatable.draw();

      denuncias_total = datatable.data().length;

      for (var i = 0; i < denuncias_total; i++) {
            var row_node = datatable.row(i).node();
            var row_data = datatable.row(i).data() 
            $(row_node.childNodes[4]).html(`<div class="d-inline-block">
                    <button type="button" class="btn btn-warning btn-xs btn_edit" onclick="showDenuncia(` + row_data['id_denuncia'] + `)"><i class='far fa-file-alt'></i></button> 
                    <button type="button" class="btn btn-primary btn-xs btn_edit" onclick="changeModal()"><i class='fas fa-pen'></i></button>
                    <button type="button" class="btn btn-danger btn-xs btn_delete" onclick="deleteThisRow()"><i class='fa fa-times'></i></button> 
                    <button type="button" class="btn btn-success btn-xs btn_save" onclick="saveThisRow()"><i class='fa fa-check'></i></button>                     
                </div>`);

            if (row_data['competencia_ai'] != '')
               setColor(datatable.row(i).node().childNodes[1],'saved');
            if (row_data['actividade_ai'] != '')
               setColor(datatable.row(i).node().childNodes[2],'saved');
            if (row_data['infraccao_ai'] != '')
               setColor(datatable.row(i).node().childNodes[3],'saved');

            
      }


      $(".loading").hide();

      if (datatable.data().length > 0) {
         $(".hidden-message").show();
      }
    }



//**************************************************
//          SAVE THE STUFF
//**************************************************

   // save the entire row ... the entire analisys of a complain
   // and set all as validated
   function saveRow(id_denuncia, competencia, competencia_simples, actividade, infraccao, row_node) {
         // only the top dog
         // creates an array, but it solves the case of non existing comma
         actividade = actividade.split(',')[0];
         $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>denuncias/srv/saverow.php", 
                    method:"POST",
                    data:{
                            model: $('#model').is(":checked")?2:1,
                            competencia:competencia,
                            actividade:actividade,
                            infraccao:infraccao,
                            id_denuncia:id_denuncia,
                            competencia_simples: competencia_simples
                    },
                   success:function(response) {
                        removeColor(row_node,'not-saved');
                        setColor(row_node.childNodes[1],'saved');
                        setColor(row_node.childNodes[2],'saved');
                        setColor(row_node.childNodes[3],'saved');
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    },
                    timeout: 30000 //in milliseconds
        });
   }


   // save only a single cell
   function saveSingleCell(cell_node, id_denuncia, column, text) {
      
      var competencia = '';
      var actividade = '';
      var infraccao = '';
      var competencia_simples = '';

      // competencia
      if (column == 1) {
         competencia_simples = text.substr(0, text.indexOf(' '));
         competencia_simples = (competencia_simples == 'XXX'?1:0)
         competencia = text.substring(text.lastIndexOf("(") + 1, text.lastIndexOf(")"));
      }
      // actvidade
      else if (column == 2) {
         actividade = text.split(',')[0];
      }
      // infraccao
      else {
         infraccao = text;
      }

      $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>denuncias/srv/savecell.php", 
                    method:"POST",
                    data:{
                            model: $('#model').is(":checked")?2:1,
                            competencia:competencia,
                            actividade:actividade,
                            infraccao:infraccao,
                            id_denuncia:id_denuncia,
                            competencia_simples: competencia_simples
                    },
                   success:function(response) {
                        setColor(cell_node,'saved');
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    },
                    timeout: 30000 //in milliseconds
      });

   }

   
//**************************************************
//          CHANGE/SAVE COMPLAIN'S RESULTS
//**************************************************

    // timeout to ensure $('#maintable tbody').on('click', 'tr', function () 
    //is called first, otherwise, it might be unable to get the right row number
    // basically give it a moment to make sure row_2_change is set
    function changeModal() {
        change_denuncia = true;
        setTimeout(function(){ changeModal2(); }, 200);
    }

    function changeModal2() {

        var row_node = datatable.row(row_2_change).node();

        var id_denuncia = $(row_node.childNodes[0]).text();
        var competencia = $(row_node.childNodes[1]).text();
        var actividade = $(row_node.childNodes[2]).text();
        var infraccao = $(row_node.childNodes[3]).text();



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
          else if (comp == "Tribunal")
            $('input:radio[name=comp]')[2].checked = true;
          else if (comp == "Outra Entidade")
            $('input:radio[name=comp]')[3].checked = true;
          else
             $('input:radio[name=comp]')[4].checked = true;


         var acts = actividade.split(", ");
         $("#act1").val(acts[0]);

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


   // if to_save = true => apply changes + save row
   // is to_save = false => apply changes 
   function applyChanges(to_save) {
         var row_node = datatable.row(row_2_change).node();
         var row_data = datatable.row(row_2_change).data()
            
         var comp = $('input[name=comp]:checked').val();
         var comp_simples = $('input:radio[name=comp_simples]')[0].checked?true:false;
         var act = $("#act1").val();

         var inf = $('#model').is(":checked")?$('input[name=infraccao]:checked').val():'';
         var comp_text = (comp_simples?'XXX':'OUTRO') + ' (' + ($('#model').is(":checked")?'':comp) + ')';

         //console.log(comp,comp_simples,act,inf,comp_text);

         $(row_node.childNodes[1]).text(comp_text);
         $(row_node.childNodes[2]).text(act);
         $(row_node.childNodes[3]).text(inf);
            

         // necessary for the export
         row_data['competencia_ai'] = comp_text;
         row_data['actividade_ai'] = act;
         row_data['infraccao_ai'] = inf;

         datatable.draw(false);

         if (to_save) {
            saveRow(row_data['id_denuncia'], comp, comp_simples?1:0, act, inf, row_node);            
         } else {
            deleteRow(row_data['id_denuncia'], row_node);
         }

    }


//**************************************************
//          SAVE COMPLAIN'S RESULTS -- check button
//**************************************************

   // give it a moment to make sure row_2_change is set before
   // calling the real function
   function saveThisRow() {
        setTimeout(function(){ saveThisRow2(); }, 200);
    }

    function saveThisRow2() {

      var row_node = datatable.row(row_2_change).node();

      var id_denuncia = $(row_node.childNodes[0]).text();
      var competencia = $(row_node.childNodes[1]).text();
      var actividade = $(row_node.childNodes[2]).text();
      var infraccao = $(row_node.childNodes[3]).text();

      var competencia_simples = competencia.substr(0, competencia.indexOf(' '));
      competencia_simples = (competencia_simples == 'XXX'?1:0)
      competencia = competencia.substring(competencia.lastIndexOf("(") + 1, competencia.lastIndexOf(")"));
      actividade = actividade.split(',')[0];

      saveRow(id_denuncia, competencia, competencia_simples, actividade, infraccao, row_node); 
    }

   

//**************************************************
//   DELETE COMPLAIN'S ANALYSIS RSULTS FROM DATABASE -- delete button
//**************************************************

   // give it a moment to make sure row_2_change is set before
   // calling the real function
   function deleteThisRow() {
        setTimeout(function(){ deleteThisRow2(); }, 200);
    }

    function deleteThisRow2() {

      var row_node = datatable.row(row_2_change).node();
      var id_denuncia = $(row_node.childNodes[0]).text();
      deleteRow(id_denuncia, row_node); 
    }



    // delete the row results from database
   function deleteRow(id_denuncia, row_node) {
         $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>denuncias/srv/deleterow.php", 
                    method:"POST",
                    data:{
                            model: $('#model').is(":checked")?2:1,
                            id_denuncia:id_denuncia,
                    },
                   success:function(response) {
                        removeColor(row_node.childNodes[1],'saved');
                        removeColor(row_node.childNodes[2],'saved');
                        removeColor(row_node.childNodes[3],'saved');
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    },
                    timeout: 30000 //in milliseconds
        });
   }


//**************************************************
//          SHOW COMPLAIN'S MESSAGE
//**************************************************

    function showDenuncia(id) {
        getDenuncia(id);
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
                       displayMessage(response, id_denuncia); 
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    }
        });
    };

    function displayMessage(msg, id_denuncia) {
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



   
//**************************************************
//          DIVERSOS
//**************************************************

   // color must be css class colors
   function setColor(element, color ) {
      element.classList.add(color);
   }

   function removeColor(element, color ) {
      element.classList.remove(color);
   }

</script>




<?php include('../footer.php'); ?>