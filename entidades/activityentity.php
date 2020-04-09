<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>
<?php
  /*
      It will the databsase and create an array
      with the activity (code + designation), id and the parent's id.
      An array is all we need to build the tree using the selected 
      tree's jquery library - JsTree (https://www.jstree.com)
  */
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
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin="" />
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/infodisplay.css" />
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/datatables.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/colreorder/1.5.1/css/colReorder.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css" />

<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>

<div class="container-fluid">

    <!-- --------------------------------------------------- -->
    <!-- --------------------------------------------------- -->
    <!-- main page -->
    <!-- --------------------------------------------------- -->
    <!-- --------------------------------------------------- -->
    <div class="loading" style="display:none;">Loading&#8230;</div>

    <br>

    <div class="row">

        <div class="col-sm-4" style="height:90vh; width:100%; overflow-x:scroll;overflow-y:scroll;">
            <h5 class="display-9 text-center" style="background-color: #99CCFF;border-radius: 10px; padding: 5px;color:white;">Selecione uma Actividade</h5><br>
            <div id="jstree"></div>

            <br>
            <hr>
            <div class="checkbox">
                <a href="javascript:void(0)" data-toggle="tooltip" title="Check para selecionar apenas as entidades com a actividade exacta e não os filhos. Atenção: se unchecked o tempo de busca poderá ser excessivo!">
                    <label style="background-color: #99CCFF;border-radius: 10px; padding: 5px;color:black;"><input type="checkbox" id="exacto" value="exacto" checked> Exacto</label></a>

            </div>

        </div>

        <div class="col-sm-8">
            <h5 class="display-9 text-center" style="background-color: #99CCFF;border-radius: 10px; padding: 5px;color:white;">Selecione uma entidade</h5><br>
            <table id="table" class="table display" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">ID</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">NOME</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">MORADA</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">LOCALIDADE</th>
                        <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">NIF</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                </tfoot>
            </table>

        </div>
    </div>

    <!-- 
  ************************************************
  ************************************************
  MODALS
  ************************************************
  ************************************************
  -->
    <!-- ENTITY DATA MODAL -->
    <div class="modal" id="modal_entity_data">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="med_title">Entity name</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-content">
                    <div class="modal-body">

                        <?php require('_entity_data.php'); ?>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
                </div>

            </div>
        </div>
    </div>
    <!-- END OF ENTITY DATA MODAL -->

    <?php require('_modal_denuncia.php'); ?>
    <?php require('_modal_analysis.php'); ?>

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
                        Utilize a árvore à esquerda para selecionar a categoria/subcategoria dos agentes econômicos desejados.
                    </p>
                    <p>
                        Ao selecionar a categoria e caso existam agentes categorizadas com essa categoria, estes irão ser listados na tabela do lado direito. O utilizador pode ainda selecionar qualquer entidade para obter as informações disponíveis mais relevantes, desde <i>denuncias</i> e <i>reclamações</i> até à sua <i>localização geográfica</i>.
                    </p>
                    <p>
                        Ao selecionar a checkbox <i><mark style="background-color: #99CCFF;border-radius: 5px; padding: 2px;color:black;">Exacto</mark></i>, o sistema apenas irá devolver os agentes com a categoria selecionada exacta. Se por outro lado a checkbox não se encontrar selecionada, o sistema irá devolver todas os agentes econômicos dentro da categoria selecionada, isto é, os agentes com a categoria selecionada ou com qualquer subcategoria desta. Note no entanto que isto ocorre apenas para os categorias que seguem o esquema: <b>X.Y.Z</b>. Se por exemplo, se selecionar a folha <b>AA</b> da árvore, não serão apresentados, todas a entidades abaixo desta. Mas se selecionar, a folha <b>I</b>, então, serão apresentados todas as entidades classificadas como: <b>I, I.1, ...</b> e assim por adiante.
                    </p>
                    <p>
                        Note que de momento apenas cerca de 110k agentes econômicos se encontram devidamente classificados.
                    </p>

                    <p>
                        Em relação às <i>denuncias</i>, é possível não só visualizar o texto original dela, como também experimentar os <b>classificadores</b> desenvolvidos, que permitem, de momento, analisar as denúncias e automaticamente identificar as actividades mais prováveis da entidade como também a quem compete a investigação da denúncia.
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

