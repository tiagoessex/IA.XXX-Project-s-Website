<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>
<?php include_once('../settings/config.php'); ?>
<?php include_once('../settings/database.php'); ?>
<?php
  $database = new Database();
  $conn = $database->getConnection();
  if (!$conn) die("error");
  $query="select 
            count(*) as COUNTER
          from 
            entidade_nif_invalid";
  try {  
    $result=$conn->query($query);  
  } catch (PDOException $e) {
      header("HTTP/1.1 500 Internal Server Error");
      die($e->getMessage());
  }
  $row = $result->fetch(PDO::FETCH_ASSOC);
  $counter = number_format($row['COUNTER']);
?>


<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/datatables.css"/>
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"   rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/colreorder/1.5.1/css/colReorder.dataTables.min.css" rel="stylesheet">





<div class="container-fluid">

    <div class="loading">Loading&#8230;</div>

    <br>

    <div class="row">
        <div class="col-sm-12">
            <h1 class="text-center">
                Entidades com NIF Inválido<a href="javascript:void(0)" data-toggle="tooltip" title="NIFs não portugueses, espanhois ou inválidos."><sup><i class='fas fa-exclamation-circle'></i></sup></a>
            </h1>
        </div>
    </div>

    <br><br>

    <div class="row">
        <div class="col-sm-12">
            <h3 style="background-color: #99CCFF;border-radius: 10px; padding: 5px;color:black;">
                <b>Número de entidades com nif inválido: </b>
                <?php echo $counter; ?>
            </h3>
        </div>
    </div>

    <br>
    <div class="row">
        <div class="col-sm-12">
            <table id="table" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">ID</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">NOME</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">NIF</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">PAÍS</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
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
                        Nesta página poderá consultar todas as entidades com NIFs inválidos
                    </p>
                    <p>
                        De momento apenas se consideram válidos os NIFs:
                    </p>
                    <ul>
                        <li>portugueses</li>
                        <li>espanhois</li>
                    </ul>
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
   var datatable = null;

    $( document ).ready(function() {
      $('[data-toggle="tooltip"]').tooltip({trigger : 'hover'});
    });

    ShowTable();

    function ShowTable() {
        datatable =  $('#table').DataTable( {
         //   "scrollY": 400,

         "columns": [
                { "data": "id" },
                { "data": "nome" },
                { "data": "nif" },
                { "data": "pais" }
            ],


            "scrollX": true,
            "colReorder": true,
            "bDestroy": true,
            "pagingType": "full_numbers",

            "order": [[ 2, "desc" ]],


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
                        _: "Mostrar %d",
                        '-1': "Mostrar todas"
                    }
                }
            },

            lengthChange: false,
            buttons: ['pageLength', { extend: 'colvis', text: 'Colunas'}, 'copy', 'excel', 'csv', 'pdf', 'print'],

            lengthMenu: [
                [ 6, 10, 26, 50, -1 ],
                [ '6 entidades', '10 entidades', '26 entidades', '50 entidades', 'Mostrar todas' ]
              ]


        } );


        datatable.buttons().container().appendTo( '#table_wrapper .col-md-6:eq(0)' ); 

        getAllNifInvalid();
      }


  function getAllNifInvalid() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/getallnifinvalid.php",  
                    method:"POST",
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                      populateTable(response); 
                   },
                   error: function( jqXHR, status ) {
                      errorsCommon(jqXHR, status);
                      $(".loading").hide();
                    }
        });
    };



    function populateTable(data) {
        datatable.clear();
        datatable.rows.add(data);
        datatable.draw();
        $(".loading").hide();
    }

</script>

<?php include('../footer.php'); ?>
