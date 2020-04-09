<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/infodisplay.css"/>

<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>

<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/css/bootstrap4-toggle.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/map.css"/>



<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/datatables.css"/>
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"   rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/colreorder/1.5.1/css/colReorder.dataTables.min.css" rel="stylesheet">


	<div class="container-fluid">

    <div class="loading" style="display:none;">Loading&#8230;</div>


		<div class="row">

			<!-- 
			************************************************
			************************************************
			MAP
			************************************************
			************************************************
			-->
			<div class="col-sm-10">
				<div id="mapid" style="height: 100vh"></div>
			</div>


			<!-- 
			************************************************
			************************************************
			SIDEBAR
			************************************************
			************************************************
			-->
			<div class="col-sm-2">
				
				<br>
				<div class="card" style="background-color: #66FFFF;">
	        	<div class="card-body">
	        	<p><i class="fas fa-thumbtack"></i> Clique sobre o mapa ou insira as coordenadas:</p>
				<form id="input_form_1">
		        	<label>Latitude
						<input type="text" class="form-control" name="latitude" id="latitude" value=""/>
					</label>
					<br>				
					
					<label>Longitude
						<input type="text" class="form-control" name="longitude" id="longitude" value=""/>
					</label>
          <!--
          <label>Raio [m]
            <input type="text" class="form-control" name="radius" id="radius" value="5000"/>
          </label>
          -->
          <label>Raio [m]
            <input type="number" class="form-control"  name="radius" id="radius" min="100" max="100000" step="500" value="5000"/>
          </label>

	        	</form>
	        	</div>
	    		</div>

				<br>
				<div class="card" style="background-color: #FFFF66;">
	        	<div class="card-body">
				<form id="input_form_1">
		        	<label>Número Máximo de Entidades
						<input type="text" class="form-control" name="limite" id="limite" value="20"/>
					</label>
					<br>				
					
					
	        	</form>
	        	</div>
	    		</div>

	    		<br>

				<button type="button" class="btn btn-info btn-lg fas  btn-block" style = " margin-top: 5px;" id="btn_update" onclick="getEntities();return false;" disabled>
				<i class="fas fa-sync-alt spinner" id="spinner"></i> Procurar Entidades </button>	

        
        <button type="button" class="btn btn-success btn-lg fas  btn-block" style = " margin-top: 5px;" id="btn_tabelar" onclick="" disabled>
        <i class="fas fa-table"></i> Listar Entidades</button> 
        

        <button type="button" class="btn btn-danger btn-lg fas  btn-block" style = " margin-top: 5px;" id="btn_reset" onclick="reset();return false;" disabled>
        <i class="fas fa-power-off"></i> Reset</button> 
				

				<br>

			</div>

		</div>


	<!-- 
	************************************************
	************************************************
	MODALS
	************************************************
	************************************************
	-->



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
                  Nesta página é possível consultar parte ou todas as entidades que se encontram dentro de um limite.</p>
              <p>
                  Ao clicar sobre o mapa ou através da introdução direta das coordenadas, uma circunferência irá surgir cujo centro corresponde às coordenadas introduzidas/clicada.<br>
                  Esta acção irá habilitar o botão <i><mark style="background-color: #99CCFF;border-radius: 5px; padding: 2px;color:black;">Procurar Entidades</mark></i>, que ao ser selecionado, irá marcar no mapa, um certo número de entidades, onde todas elas se encontram dentro da circunferência definida.<br>
                  Tanto o raio da circunferência como as coordenadas do seu centro podem ser alterados a qualquer momento.<br>
                  Após o aparecimento das entidades, os botões <i><mark style="background-color: #99FF33;border-radius: 5px; padding: 2px;color:black;">Listar Entidades</mark></i> e <i><mark style="background-color: #FF0000;border-radius: 5px; padding: 2px;color:black;">Reset</mark></i> irão ser habilitadas.
              </p>

              <p>
                  Ao selecionar <i><mark style="background-color: #99FF33;border-radius: 5px; padding: 2px;color:black;">Mostrar Tabela</mark></i>, irá surgir uma janela contendo uma lista de todas as entidades exibidas no mapa, podendo o utilizador selecionar qualquer uma destas, apresentando assim todas as informações relevantes da entidade selecionada desde <i>denuncias</i>, <i>fiscalizações</i> e <i>processos</i> até à sua <i>localização geográfica</i>.
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


  <!-- ENT TAB MODAL -->
    <div class="modal" id="modal-ents">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
        
          <div class="modal-header">
            <h4 class="modal-title">Entidades dentro da área - Selecione uma</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          
          <div class="modal-body modal-body-ents">
             <table id="table" class="table display" style="width:100%">
          <thead>
            <tr>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">ID</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">NOME</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">NIF</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">LATITUDE</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">LONGITUDE</th>
            </tr>
          </thead>
          <tbody>            
          </tbody>
          <tfoot>            
          </tfoot>
        </table>

          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
          </div>
          
        </div>
      </div>
    </div>
    <!-- ENT TAB MODAL -->


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

  


