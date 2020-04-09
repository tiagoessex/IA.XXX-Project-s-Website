<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>

<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/toggleswitch.css"/>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/infodisplay.css"/>

<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>

<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/css/bootstrap4-toggle.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/map.css"/>


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

				<div class="toggle_stats_table">
		            UNI.
		            <label class="switchA">
		                <input type="checkbox" id="nuts_or_uo">
		                <span class="sliderA"></span>
		            </label>
		            NUTS
	        	</div>

				<div class="btn-group-vertical btn-block nuts_grp" style="display: none;">
					  <button type="button" class="btn btn-warning btn-lg fas" style = " margin-top: 5px;" id="btn_distritos" onclick="ReturnTo(0)">Distritos</button>
					  <button type="button" class="btn btn-warning btn-lg fas" style = " margin-top: 5px;" id="btn_concelhos" onclick="ReturnTo(1)" disabled>&#xf062; Concelhos</button>
					  <button type="button" class="btn btn-warning btn-lg fas" style = " margin-top: 5px;" id="btn_freguesias" onclick="ReturnTo(2)" disabled>&#xf062; Freguesias</button>
				</div> 

				<div class="btn-group-vertical btn-block uo_grp">
					  <button type="button" class="btn btn-danger btn-lg fas" style = " margin-top: 5px;" id="btn_ur" onclick="ReturnTo(0)" disabled>Unidades Regionais</button>
					  <button type="button" class="btn btn-danger btn-lg fas" style = " margin-top: 5px;" id="btn_uo" onclick="ReturnTo(1)" disabled>&#xf062; Unidades Operacionais</button>
					  <button type="button" class="btn btn-danger btn-lg fas" style = " margin-top: 5px;" id="btn_nuts" onclick="ReturnTo(2)" disabled>&#xf062; Concelhos/Freg.</button>
				</div> 

				<br><br>

				<form id="input_form_1">
		        	<label>Numero Máximo de Entidades
					<input type="text" class="form-control" name="limite" id="limite" value="20"/>
					</label>
					<br>				
					
					<label><input type="checkbox" name="org" value="org" id="org" checked> Org </label>
					<br>
					<label><input type="checkbox" name="individuo" value="individuo" id="individuo" checked> Indivíduo </label>
					<br>
					<label><input type="checkbox" name="activas" value="activas" id="activas" checked> Activas </label>
					<br>
					<label><input type="checkbox" name="naoactivas" value="naoactivas" id="naoactivas"  checked> Não activas </label>
					<br>
					
					<button type="button" class="btn btn-info btn-lg fas  btn-block" style = " margin-top: 5px;" id="btn_update" onclick="getEntities();return false;">
					<i class="fas fa-sync-alt search-spinner" id="spinner"></i> Procurar Entidades </button>	
					
					<br><br><br>
					<a href="javascript:void(0)" data-toggle="tooltip" title="Check para mostrar todas as informações relativas a esta entidade (denuncias, ...).">
					<label style="background-color: #99CCFF;border-radius: 10px; padding: 5px;color:black;"> 
						<input type="checkbox" name="mostrarentdialogo" value="mostrarentdialogo" id="mostrarentdialogo" checked> Mostrar info. das entidades 
					</label>
					</a>
					<br>
					<a href="javascript:void(0)" data-toggle="tooltip" title="Check para procurar automaticamente entidades cada vez que o mapa se alterar.">
					<label style="background-color: #99CCFF;border-radius: 10px; padding: 5px;color:black;">
						<input type="checkbox" name="auto" value="auto" id="auto"> Auto-update
					</label>
					</a>
					<!--
					<br>
					<label style="background-color: #99CCFF;border-radius: 10px; padding: 5px;color:black;">
						<input type="checkbox" name="aleatorio" value="aleatorio" id="aleatorio" checked disabled> Aleatórias
					</label>
					-->
					<br>
				

	        	</form>

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
          
          <div class="modal-body modal-body-help">
			<p>
                Nesta página, pode não só consultar informações sobre agentes econômicos, como também visualizar de forma iterativa as distintas áreas geográficas ondes estes se encontram.
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
                  Uma vez selecionada a área geográfica, o utilizador pode clicar no botão <i><mark style="background-color: #009999;border-radius: 5px; padding: 2px;color:black;">Procurar Entidades</mark></i> para que seja indicada uma amostra aleatória de agentes econômicos na área.
              </p>
              <p>
                  No canto superior direito do mapa, pode ainda selecionar diversas opções de visualização do mapa:
                  <ul>
                      <li><i>Polígonos</i> - a demarcação das diversas áreas geográficas;</li>
                      <li><i>Labels</i> - etiqueta indicando o nome da área geográfica.</li>
                  </ul>
              </p>
              <p>
                Caso a opção <i><mark style="background-color: #99CCFF;border-radius: 5px; padding: 2px;color:black;">Mostrar info das entidades</mark></i> estiver selecionada, o utilizar ao clicar numa dessas entidades poderá obter as informações mais relevantes, desde <i>denuncias</i>, <i>fiscalizações</i> e <i>processos</i> até à sua <i>localização geográfica</i>.
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