<script>

    // global variable because it simplifies the code
    var g_id_denuncia = null; // message id to be analyzed (if required)

    // for the entity tabs
    var tabmap = null;
    var tabmarker = null;

    var datatable = null;



  // ******************************************
  // MAIN PAGE
  // ******************************************

  ShowTable();

  $(function () {

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
  
      $('[data-toggle="tooltip"]').tooltip(); 

       
      $('#table tbody').on('click', 'tr', function () {

          $("#table tbody tr").removeClass('row_selected');       
          $(this).addClass('row_selected');

          // this only works if columns' order is immutable
          var id = $(this).closest('tr').find('td').first().text();
          getData2(id);

      } );

    });


    // in clicking on a leaf, get all entities of the selected activity
    $('#jstree').on("changed.jstree", function (e, data) {
      $(".loading").show();
      getEntitiesOfCat(data.selected[0]);
    });



    function getData2(id_entidade) {
        $('#modal_entity_data').modal('show');
        getEntityGeral(id_entidade);
        getEntityGeo(id_entidade);
        getEntityDenuncias(id_entidade);
        getEntityReclamacoes(id_entidade);
        //getEntityFiscalizacoes(id_entidade);
        //getEntityProcessos(id_entidade);

    }

  // ******************************************
  // GET ENTITIE'S DATA
  // ******************************************



    // general data: name, address, activity, contacts, etc.
   function getEntityGeral(id_entidade) {

    $.ajax({  
                url:"<?php echo DOMAIN_URL; ?>entidades/srv/entitygeral.php",  
                    method:"POST",
                data:{id_entidade: id_entidade},
                cache: false,
                dataType:"json", 
                contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                success:function(response) {
                      populateGen(response); 
                },
                error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                }
    });
    };

    // get coordinates, address, city, country, ...
    function getEntityGeo(id_entidade) {
      $('#nav-denuncias').text('');
      $('#nav-denuncias').append('AGUARDE UM MOMENTO ...');
      $.ajax({  
                  url:"<?php echo DOMAIN_URL; ?>entidades/srv/getgeocod.php",  
                  method:"POST",
                  data:{id_entidade: id_entidade},//id_entidade},
                  cache: false,
                  dataType:"json", 
                  contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                  success:function(response) {
                        populateGeo(response); 
                  },
                  error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                  }
      });
    };


   function getEntityDenuncias(id_entidade) {
      $('#nav-denuncias').text('');
        $('#nav-denuncias').append('AGUARDE UM MOMENTO ...');
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/entitydenuncias.php",  
                    method:"POST",
                    data:{id_entidade: id_entidade},//id_entidade},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        populateDenuncias(response); 
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    }
        });
    };    


   function getEntityReclamacoes(id_entidade) {
      $('#nav-reclamacoes').text('');
        $('#nav-reclamacoes').append('AGUARDE UM MOMENTO ...');
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/entityreclamacoes.php",  
                    method:"POST",
                    data:{id_entidade: id_entidade},//id_entidade},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        populateReclamacoes(response); 
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    }
        });
    }; 


  function getEntityFiscalizacoes(id_entidade) {
       $('#nav-fiscalizacacoes').text('');
       $('#nav-fiscalizacacoes').append('AGUARDE UM MOMENTO ...');
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/entityfiscalizacoes.php",  
                    method:"POST",
                    data:{id_entidade: id_entidade},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        populateFiscalizacoes(response); 
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);                    
                    }
        });
    };


  function getEntityProcessos(id_entidade) {
       $('#nav-processos').text('');
       $('#nav-processos').append('AGUARDE UM MOMENTO ...');
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/entityprocessos.php",  
                    method:"POST",
                    data:{id_entidade: id_entidade},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        populateProcessos(response); 
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    }
        });
    };

    // GEOGRAPHIC TAB CONTENT
    function populateGeo(data) {
        if (tabmarker) {
            tabmarker.remove();
            tabmarker = null;
        }
        if (tabmap && tabmap.remove) {
            tabmap.off();
            tabmap.remove();
            tabmap = null;
        }

        $('#nav-geo').text('');




        // ******************************************
        // NAV-GEO
        // ******************************************
        str = "<div class='text-center'><b>LATITUDE: </b>" + data['latitude'];
        str += "<b style='padding-left: 50px;'>LONGITUDE: </b>" + data['longitude'] + '</div>';


         str += '<div id="tabmapid" style="width:100%; height:400px;"></div>';

        str += "<div class='text-center'>"
        str += _creator_1(data['is_in_distrito'],'Distrito');
        str += _creator_1(data['is_in_concelho'],'Concelho');
        str += _creator_1(data['is_in_freguesia'],'Freguesia');
        str += _creator_1(data['is_in_local'],'Localidade');
        str += _creator_1(data['is_in_cp'],'Cp');
        str += _creator_1(data['is_in_rua'],'Rua');
        str += _creator_1(data['is_manually_valid'],'Manual');
        str += "<br><b>" + _creator_1(data['is_valid'],'Valido') + "</b>";
        str += "</div>"

        $('#nav-geo').append(str);


        tabmap = L.map('tabmapid').setView([39.5, -8], 13);

            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                maxZoom: 21,
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                    '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                id: 'mapbox.streets'
            }).addTo(tabmap);  
          marker = L.marker([data['latitude'], data['longitude']]).addTo(tabmap);
          tabmap.setView(new L.LatLng(data['latitude'], data['longitude']), 19);

    }


    function _creator_1(value, title) {
        if ( value == 'T') {
            return title + ': <i class="far fa-thumbs-up" style="color: green;"></i> '
        } else if ( value == 'F') {
            return title + ': <i class="far fa-thumbs-down" style="color: red;"></i> '
        } else {
            return title + ': <i class="fa fa-question-circle" style="color: orange;"></i> '
        }
    }



  // ******************************************
  // TABLE RELATED
  // ******************************************


 function ShowTable() {
        datatable =  $('#table').DataTable( {
         //   "scrollY": 400,

         "columns": [
                { "data": "id" },
                { "data": "nome" },
                { "data": "morada" },
                { "data": "localidade" },
                { "data": "nif" }
            ],


            "scrollX": true,
            //"colReorder": true,
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
                        _: "Mostrar %d",
                        '-1': "Mostrar todos"
                    }
                }
            },

            lengthChange: false,
            buttons: ['pageLength', { extend: 'colvis', text: 'Colunas', columns: ':not(:first-child)'}, 'copy', 'excel', 'csv', 'pdf', 'print'],

            lengthMenu: [
                [ 5, 10, 25, 50, -1 ],
                [ '5 entidades', '10 entidades', '25 entidades', '50 entidades', 'Mostrar todas' ]
              ]
        } );


        datatable.buttons().container().appendTo( '#table_wrapper .col-md-6:eq(0)' ); 


      }



  function getEntitiesOfCat(id_act) {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/entitybycategory.php",  
                    method:"POST",
                    data:{id_act: id_act, exact: ($('#exacto').is(":checked"))?1:0},
                    cache: false,
                    dataType:"json", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                        populateTable(response);
                   },
                   error: function( jqXHR, status ) {
                        if (jqXHR.responseText['code'] = 1001) {
                          datatable.clear();
                          $(".loading").hide();
                          return;
                        }
                         errorsCommon(jqXHR, status);                    
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