</div>


<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>

<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/js/bootstrap4-toggle.min.js"></script>


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


	// ************************************************
	// VARS AND CONST
	// ************************************************


  //var popup = L.popup();
  var circle = null;
  var center = null;

  var entitiesArray = [];
  var entitiesLayer = null;
  var labelsLayer = null;
//  var layerControl = false;

  const LIMITE = 20;
  const RADIUS = 5000;

  var g_id_denuncia = null; // message id to be analyzed (if required)

  var datatable = null;

	// ************************************************
	// INIT MAP
	// ************************************************
	var mymap = new L.Map('mapid', {center: new L.LatLng(39.5, -8), zoom: 6});

	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
			'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		id: 'mapbox.streets'
	}).addTo(mymap);


	// ************************************************
	// DIVERSOS
	// ************************************************

	$( document ).ready(function() {
    	$('[data-toggle="tooltip"]').tooltip(); 
      $("#latitude").val("");
      $("#longitude").val("");
      $("#radius").val(RADIUS);
      $("#limite").val(LIMITE);

      ShowTable();

      $('#table tbody').on('click', 'tr', function () {
          $("#table tbody tr").removeClass('row_selected');
          $(this).addClass('row_selected');
          // this only works if columns' order is immutable
          var id = $(this).closest('tr').find('td').first().text();
          getData2(id); 
      } );

	});


  function reset() {
    $("#latitude").val("");
    $("#longitude").val("");
    $("#radius").val(RADIUS);
    $("#limite").val(LIMITE);


    $("#btn_tabelar").prop("disabled", true);
    $("#btn_reset").prop("disabled", true);
    $("#btn_update").prop("disabled", true);

    deleteEntities();

     if (circle) {
        circle.remove();
        circle = null;
    }    
    center = null;

    datatable.clear();
  }


	// ************************************************
	// MAP CONTROL
	// ************************************************

  $('#radius').on('keypress', function (e) {
         if(e.which === 13){
            onMapClick(null);
         }
   });

    $('#radius').on('change', function (e) {
        onMapClick(null);
   });

    $('#latitude, #longitude').on('keypress', function (e) {
         if(e.which === 13 &&
            $("#longitude").val() != '' && 
            $("#latitude").val() != '') {
            setCircle(
                Number($("#latitude").val()),
                Number($("#longitude").val()),
                Number($("#radius").val())
            );
         }
   });


  function onMapClick(e) {

    if (e) {
      center = e.latlng;
    }

    if (!center) return;

    $("#longitude").val(center.lng.toString());
    $("#latitude").val(center.lat.toString());

    setCircle(center.lat, center.lng, Number($("#radius").val()));

  }


  function setCircle(lat, long, radius) {
     deleteEntities();

    if (circle) {
        circle.remove();
        circle = null;
    }


    circle = L.circle([lat, long], radius, {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.2
      }).addTo(mymap);

    // fit to screen - very simple calculus => not very accurate
  	var dY = 360 * radius / 6371392.896;
  	var dX = dY * Math.cos (long * 180 / Math.PI);
  	var corner1 = L.latLng(lat - dX, long - dY);
  	var corner2 = L.latLng(lat + dX, long + dY);
  	var bounds = L.latLngBounds(corner1, corner2);
  	mymap.flyToBounds(bounds);
    //  mymap.flyTo( L.latLng(lat, long));

     $("#btn_update").prop("disabled", false);
  }



  mymap.on('click', onMapClick);

	// ************************************************
	// AJAX ROUTINES 
	// ************************************************



  function getEntities() {

    if ($("#longitude").val() == '') return;
    if ($("#latitude").val() == '') return;
    if ($("#radius").val() == '') return;
    //if ($("#longitude").val() != '') return;

      $(".search-spinner").addClass("fa-spin")
      $(".loading").show();

      $.ajax({  
            url:"<?php echo DOMAIN_URL; ?>entidades/srv/getentsinradius.php",  
            method:"POST",
            data:{
                'radius': $("#radius").val(),
                'lat': $("#latitude").val(),
                'lon': $("#longitude").val(),
                'limite': $("#limite").val()
            },
            cache: false,
            dataType:"json", 
            contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
            success:function(response) {
              //console.log(response);
                setMapEntities(response);                  
            },
            error: function( jqXHR, status ) {
                errorsCommon(jqXHR, status);
                $(".search-spinner").removeClass("fa-spin");  
                $(".loading").hide();                  
            }
      });
  };



	// ************************************************
	// AJAX RESPONSES
	// ************************************************

  function setMapEntities(coord) {
   
    for(var i=0;i<coord.length;i++) {     
      var ma = new L.marker([coord[i]['latitude'],coord[i]['longitude']]).on('click', onMarkerClick);
      ma.bindPopup(coord[i]['nome'] + " # " + coord[i]['id']);
      entitiesArray.push(ma);       
      } 

    entitiesLayer = L.layerGroup(entitiesArray);

    entitiesLayer.addTo(mymap);     


  // fit to screen
    var fg = new L.featureGroup(entitiesArray);
    //mymap.fitBounds(fg.getBounds());
    mymap.flyToBounds(fg.getBounds());

   //   layerControl.addOverlay(entitiesLayer, "Entidades");  


    populateTable(coord);

    $(".search-spinner").removeClass("fa-spin")
    $(".loading").hide();

    $("#btn_tabelar").prop("disabled", false);
    $("#btn_reset").prop("disabled", false);

  }


  function deleteEntities() {
    if (entitiesLayer) {
   //   layerControl.removeLayer(entitiesLayer);
    }


    if (entitiesLayer) {
      for(i=0;i<entitiesArray.length;i++) {       
          entitiesLayer.removeLayer(entitiesArray[i]);
      }
    }

    if (entitiesLayer) {
      entitiesLayer.remove();
    }

    entitiesArray = [];

  }
  

  function onMarkerClick(e) {
    
  }




  // ************************************************
  // TABLES
  // ************************************************


 function ShowTable() {
        datatable =  $('#table').DataTable( {
         //   "scrollY": 400,

         "columns": [
                { "data": "id" },
                { "data": "nome" },
                { "data": "nif" },
                { "data": "latitude" },
                { "data": "longitude" }
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
            buttons: ['pageLength', 'copy', 'excel', 'csv', 'pdf', 'print'],

            lengthMenu: [
                [ 5, 10, 25, 50, -1 ],
                [ '5 entidades', '10 entidades', '25 entidades', '50 entidades', 'Mostrar todas' ]
              ]
        } );


        datatable.buttons().container().appendTo( '#table_wrapper .col-md-6:eq(0)' ); 


      }



    function populateTable(data) {
        datatable.clear();
        datatable.rows.add(data);
        datatable.draw();
    }

   $("#btn_tabelar").click(function(){
        $("#modal-ents").modal('show');
        datatable.draw();
    })




// ************************************************
// ************************************************
// ************************************************
//    PART II - ENTITY SELECTION AND DATA DISPLAY
// ************************************************
// ************************************************
// ************************************************


  // ************************************************
  // VARS AND INTRO
  // ************************************************


    // for the entity tabs
    var tabmap = null;
    var tabmarker = null;

    // get the rest of the data - denuncias, ...
    function getData2(id_entidade) {
      $('#modal_entity_data').modal('show');
        getEntityGeral(id_entidade);
        getEntityGeo(id_entidade);
        getEntityDenuncias(id_entidade);// 2709137
        getEntityReclamacoes(id_entidade);
      //  getEntityFiscalizacoes(id_entidade);
      //  getEntityProcessos(id_entidade);
    }



  // ************************************************
  // ÃJAX CALLS
  // ************************************************



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
                        $(".search-spinner").removeClass("fa-spin");
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



  // ************************************************
  // AJAX RESPONSES
  // ************************************************

    
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




</script>


<?php include('../footer.php'); ?>