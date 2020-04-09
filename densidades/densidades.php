<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>

<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/toggleswitch.css"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/map.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/densidades.css"/>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/css/bootstrap4-toggle.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>


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
				<!--
				<input type="checkbox" data-toggle="toggle" data-on="NUTS" data-off="UNIDADES" data-onstyle="primary" data-offstyle="success" data-size="sm" id="nuts_or_uo">
				-->
				<div class="toggle_stats_table">
		            UNI.
		            <label class="switchA">
		                <input type="checkbox" id="nuts_or_uo">
		                <span class="sliderA"></span>
		            </label>
		            NUTS
	        	</div>

				<div class="btn-group-vertical btn-block nuts_grp" style="display: none;">
					  <button type="button" class="btn btn-warning btn-lg fas" style = " margin-top: 5px;" id="btn_distritos" onclick="ReturnTo(0)" disabled>Distritos</button>
					  <button type="button" class="btn btn-warning btn-lg fas" style = " margin-top: 5px;" id="btn_concelhos" onclick="ReturnTo(1)" disabled>&#xf062; Concelhos</button>
					  <button type="button" class="btn btn-warning btn-lg fas" style = " margin-top: 5px;" id="btn_freguesias" onclick="ReturnTo(2)" disabled>&#xf062; Freguesias</button>
				</div> 

				<div class="btn-group-vertical btn-block uo_grp">
					  <button type="button" class="btn btn-danger btn-lg fas" style = " margin-top: 5px;" id="btn_ur" onclick="ReturnTo(0)" disabled>Unidades Regionais</button>
					  <button type="button" class="btn btn-danger btn-lg fas" style = " margin-top: 5px;" id="btn_uo" onclick="ReturnTo(1)" disabled>&#xf062; Unidades Operacionais</button>
					  <button type="button" class="btn btn-danger btn-lg fas" style = " margin-top: 5px;" id="btn_nuts" onclick="ReturnTo(2)" disabled>&#xf062; Concelhos/Freg.</button>
				</div> 

		
				<br><br>
				<div class="card" style="background-color: #FFFF66;">
	        	<div class="card-body">
				<form id="input_form_1">
					<label><b>Selecione:</b></label>
					<br>
					<label><input type="radio" name="densidades" value="entidades" id="entidades" checked> Entidades</label>
					<br>
					<label><input type="radio" name="densidades" value="denuncias" id="denuncias"> Denúncias</label>
					<br>
					<label><input type="radio" name="densidades" value="reclamacoes" id="reclamacoes"> Reclamações</label>
					<br>
					<label>
						<input type="radio" name="densidades" value="informacoes" id="informacoes"> Pedidos de Informação
					</label>
					<br>
					<label>
						<input type="radio" name="densidades" value="fiscalizacoes" id="fiscalizacoes" disabled> Fiscalizações
						<img src="../images/forbidden.png" alt="forbidden" style="width:16px;height:16px;"> 
					</label>
					<br>
					<label>
						<input type="radio" name="densidades" value="processos" id="processos" disabled> Processos
						<img src="../images/forbidden.png" alt="forbidden" style="width:16px;height:16px;"> 
					</label>
					<br>

					<input type="hidden" name="codigo_selected" id="codigo_selected">
					<input type="hidden" name="nome_selected" id="nome_selected">

	        	</form>
	        	</div>
	    		</div>

				<br>
				<div class="card" style="background-color: #CCCC00;">
	        	<div class="card-body">
	    		<form id="input_form_type">
	    			<label><input type="radio" name="densidades_type" value="type_total" id="type_total" data-label="" checked> Total</label>
	    			<label><input type="radio" name="densidades_type" value="type_ent" id="type_ent" data-label="nº entidades"> Ents</label>
	    			<label><input type="radio" name="densidades_type" value="type_pop" id="type_pop" data-label="população"> Pop</label>
	    			<label><input type="radio" name="densidades_type" value="type_area" id="type_area" data-label="área"> Área</label>
	    		</form>
	    		</div>
	    		</div>
				<br>

				<button type="button" class="btn btn-info btn-lg fas  btn-block" style = " margin-top: 5px;" id="btn_update" onclick="getPolys();return false;">
				<i class="fas fa-sync-alt spinner" id="spinner"></i> Calcular densidades</button>

				<br>
				<br>

				<button type="button" class="btn btn-outline-secondary btn-lg fas  btn-block" style = " margin-top: 5px;" id="btn_filter" data-toggle="modal" data-target="#modalFilter">Filtros</button>  			

			</div>

		</div>


	<!-- 
	************************************************
	************************************************
	MODALS
	************************************************
	************************************************
	-->



	<!-- ## Filter Modal ## -->
   <div class="modal" id="modalFilter">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Filtros</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body modal-body-modalFilter">
          

			<p class="h3">Geral</p>
	
				<p>	            
					De: <input type="date" id="datepicker1" min="<?php echo MIN_DATE; ?>" max="<?php echo MAX_DATE; ?>" value="<?php echo MIN_DATE; ?>">
					Até: <input type="date" id="datepicker2" min="<?php echo MIN_DATE; ?>" max="<?php echo MAX_DATE; ?>" value="">   
				</p>
				<p>
					<mark>Atenção: apenas o <i>mês</i> e o <i>ano</i> serão tidos em conta.</mark>
				</p>
			<hr>
        	<p class="h3">Entidades</p>
			<form id="input_form_entidades">
				<div class="form-check form-check-inline">
					<input type="checkbox" name="entidades_org" class="form-check-input" id="entidades_org" checked="checked">
					<label class="form-check-label" for="entidades_org"> Org</label>
				</div>
				<div class="form-check form-check-inline">
					<input type="checkbox" name="entidades_particulares" id="entidades_particulares" class="form-check-input" checked="checked"> 
					<label class="form-check-label" for="entidades_particulares"> Individuo</label>
				</div>				
			</form>
			<br>
			<p>
				<mark>Atenção: se pelo menos um não estiver selecionado, o o sistema irá considerar ambos como selecionados.</mark>
			</p>
			<hr>
			<p class="h3">Denúncias</p>

				<form id="input_form_denuncias">
	
	        		<label><b>Competência:</b></label>
	        		<br>
					<div class="form-check form-check-inline">
	        			<input type="checkbox" name="denuncias_indeterminada" class="form-check-input" id="denuncias_indeterminada" checked> 
	        			<label class="form-check-label" for="denuncias_indeterminada"> Indeterminada</label> 
	        		</div>
	        		<div class="form-check form-check-inline">
	        			<input type="checkbox" name="denuncias_XXX" class="form-check-input" id="denuncias_XXX"  checked> 
	        			<label class="form-check-label" for="denuncias_XXX"> XXX</label> 
	        		</div>
	        		<div class="form-check form-check-inline">
	        			<input type="checkbox" name="denuncias_XXX_outra_ent"  class="form-check-input" id="denuncias_XXX_outra_ent"  checked> 
	        			<label class="form-check-label" for="denuncias_XXX_outra_ent"> XXX e outra Entidade</label> 
	        		</div>
	        		<div class="form-check form-check-inline">
	        			<input type="checkbox" name="denuncias_XXX_trib" class="form-check-input"  id="denuncias_XXX_trib" checked> 
	        			<label class="form-check-label" for="denuncias_XXX_trib"> XXX e Tribunais</label>
	        		</div>
	        		<div class="form-check form-check-inline">
	        			<input type="checkbox" name="denuncias_outra_ent" class="form-check-input"  id="denuncias_outra_ent" checked> 
	        			<label class="form-check-label" for="denuncias_outra_ent"> Outra Entidade</label> 
	        		</div>
	        		<div class="form-check form-check-inline">
	        			<input type="checkbox" name="denuncias_trib" class="form-check-input" id="denuncias_trib"  checked> 
	        			<label class="form-check-label" for="denuncias_trib"> Tribunais</label> 
	        		</div>
	        		<div class="form-check form-check-inline">
	        			<input type="checkbox" name="denuncias_trib_outra" class="form-check-input" id="denuncias_trib_outra" checked>
	        			<label class="form-check-label" for="denuncias_trib_outra"> Outra Entidade e Tribunais</label> 
	        		</div>
	        		
	        	</form>	


				<br>
				<hr>

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
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
                Nesta página, o utilizador pode visualizar de forma iterativa a densidade de diversos indicadores/grandezas pelas distintas áreas geográficas.
              </p>
              <p>
                  As divisões geográficas divide-se em duas categorias:
                  <ul>
                      <li>Pelas unidades territoriais ou NUTS, desde distrito até às freguesias;</li>
                      <li>Pelas unidades definidas pela XXX, desde as unidades regionais até aos concelhos, passando pelas unidades operacionais.</li>
                  </ul>
                  A selecção do modo é efectuada através do selector no topo da coluna do lado direito <i><mark style="background-color: #0066FF;border-radius: 5px; padding: 2px;color:black;">UNIDADES</mark> / <mark style="background-color: #33FF00;border-radius: 5px; padding: 2px;color:black;">NUTS</mark></i>
              </p>

              <p>
                  Uma vez selecionada a área geográfica, o utilizador pode clicar no botão <i><mark style="background-color: #009999;border-radius: 5px; padding: 2px;color:black;">Calcular densidades</mark></i>.
              </p>
              <p>
                  No canto superior direito do mapa, pode ainda selecionar diversas opções de visualização do mapa:
                  <ul>
                      <li><i>Polígonos</i> - a demarcação das diversas áreas geográficas;</li>
                      <li><i>Labels</i> - etiqueta indicando o nome da área geográfica.</li>
                  </ul>
              </p>
              <p>
                  Note ainda que é possível mover-se na hierarquia no sentido ascendente, isto é, se por exemplo, quando o utilizador se encontra no nível de uma freguesia específica, ele pode através dos botões no canto superior direito, ir directamente para o nível do concelho ou distrito.
              </p>

              <hr>
              <b>Notas:</b><br>
                  <p>
                      Utilize o <mark>filtro</mark> para melhorar os dados.
                      O utilizador pode especificar desde um intervalo temporal até a quem compete investigar as denúncias.
                  </p>
                   <p>Campos utilizados para as datas:</p>
                   <ul>
                    <li><b>Entidades: </b> DT_INICIAL<br></li>                
                    <li><b>Denuncias: </b> DT_REGISTO<br></li>
                    <li><b>Pedidos de informações: </b> DT_REGISTO<br></li>
                    <li><b>Reclamações: </b> DT_REGISTO<br></li>
                    <li><b>Fiscalizações: </b> DT_AVERIG<br></li>
                    <li><b>Processos: </b> DT_SITUACAO<br></li>
                </ul>                
                   <p>
                       O número de processos foi retirado da tabela <i>entidade_processo</i>, e considerando apenas as entidades identificadas como <i>arguidas</i>.
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


