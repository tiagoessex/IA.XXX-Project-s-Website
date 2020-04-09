<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>


<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/infodisplay.css"/>

<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>

<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/css/bootstrap4-toggle.min.css" rel="stylesheet">');
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

          <label>Raio [m]
            <input type="number" class="form-control"  name="radius" id="radius" min="100" max="100000" step="500" value="5000"/>
          </label>

	        	</form>
	        	</div>
	    		</div>

        <br>
        <div class="card" style="background-color: #CCFFCC;">
          <div class="card-body">
              <button type="button" class="btn btn-success btn-lg fas btn-block" style = " margin-top: 5px;" id="btn_tipos" data-target="#modal-tipos" data-toggle="modal">
              Estabelecimentos</button> 
              <br><br>             
              <div class="tipos_selected">
              </div>
              <div class="keywords_selected">
                <b>Keywords:</b>
              </div>

          </div>
        </div>

				<br>
				<div class="card" style="background-color: #FFFF66;">
	        <div class="card-body">
				    <form id="input_form_1">
		        	<label>Número Máximo de Entidades
						    <input type="text" class="form-control" name="limite" id="limite" value="20"/>
					    </label>					   	
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
                  Nesta página é possível procurar entidades de um ou mais tipos, que se encontram registadas no <b>Google</b>, dentro de uma área definida por uma circunferência.
              </p>
              <p>
                  A selecção do tipo de entidades, por exemplo: restaurante ou bar, é efecutada na lista <i><mark style="background-color: #CCFFCC;border-radius: 5px; padding: 2px;color:black;">Tipos de Estabelecimento</mark></i>, onde pode selecionar o tipo de estabelecimento.
                  <br>
                  Seleccione <b>Todas</b>, para selecionar qualquer tipo de entidade/lugar.
                  <br>
                  Para maior especificação, usar o campo <b>keywords</b>, onde o utilizador pode introduzir palavras-chave que irão actuar como filtros.
                  Por exemplo, para obter <i>n</i> restaurantes sushi, deverá selecionar <i>restaurante</i> e inserir a palavra-chave <i>sushi</i>.
              </p>
              <p>
                  Ao clicar sobre o mapa ou através da introdução direta das coordenadas, uma circunferência irá surgir cujo centro corresponde às coordenadas introduzidas/clicada.<br>                  
                  Esta acção irá habilitar o botão <i><mark style="background-color: #99CCFF;border-radius: 5px; padding: 2px;color:black;">Procurar Entidades</mark></i>, que ao ser selecionado, irá marcar no mapa, um certo número de entidades, onde todas elas se encontram dentro da circunferência definida.<br>
                  Tanto o raio da circunferência como as coordenadas do seu centro podem ser alterados a qualquer momento.<br>
                  Após o aparecimento das entidades, os botões <i><mark style="background-color: #99FF33;border-radius: 5px; padding: 2px;color:black;">Listar Entidades</mark></i> e <i><mark style="background-color: #FF0000;border-radius: 5px; padding: 2px;color:black;">Reset</mark></i> irão ser habilitadas.
              </p>
              <br>
              <hr>
              <p>
                <h4 class="bg-warning">ATENÇÃO: USAR ESTE SERVIÇO COM CUIDADO DEVIDO AOS SEUS CUSTOS.</h4>
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
            <h4 class="modal-title">Entidades dentro da área</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          
          <div class="modal-body modal-body-ents">
            <table id="table" class="table display" style="width:100%">
          <thead>
            <tr>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">NOME</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">MORADA</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">LATITUDE</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">LONGITUDE</th>
              <th class="text-center" style="vertical-align: middle;background-color: #0000FF;color:white;">ABERTO?</th>
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


  <!-- ENT TIPOS MODAL -->
    <div class="modal" id="modal-tipos">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
        
          <div class="modal-header">
            <h4 class="modal-title">Tipos de Estalecimentos - Selecione um</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          
          <div class="modal-body modal-body-tipos" id="tipos">
            <form id="tiposForm">
            <div class="row">
            <div class="col-sm-4">
                  <div class="radio"><input type="radio" value="bakery" name="tipo"><label> Pastelaria</label></div>
                  <div class="radio"><input type="radio" value="bank" name="tipo"><label> Banco</label></div>
                  <div class="radio"><input type="radio" value="bar" name="tipo"><label> Bar</label></div>
                  <div class="radio"><input type="radio" value="beauty_salon" name="tipo"><label> Salão de Beleza</label></div>
                  <div class="radio"><input type="radio" value="cafe" name="tipo"><label> Café</label></div>
                  <div class="radio"><input type="radio" value="car_dealer" name="tipo"><label> Revendedor Auto</label></div>
                  <div class="radio"><input type="radio" value="car_rental" name="tipo"><label> Aluguer de Auto</label></div>
                  <div class="radio"><input type="radio" value="car_repair" name="tipo"><label> Oficina Auto</label></div>
                  <div class="radio"><input type="radio" value="car_wash" name="tipo"><label> Lavagem Auto</label></div>
                  <div class="radio"><input type="radio" value="clothing_store" name="tipo"><label> Loja Roupas</label></div>
                  <div class="radio"><input type="radio" value="dentist" name="tipo"><label> Dentista</label></div>
                  <div class="radio"><input type="radio" value="doctor" name="tipo"><label> Médico</label></div>
                  <div class="radio"><input type="radio" value="electrician" name="tipo"><label> Electricista</label></div>              
                  <div class="radio"><input type="radio" value="electronics_store" name="tipo"><label> Electrodomésticos</label></div>
                  <div class="radio"><input type="radio" value="florist" name="tipo"><label> Florista</label></div>
            </div>
            <div class="col-sm-4">                  
                  <div class="radio"><input type="radio" value="furniture_store" name="tipo"><label> Mobiliário</label></div>
                  <div class="radio"><input type="radio" value="gas_station" name="tipo"><label> Estação Serviço</label></div>
                  <div class="radio"><input type="radio" value="gym" name="tipo"><label> Ginásio</label></div>
                  <div class="radio"><input type="radio" value="hair_care" name="tipo"><label> Salão</label></div>
                  <div class="radio"><input type="radio" value="laundry" name="tipo"><label> Lavandaria</label></div>
                  <div class="radio"><input type="radio" value="library" name="tipo"><label> Livraria</label></div>
                  <div class="radio"><input type="radio" value="lodging" name="tipo"><label> Hospedagem</label></div>
                  <div class="radio"><input type="radio" value="meal_delivery" name="tipo"><label> Entregas Comida</label></div>
                  <div class="radio"><input type="radio" value="meal_takeaway" name="tipo"><label> Takeaway</label></div>
                  <div class="radio"><input type="radio" value="night_club" name="tipo"><label> Night Club</label></div>
                  <div class="radio"><input type="radio" value="park" name="tipo"><label> Parque</label></div>
                  <div class="radio"><input type="radio" value="parking" name="tipo"><label> Parque de Estacionamento</label></div>              
                  <div class="radio"><input type="radio" value="pet_store" name="tipo"><label> Loja Animais</label></div>             
                  <div class="radio"><input type="radio" value="pharmacy" name="tipo"><label> Farmácia</label></div>              
                  <div class="radio"><input type="radio" value="restaurant" name="tipo" checked><label> Restaurante</label></div>
            </div>
             <div class="col-sm-4">
                  <div class="radio"><input type="radio" value="school" name="tipo"><label> Escola</label></div>
                  <div class="radio"><input type="radio" value="shoe_store" name="tipo"><label> Sapataria</label></div>
                  <div class="radio"><input type="radio" value="shopping_mall" name="tipo"><label> Shopping Center</label></div>
                  <div class="radio"><input type="radio" value="spa" name="tipo"><label> Spa</label></div>
                  <div class="radio"><input type="radio" value="stadium" name="tipo"><label> Estádio</label></div>
                  <div class="radio"><input type="radio" value="store" name="tipo"><label> Loja</label></div>
                  <div class="radio"><input type="radio" value="supermarket" name="tipo"><label> Supermercado</label></div>
                  <br><br>
                  <div class="radio"><input type="radio" value="" name="tipo"><label><b> Todas</b></label></div>
                  <br><br>
              </div>
              </div>
              <hr>
              <div class="row">
              <div class="col-sm-12">
                <label>Keywords (ex: sushi, wok):</label> 
                <input type="text" class="form-control" name="keywords" id="keywords" value="" placeholder="Separar com virgulas" />                
              </div>
              </div>
            </form>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
          </div>

        </div>
      </div>
    </div>
    <!-- ENT TIPOS MODAL -->


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


    $('input[type=radio][name=tipo]').change(function() {
      $(".tipos_selected").html("<b>Tipo:</b> " + $('input[name=tipo]:checked', '#tiposForm').next('label').text());
    });

    $(".tipos_selected").html("<b>Tipo:</b> " + $('input[name=tipo]:checked', '#tiposForm').next('label').text());
 

    $('#keywords').on("keydown input", function(){
      $(".keywords_selected").html("<b>Keywords:</b> " + $('#keywords').val());
    });

    ShowTable();

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