<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>

<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.4.0/js/bootstrap4-toggle.min.js"></script>

<script>

	// ************************************************
	// VARS AND CONST
	// ************************************************
	var polysArray = [];
	var labelsArray = [];
	var entitiesArray = [];
	var labelsLayer = null;
	var isLabelsVisible = false;
	var old_isLabelsVisible = isLabelsVisible;
	var polysLayer = null;
	var isPolysVisible = true;
	var old_isPolysVisible = isPolysVisible;
	var entitiesLayer = null;
	var isEntitiesVisible = true;
	var old_isEntitiesVisible = isEntitiesVisible;
	var layerControl = false;

	// 0 - todos distritos
	// 1 - todos concelhos de distrito XX
	// 2 - todas freguesias de concelho XXYY
	// 3 - freguesia XXYYZZ
	var level = 0;

	// for the entity tabs
	var tabmap = null;
    var tabmarker = null;


    // highlight polygon boundaries style when mouseover inside
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

	// it's global because it simplifies the code
	var g_id_denuncia = null; // message id to be analyzed (if required)


	// history will save the clicking path:
	// ex: Portugal -> Minho -> Braga
	// by saving it, it allows us to go back to the previous
	// level, using the same exact path, but on reverse:
	// Braga -> Minho -> Portugal 
	var history = Array(4).fill('');
	history[0] = '000000';	// Portugal -- all country



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
		getPolys();
		updatePage();

		$('#nuts_or_uo').change(function() {
	        if($(this).is(":checked")) {
	        	$( ".uo_grp" ).css("display", "none");
	        	$( ".nuts_grp" ).css("display", "block");
	        } else {	        	 
	        	$( ".nuts_grp" ).css("display", "none");
	        	$( ".uo_grp" ).css("display", "block");
	        }
	        resetAll();
    	});

    	$('[data-toggle="tooltip"]').tooltip(); 
	});


	function resetAll() {
			resetMap(); // comment line if getPolys() and no errors
			history = ['000000','','',''];
			ReturnTo(0, true);
			updatePage();
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



	// ************************************************
	// MAP CONTROL
	// ************************************************

	// clear everything
	function resetMap() {
		if (!(labelsLayer && polysLayer)) return;

		old_isLabelsVisible = isLabelsVisible;
		old_isPolysVisible = isPolysVisible;
		old_isEntitiesVisible = isEntitiesVisible;

		for(i=0;i<polysArray.length;i++) {			
	    	labelsLayer.removeLayer(polysArray[i]);
	    }
	     
		for(i=0;i<labelsArray.length;i++) {	    	
		    polysLayer.removeLayer(labelsArray[i]);
		}
		if (entitiesLayer) {
			for(i=0;i<entitiesArray.length;i++) {	    	
		    	entitiesLayer.removeLayer(entitiesArray[i]);
			}
		}

	    if (labelsLayer) labelsLayer.remove();
	    if (polysLayer) polysLayer.remove();
	    if (entitiesLayer) entitiesLayer.remove();
	    if (layerControl) layerControl.remove();

	    layerControl = null;
	    polysArray = [];
		labelsArray = [];
		entitiesArray = [];

		isLabelsVisible = old_isLabelsVisible;
		isPolysVisible = old_isPolysVisible;
		isEntitiesVisible = old_isEntitiesVisible;
	}

	function deleteEntities() {
		if (entitiesLayer)
			layerControl.removeLayer(entitiesLayer);

		old_isEntitiesVisible = isEntitiesVisible;

		if (entitiesLayer) {
			for(i=0;i<entitiesArray.length;i++) {	    	
		    	entitiesLayer.removeLayer(entitiesArray[i]);
			}
		}

	    if (entitiesLayer) entitiesLayer.remove();
		entitiesArray = [];

		isEntitiesVisible = old_isEntitiesVisible;
	}
	
	mymap.on('layerremove', function(event) {
	    if(event.layer == polysLayer) {
	     	isPolysVisible = false;
	    } else if(event.layer == labelsLayer) {
	     	isLabelsVisible = false;
	    } else if(event.layer == entitiesLayer) {
	     	isEntitiesVisible = false;
	    }
	});

	mymap.on('layeradd', function(event) {
	    if(event.layer == polysLayer) {
	     	isPolysVisible = true;	     	
	    } else if(event.layer == labelsLayer) {
	     	isLabelsVisible = true;
	    } else if(event.layer == entitiesLayer) {
	     	isEntitiesVisible = true;
	    }
	});		


	function addPolygon(arr, msg, color, fillcolor, fillopacity) {
		polygon = L.polygon(arr,
						{
							color: color,
							fillColor: fillcolor,
							fillOpacity: fillopacity
						}
		);

		polygon.bindPopup(msg);
		return polygon;
	}

	function addLabel(coords, msg, style) {
		var marker = new L.marker(coords, {opacity: 0.01});
		marker.bindTooltip(msg, {permanent: true, className: style, offset: [0, 0] });
		return marker
	}


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
			//id_nuts = temp_id;
			if (getpolys) {
				getPolys();
			}
		}
	}

	function onMarkerClick(e) {
		if (!$('#mostrarentdialogo').is(":checked"))
			return;

		var popup = e.target.getPopup().getContent();
		if (isLabelsVisible)
			$(".leaflet-popup-close-button")[0].click();
		var data = popup.split(" # ");
		var id = data[1];
		var name = data[0];

		$('#modal_entity_data').modal('show');
		getEntityGeral(id);
		getEntityGeo(id);	
		getEntityDenuncias(id);// 2709137
		getEntityReclamacoes(id);
		//getEntityFiscalizacoes(id);
		//getEntityProcessos(id);
	}

	function ReturnTo(target_level, loadpolys = false) {
			//console.log("ReturnTo");
			old_level = level;
			var id;
		
			level = target_level;
			id = history[level];
			//console.log("RETURN TO > " + level);
			if (old_level != level || loadpolys) {
				getPolys();				
			}
			updatePage(); 
	}


	// ************************************************
	// AJAX ROUTINES 
	// ************************************************
	function getPolys() {
		$(".search-spinner").addClass("fa-spin")
		$(".loading").show();

		$.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>geo/polys.php",  
                    method:"POST",
           			data:{id_nuts: history[level], level:level, nuts_or_uo:$("#nuts_or_uo").is(":checked")},
            		dataType:"json", 
                   success:function(response) {                   	
                   		setMapPolys(response);
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                        $(".search-spinner").removeClass("fa-spin");                   
                    }
		});
    };


	function getEntities() {
    	$(".search-spinner").addClass("fa-spin")

		$.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/entities.php",  
                    method:"POST",
           			//data:$('#input_form_1').serialize(),
           			data:{codigo: history[level], limite:20, level: level, nuts_vs_unidades:$("#nuts_or_uo").is(":checked")},
            		cache: false,
            		dataType:"json", 
            		contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                   		setMapEntities(response);
                   		$(".search-spinner").removeClass("fa-spin");
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                        $(".search-spinner").removeClass("fa-spin");                    
                    }
		});
    };


	function getEntityGeral(id_entidade) {
    	$(".search-spinner").addClass("fa-spin")
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
                        $(".search-spinner").removeClass("fa-spin");
                    }
		});
    };

    function getEntityGeo(id_entidade) {
    	$(".search-spinner").addClass("fa-spin")
		$.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/getgeocod.php",  
                    method:"POST",
           			data:{id_entidade: id_entidade},
            		cache: false,
            		dataType:"json", 
            		contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                   		populateGeo(response); 
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                        $(".search-spinner").removeClass("fa-spin");
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
                        $(".search-spinner").removeClass("fa-spin");
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


	function setMapPolys(polys) {
		resetMap();		
		for(var i=0;i<polys.length;i++) {
			poly = addPolygon(
				JSON.parse(polys[i]['poly']), 
				polys[i]['id'], 
				'white', 
				'#f03', 
				0.5);	
			polysArray.push(poly);

			labelsArray.push(addLabel(
				poly.getBounds().getCenter(),
				($("#nuts_or_uo").is(":checked")||level > 1?polys[i]['nuts']:polys[i]['id']),
				'label1')
			);
		}

		polysLayer = L.layerGroup(polysArray);
		labelsLayer = L.layerGroup(labelsArray);

		if (isPolysVisible) {
			polysLayer.addTo(mymap);			
		}
		if (isLabelsVisible) {
			labelsLayer.addTo(mymap);
		}
		
		if(layerControl === false || !layerControl) {
        	layerControl = L.control.layers().addTo(mymap);

    	}    
    	layerControl.addOverlay(polysLayer, "Poligonos");
    	layerControl.addOverlay(labelsLayer, "Labels");  

    	// fit to screen
		var fg = L.featureGroup(polysArray);
		//mymap.fitBounds(fg.getBounds());
		mymap.flyToBounds(fg.getBounds());
		fg.on('click', onMapClick);

    	updatePage();

    	mymap.closePopup();

    	if ($('#auto').is(":checked")) {
    		getEntities();
    	}


    	$(".search-spinner").removeClass("fa-spin")
    	$(".loading").hide();


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

	}


	function setMapEntities(coord) {
		deleteEntities();
		for(var i=0;i<coord.length;i++) {			
			var ma = new L.marker([coord[i]['latitude'],coord[i]['longitude']]).on('click', onMarkerClick);
			ma.bindPopup(coord[i]['nome'] + " # " + coord[i]['id']);
			entitiesArray.push(ma);				
	    } 

		entitiesLayer = L.layerGroup(entitiesArray);

		if (isEntitiesVisible) {
			entitiesLayer.addTo(mymap);			
		}

    	layerControl.addOverlay(entitiesLayer, "Entidades");  

	    $(".search-spinner").removeClass("fa-spin")
	}





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


        $('a[href="#nav-geral"]').click();
		$('#modal_entity_data').modal('show');


		mymap.closePopup();	// close all popups
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





	function setHighlight (layer) {
	  if (highlight) {
	    unsetHighlight(highlight);
	  }
	  layer.setStyle(style.highlight);
	  highlight = layer;
	}

	function unsetHighlight (layer) {
	  highlight = null;
	  layer.setStyle(style.default);
	}


</script>


<?php include('../footer.php'); ?>