<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>

<script src="<?php echo DOMAIN_URL; ?>js/utils.js"></script>

<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/js/bootstrap4-toggle.min.js"></script>


<script>
	// ************************************************
	// GLOBAL VARS AND CONST
	// ************************************************
	// current nuts selected
	// all 0s => all portugal (islands not included)
	var id_nuts = '000000';

	// levels
	// 0 - todos distritos
	// 1 - todos concelhos de distrito XX
	// 2 - todas freguesias de concelho XXYY
	// 3 - freguesia XXYYZZ
	var level = 0;

	// save the polys and arrays, so we can access them
	// individually, if needed
	var polysArray = [];
	var labelsArray = [];

	// layers
	var labelsLayer = null;
	var polysLayer = null;
	var layerControl = false;

	// layers visibility
	var isLabelsVisible = false;
	var isPolysVisible = true;
	var old_isLabelsVisible = isLabelsVisible;

	// highlight polys when mouseover
	var style = {
	  'default': {
	    'color': 'white',
	    'weight': 2
	  },
	  'highlight': {
	    'color': 'yellow',
	    'weight': 5
	  }
	};
	var highlight;

	// densities
	var densities = null;
	var gradient = [];	// ex: 0, 10, 20, 50, 100, 200, 500, 1000];
	var legend = null;	// color gradient and values
	var info = null;	// data on mouse over
	var current_densities = [];	// hold the densities according to typeofwhat

	// reset everything?
	var reset = false;


	var history = Array(4).fill('');
	history[0] = '000000';

	// FILTERS
	var ano_start = 1999;
	var mes_start = 0;
	var ano_end = 2020;
	var mes_end = 0;

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
	// PAGE STUFF
	// ************************************************
	$( document ).ready(function() {

		//getPolys();

	  	$("input[name='densidades']").change(function(e){
	    	if (current_densities.length > 0)
	    		getDensities();
	  	});


	  	$("input[name='densidades_type']").change(function(e){
			setDensities();
		});


	  	// if change => reset map + back to highest level + get polys and densities
		$('#nuts_or_uo').change(function() {
	        if($(this).is(":checked")) {
	        	$( ".uo_grp" ).css("display", "none");
	        	$( ".nuts_grp" ).css("display", "block");
	        } else {	        	 
	        	$( ".nuts_grp" ).css("display", "none");
	        	$( ".uo_grp" ).css("display", "block");
	        }
	        
			//resetAll(false);
			reset = true;
			getPolys();
    	});



		$("#modalFilter").on("hidden.bs.modal", function () {
			newFilterSet();
		});	

		
		$('#datepicker2').val(new Date().toDateInputValue());

		newFilterSet();



	  });



	function newFilterSet() {
		var start = $('#datepicker1').val().split('-');
		ano_start = start[0];
		mes_start = start[1];
		var end = $('#datepicker2').val().split('-');
		ano_end = end[0];
		mes_end = end[1];

		// new set => new densities iff there're polys already
		if (polysArray.length > 0) {
			getDensities();
		}
	}


	function resetAll(getpolys = true) {
			resetMap(); // comment line if getPolys() and no errors
	        densities = null;
			gradient = [];
			current_densities = [];
			//id_nuts = '000000';	// commenty if ReturnTo(0)
			//level = 0;			// commenty if ReturnTo(0)
			if (info) {
				info.remove();
				info = null;
			}
			if (legend) {
				legend.remove();
				legend = null;
			}
			history = ['000000','','',''];
			ReturnTo(0);
			updatePage();
			if (getpolys)
				getPolys();
	}

	function updatePage() {
		switch(level) {
			case 0:
				$("#btn_distritos").prop("disabled", true);
				$("#btn_concelhos").prop("disabled", true);
				$("#btn_freguesias").prop("disabled", true);

				$("#btn_ur").prop("disabled", true);
				$("#btn_uo").prop("disabled", true);
				$("#btn_nuts").prop("disabled", true);				
				break;
			case 1:
				$("#btn_distritos").prop("disabled", false);
				$("#btn_concelhos").prop("disabled", true);
				$("#btn_freguesias").prop("disabled", true);

				$("#btn_ur").prop("disabled", false);
				$("#btn_uo").prop("disabled", true);
				$("#btn_nuts").prop("disabled", true);
				break;
			case 2:
				$("#btn_distritos").prop("disabled", false);
				$("#btn_concelhos").prop("disabled", false);
				$("#btn_freguesias").prop("disabled", true);

				$("#btn_ur").prop("disabled", false);
				$("#btn_uo").prop("disabled", false);
				$("#btn_nuts").prop("disabled", true);
				break;
			case 3:
				$("#btn_distritos").prop("disabled", false);
				$("#btn_concelhos").prop("disabled", false);
				$("#btn_freguesias").prop("disabled", false);

				$("#btn_ur").prop("disabled", false);
				$("#btn_uo").prop("disabled", false);
				$("#btn_nuts").prop("disabled", false);
				break;
		}
	}

	Date.prototype.toDateInputValue = (function() {
	    var local = new Date(this);
	    local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
	    return local.toJSON().slice(0,10);
	});


	// ************************************************
	// AJAX CALLS 
	// ************************************************
	function getPolys() {		
		$(".spinner").addClass("fa-spin")
		$(".loading").show();

		if (reset) {
			resetAll(false);
			reset = false;
		}		

		var nuts_or_uo = $("#nuts_or_uo").is(":checked");

		$.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>geo/polys.php",  
                    method:"POST",
           			data:{	
           					id_nuts: history[level], 
           					level:level, 
           					nuts_or_uo:nuts_or_uo
           			},
            		dataType:"json", 
                   success:function(response) {
                   		setMapPolys(response);
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    }
		});
    };


    function getDensities() {
		$(".spinner").addClass("fa-spin")
		$(".loading").show();
		//$(".loading").show();	// uncomment if takes a long time to get the data

		//whichone = $("input[name='densidades']:checked").parent('label').text();
		densityofwhat = $('input[type=radio][name=densidades]:checked').attr('id');
		
	
		$.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>densidades/srv/getdensidades.php",  
                    method:"POST",
           			//data:{id_nuts: id_nuts, level:level, densityofwhat:densityofwhat, nuts_or_uo:nuts_or_uo},
           			data:{	
           					id_nuts: history[level], 
           					level:level, 
           					densityofwhat:densityofwhat, 
           					nuts_or_uo:$("#nuts_or_uo").is(":checked"),
           					ano_start:ano_start,
           					mes_start:mes_start,
           					ano_end:ano_end,
           					mes_end:mes_end,
           					org:$("#entidades_org").is(":checked"),
           					particulares:$("#entidades_particulares").is(":checked"),
           					d_indeterminada:$("#denuncias_indeterminada").is(":checked"),
           					d_XXX:$("#denuncias_XXX").is(":checked"),
           					d_XXX_outra_ent:$("#denuncias_XXX_outra_ent").is(":checked"),
           					d_XXX_trib:$("#denuncias_XXX_trib").is(":checked"),
           					d_outra_ent:$("#denuncias_outra_ent").is(":checked"),
           					d_trib:$("#denuncias_trib").is(":checked"),
           					d_trib_outra:$("#denuncias_trib_outra").is(":checked")
           			},
            		dataType:"json", 
                   success:function(response) {
                   		densities = response;
                   	//	console.log(response);
                   		setDensities();
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    }
		});
    };



 

   	// ************************************************
	// AJAX RESPONSES 
	// ************************************************

	function setMapPolys(polys) {
		resetMap();

		// create and save the polys
		for(var i=0;i<polys.length;i++) {
			// poly
			poly = L.polygon(JSON.parse(polys[i]['poly']),
						{
							color: 'white',
							fillColor: '#800026',	// yellow color, at least until we get the densities
							//fillOpacity: 0.65,
							fillOpacity: 0,	// once unidades densitites ok => set to 0
							dashArray: '3',
							weight: 2
						},
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																
			);
			poly.bindPopup(polys[i]['id']);
			polysArray.push(poly);

			// marker
			var marker = new L.marker(poly.getBounds().getCenter(), {opacity: 0.01});
			if ($("#nuts_or_uo").is(":checked") || level > 1) {
				marker.bindTooltip(polys[i]['nuts'], {permanent: true, className: 'label1', offset: [0, 0] });
			} else {
				marker.bindTooltip(polys[i]['id'], {permanent: true, className: 'label1', offset: [0, 0] });
			}
			labelsArray.push(marker);
		}

		polysLayer = L.layerGroup(polysArray);
		labelsLayer = L.layerGroup(labelsArray);

		
		// ok show me the map and labels
    	if (isPolysVisible) {
			polysLayer.addTo(mymap);			
		}
		if (isLabelsVisible) {
			labelsLayer.addTo(mymap);
		}
		

		// add the polys and the labels layer to the control
		// so we can hide/show them at will
		if(layerControl === false || !layerControl) {
        	layerControl = L.control.layers().addTo(mymap);
    	}    
    	layerControl.addOverlay(polysLayer, "Poligonos");
    	layerControl.addOverlay(labelsLayer, "Labels");

    	

    	// fit to screen
		var fg = L.featureGroup(polysArray);
		mymap.flyToBounds(fg.getBounds());

		// add action to each polygon
		fg.on('click', onMapClick);	

		
		// highlight
		polysLayer.eachLayer(function (layer) {
	  		layer.setStyle(style.default);
	  		layer.on('mouseover', function (e) {
	    		setHighlight(layer);
	  		});
		  	layer.on('mouseout', function (e) {
	    		unsetHighlight(layer);
	  		});
		});
		

		// new polys => new densities
		getDensities();		

	}


	// ************************************************
	// DENSITIES
	// ************************************************

	function calculateDensities() {
		var typeofwhat = $('input[type=radio][name=densidades_type]:checked').attr('id');
		//console.log(typeofwhat);

		current_densities = []
		
		for(var i=0;i<densities.length;i++) {

			if (typeofwhat == 'type_total') {
				current_densities[i] = densities[i]['density'];
			} else if (typeofwhat == 'type_area') {
				current_densities[i] = densities[i]['density']/densities[i]['area'];
			} else if (typeofwhat == 'type_pop') {
				current_densities[i] = densities[i]['density']/densities[i]['populacao'];
			} else if (typeofwhat == 'type_ent') {
				current_densities[i] = densities[i]['density']/densities[i]['entidades'];
			}
			if (current_densities[i] < 0 ||  isNaN(current_densities[i]) || current_densities[i] == null || !isFinite(current_densities[i])) {
				current_densities[i] = 0;
			}
		}
	 }

	function getMaxDensity() {
		var max = 0;

		for(var i=0;i<densities.length;i++) {
			if (level == 2) {
				if (densities[i]['id'].substring(4, 6) == '00') {
					continue;
				}
			}
			if (max < Number(current_densities[i]) && densities[i]['id'] != '000000') {
				max = Number(current_densities[i]);
			}
		}
		return max;
	}

	function getMinDensity() {
		var min = 0;

		for(var i=0;i<densities.length;i++) {
			if (level == 2) {
				if (densities[i]['id'].substring(4, 6) == '00') {
					continue;
				}
			}
			if (min > Number(current_densities[i]) && densities[i]['id'] != '000000') {
				min = Number(current_densities[i]);
			}
		}
		return min;
	}




	function getDensity(id) {
		for(var i=0;i<densities.length;i++) {
			if (densities[i]['id'] == id) {
				return current_densities[i];
			}
		}
		return 0;
	}



	function getColor(d) {
		return  d >= gradient[7]	? '#800026' :
				d >= gradient[6]	? '#BD0026' :
				d >= gradient[5]	? '#E31A1C' :
				d >= gradient[4]	? '#FC4E2A' :
				d >= gradient[3]	? '#FD8D3C' :
				d >= gradient[2]	? '#FEB24C' :
				d >= gradient[1]	? '#FED976' :
								  	'#FFEDA0';
	}

	function updateDensityLevels() {
		if (densities == null) return;
		calculateDensities();
		// calculate ranges
		var highest = Number(getMaxDensity());
		var lowest = Number(getMinDensity());
		var n_points = 8;
		var delta = highest / (n_points-1);
		//highest += delta * 0.1;
		gradient = [];

		if (highest <= 1) {
			for (var i = 0; i < n_points; i++) {
				var temp = delta * i;
				gradient.push(round0001(temp.toFixed(4)));			
			}
		} else {
			gradient = logspace(0,Math.log10(highest),n_points);
			for (var i = 0; i < gradient.length; i++) {
				gradient[i] = ~~gradient[i];
			}
		}

		addDensitiesColorLegend(highest);
		addDensitiesLegend();
	}



	function addDensitiesColorLegend(highest) {
		if (legend) {
			legend.remove();
			legend = null;
		}
		legend = L.control({position: 'topleft'});
		legend.onAdd = function (map) {

		    var div = L.DomUtil.create('div', 'info legend'),
		        labels = [];

		    // loop through our density intervals and generate a label with a colored square for each interval
		    for (var i = 0; i < gradient.length; i++) {
		    	div.innerHTML +=
		            '<i style="background:' + getColor(gradient[i]) + '"></i> ' +
		            gradient[i] + (gradient[i + 1] ? '&ndash;' + gradient[i + 1] + '<br>' : '+');

		    }

		    return div;
		};

		legend.addTo(mymap);
	}


	function addDensitiesLegend() {
		// TODO: 
		if (info) {
			info.remove();
			info = null;
		}
		// polygon info
		info = L.control({position: 'topleft'});

		info.onAdd = function (map) {
		    this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
		    this.update();
		    return this._div;
		};

		// method that we will use to update the control based on feature properties passed
		info.update = function (data, nuts) {
		    var label = $("input[name='densidades']:checked").parent('label').text();



			densityofwhat = $('input[type=radio][name=densidades]:checked').attr('id');
			if (densityofwhat == 'coimas') {
				var coima = $('input[type=radio][name=type_coimas]:checked').attr('name');
				label += " €"
			}

			var type = $("input[name='densidades_type']:checked").data("label");

			if (type.length > 0) {
				if (type == "área") {
					type = "km&#xb2;";
				}
				label += " / " + type;
			}

		    label += " em " + nuts;

		    var value = data;
		    if (data && data.toString().indexOf('.') >= 0) {
		    	value = parseFloat(Math.round(data * 100) / 100).toFixed(2);
		    }
		    this._div.innerHTML = '<h4>Densidade</h4>' +  (data ?
		        '<b>' + value + '</b> ' + label
		        : 'Passe o rato sobre uma área');
		};
		info.addTo(mymap);
	}

	function setDensities() {
		updateDensityLevels();

		// set the color of each poly
		for(i=0;i<polysArray.length;i++) {						
	    	var id = polysArray[i].getPopup().getContent();
	    	polysArray[i].setStyle({
    			fillColor: getColor(getDensity(id)),
    			fillOpacity: 0.65
			});
	    } 

		$(".spinner").removeClass("fa-spin");
		$(".loading").hide();

		updatePage();
	}


	// ************************************************
	// SPECIAL EFFECTS
	// ************************************************
	function setHighlight (layer) {
	  	if (highlight) {
	    	unsetHighlight(highlight);
	  	}
	  	layer.setStyle(style.highlight);
	  	highlight = layer;

		var popup = layer.getPopup();
		var id_nuts = popup.getContent();

		if (getDensity(id_nuts) == 0) {
			info.update('ZERO', id_nuts);
		} else {
	  		info.update(getDensity(id_nuts), id_nuts);
		}
	}

	function unsetHighlight (layer) {
	  highlight = null;
	  layer.setStyle(style.default);
	  info.update("");
	}


	// ************************************************
	// CLEANING
	// ************************************************

	// clear everything -- all layers cleared and removed
	function resetMap() {
		if (!(labelsLayer && polysLayer)) return;

		old_isLabelsVisible = isLabelsVisible;

		// remove all individual polys and markers
		for(i=0;i<polysArray.length;i++) {			
	    	labelsLayer.removeLayer(polysArray[i]);
	    }	     
		for(i=0;i<labelsArray.length;i++) {	    	
		    polysLayer.removeLayer(labelsArray[i]);
		}

		// remove all layers
	    if (labelsLayer) labelsLayer.remove();
	    if (polysLayer) polysLayer.remove();
	    if (layerControl) layerControl.remove();

	    // set to empty -- good pratice
	    layerControl = null;
	    polysArray = [];
		labelsArray = [];

		isLabelsVisible = old_isLabelsVisible;
	}

	
	// ************************************************
	// ACTIONS
	// ************************************************

	function onMapClick(e) {
		// iff new and old levels are different	get new polys
		var old_level = level;		
		var popup = e.layer.getPopup();
		if (isLabelsVisible)
			$(".leaflet-popup-close-button")[0].click();
		var temp_id = popup.getContent();
		var getpolys = true;
		// if nuts => 6 digit code
		if ($("#nuts_or_uo").is(":checked")) {
			//console.log("NUTS");
			if (temp_id.slice(2) == '0000') {
				level = 1;
			} else if (temp_id.slice(4) == '00') {
				level = 2;
			} else {
				level = 3;					
			}
		} else {
			//console.log("UNIDADE");
			// if unidades => [3,8] digit code
			if (temp_id.slice(NaN,2) == 'UR') {
				level = 1;
			} else if (temp_id.slice(NaN,2) == 'UO') {
				level = 2;
			} else {
				level = 3;
				if (temp_id.slice(4) != '00') {
					getpolys = false;
				}
			}
		}
		history[level] = temp_id;
		// complex if because of lisbon where concelhos e freguesias are mixed in UOX
		if (old_level != level || (old_level == level && getpolys)) {
			id_nuts = temp_id;
			if (getpolys) {
				getPolys();
			}
		}
	}

	function ReturnTo(target_level) {			
			old_level = level;
			level = target_level;
			id_nuts = history[level];
			if (old_level != level) {
				getPolys();
			}
	}


	mymap.on('layerremove', function(event) {
	    if(event.layer == labelsLayer) {
	     	isLabelsVisible = false;
	    }
	});

	mymap.on('layeradd', function(event) {
	    if(event.layer == labelsLayer) {
	     	isLabelsVisible = true;
	    }
	});

</script>



<?php include('../footer.php'); ?>