function getKeywords(){
  var chkArray = [];
  
  $(".tipo:checked").each(function() {
    chkArray.push($(this).next('label').text());
  });
  
  var selected;
  selected = chkArray.join(', ') ;

  if(selected.length > 0){
    $(".tipos_selected").html(selected);
  }else{
    $(".tipos_selected").html(""); 
  }
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
      //console.log(getEntities);
      if ($("#longitude").val() == '') return;
      if ($("#latitude").val() == '') return;
      if ($("#radius").val() == '') return;
      if ($("#limite").val() == '') return;

      
      $(".spinner").addClass("fa-spin")
      $(".loading").show();
      $.ajax({
                url: "<?php echo PYTHON_SRV_DOOR; ?>googleradius",
               contentType: 'application/json;charset=UTF-8',
               data: JSON.stringify(
                  {
                      'longitude':$("#longitude").val(), 
                      'latitude': $("#latitude").val(), 
                      "radius":$("#radius").val(), 
                      "limite": $("#limite").val(), 
                      "types":$('input[name=tipo]:checked', '#tiposForm').val(),
                      "keywords":$("#keywords").val()
                  }, 
                  null, 
                  '\t'),
               type: 'POST',
               success: function(data){
                    setMapEntities(data);
               },
               error: function(data){
                  $(".spinner").removeClass("fa-spin");                    
                  errorsCommonPython(data);
               },
               timeout: 20000 //in milliseconds
      });

    }



	// ************************************************
	// AJAX RESPONSES
	// ************************************************


  function setMapEntities(data) {
    deleteEntities();

    var data = JSON.parse(data);
      if (data['status'] == 'ERROR' || data['status'] == 'UNABLE') {
        $(".loading").hide();
        $(".spinner").removeClass("fa-spin")
        var msg = "<b>Não foram encontrados resultados para os parametros dados!</b><br>";
        if (data['error_code'] == 0) {
          msg += "Não existem serviços disponiveis.<br><br>";
          msg += "Possiveis problemas:";
          msg += "<ul>";
          msg += "<li>credenciais google (API KEY) incorrecta</li>";
          msg += "<li>limites do serviço atingido</li>";
          msg += "<ul>";
        } else
          msg += "Possiveis problemas: desconhecido"
        showMessage(msg);
        return;
      }


    for(var i=0;i<data.length;i++) {     
      var ma = new L.marker([data[i]['latitude'],data[i]['longitude']]).on('click', onMarkerClick);
      ma.bindPopup(data[i]['name']);
      entitiesArray.push(ma);       
      } 

    entitiesLayer = L.layerGroup(entitiesArray);

    entitiesLayer.addTo(mymap);     


  // fit to screen
    var fg = new L.featureGroup(entitiesArray);
    //mymap.fitBounds(fg.getBounds());
    mymap.flyToBounds(fg.getBounds());

   //   layerControl.addOverlay(entitiesLayer, "Entidades");  


    populateTable(data);

    $(".spinner").removeClass("fa-spin")
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
                { "data": "name" },
                { "data": "address" },
                { "data": "latitude" },
                { "data": "longitude" },
                { "data": "open_now" }
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
      console.log(data);
        datatable.clear();
        datatable.rows.add(data);
        datatable.draw();
    }


   $("#btn_tabelar").click(function(){
        $("#modal-ents").modal('show');
        datatable.draw();
    })


    function showError(msg) {
      $('.modal-body-modalDBError').html(msg);
        $('#modalDBError').modal('show');  
    }

    function showMessage(msg) {
      showError(msg);
    }

</script>


<?php include('../footer.php'); ?>