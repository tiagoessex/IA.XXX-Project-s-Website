<?php include('../header.php'); ?>



<script>
	$('head').append('<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>');
	$('head').append('<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />');


	$('head').append('<link rel="stylesheet" href="map.css"/>');
	$('head').append('<link rel="stylesheet" href="css/densidades.css"/>');
</script>



<div class="container-fluid">
	<div class="row">

		<!-- 
			************************************************
			************************************************
			MAP
			************************************************
			************************************************
			-->
		<div class="col-sm-8">
			<div id="mapid" style="height: 100vh"></div>
		</div>


		<!-- 
			************************************************
			************************************************
			SIDEBAR
			************************************************
			************************************************
			-->
		<div id="sidebar" class="col-sm-4">
			<h4>Fiscalizações <span id="dateSpan"></span></h4>
		</div>

	</div>


	<!-- 
	************************************************
	************************************************
	MODALS
	************************************************
	************************************************
	-->
	<div class="modal" id="modalDBError">
		<div class="modal-dialog">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">ERRO</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<!-- Modal body -->
				<div class="modal-body modal-body-modalDBError">
					error
				</div>

				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
				</div>

			</div>
		</div>
	</div>


</div>


<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
<script type="text/javascript" src="<?php echo DOMAIN_URL; ?>js/utils.js"></script>
<script type="text/javascript" src="<?php echo DOMAIN_URL; ?>js/markers.js"></script>

<script type="text/javascript">

	var entities;

	// ************************************************
	// INIT MAP
	// ************************************************
	var mymap = new L.Map('mapid', {
		center: new L.LatLng(39.5, -8),
		zoom: 7
	});

	L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
		maxZoom: 18,
		attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
			'<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
			'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
		id: 'mapbox.streets'
	}).addTo(mymap);


	var getUrlParameter = function getUrlParameter(sParam) {
		var sPageURL = window.location.search.substring(1),
			sURLVariables = sPageURL.split('&'),
			sParameterName,
			i;

		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');

			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
			}
		}
	};

	function representRoute(user, unidade, route) {
		$('#sidebar').append(
			'<div id="vehicle_' + user.username + '">' +
			'<h6>Inspetor ' + user.nome + '</h6>' +
			'<table id="vehicle_table_' + user.username + '" class="table table-striped text-center">' +
			`<tr>
										<th>ID</th>
										<th>Nome</th>
										<th>Utilidade (0-1)</th>
										<th>Chegada</th>
										<th>Partida</th>
									</tr>
								</table>
							</div>`);

		var wps = [];
		
		wps.push(L.Routing.waypoint(L.latLng(unidade.lat, unidade.lng), null));

		$('#vehicle_table_' + user.username).append('<tr>' +
			'<td colspan="3">' + unidade.nome + '</td>' +
			'<td> --- </td>' +
			'<td> 0 </td>' +
			'</tr>');

		route.forEach(function(d) {
			$('#vehicle_table_' + user.username).append('<tr>' +
				'<td>' + d['ID_ENTIDADE'] + '</td>' +
				'<td>' + d['NOME'] + '</td>' +
				'<td>' + d['UTILIDADE'] + '</td>' +
				'<td>' + '?' + '</td>' +
				'<td>' + d['visit_duration'].toHHMMSS() + '</td>' +
				'</tr>');

			wps.push(L.Routing.waypoint(L.latLng(d['lat'], d['lng']), d['ID_ENTIDADE']));
		});

		$('#vehicle_table_' + user.username).append('<tr>' +
			'<td colspan="3">' + unidade.nome + '</td>' +
			'<td> ? </td>' +
			'<td> --- </td>' +
			'</tr>');
			
		wps.push(L.Routing.waypoint(L.latLng(unidade.lat, unidade.lng), null));



		routingObject = L.Routing.control({
			waypoints: wps,
			createMarker: function(i, wpt, n) {
				if (i != 0 && i != n-1) {
					var selectedIcon;
					var e = entities[wpt.name];
					if (e['UTILIDADE'] < 0.5) {
						selectedIcon = greenIcon;
					} else if (e['UTILIDADE'] < 0.8) {
						selectedIcon = orangeIcon;
					} else {
						selectedIcon = redIcon;
					}
					var marker = L.marker(wpt.latLng, { icon: selectedIcon })
					marker.bindPopup('<b>' + e['NOME'] + '</b> (' + e['ID_ENTIDADE'] + ')<br>' +
					'Utilidade: <b>' + Math.round(e['UTILIDADE'] * 10000) / 100 + '</b>/100<br>'
					);

					return marker
				} else {
					return null
				}
			},
			router: new L.Routing.OSRMv1({
				//serviceUrl: 'http://127.0.0.1:5000/route/v1',
				serviceUrl: '<?php echo ROUTES_OSRM_API; ?>/route/v1',
				language: 'pt-PT'
			}),
			lineOptions: {
				styles: [{
						color: 'black',
						opacity: 0.15,
						weight: 9
					},
					{
						color: 'white',
						opacity: 0.8,
						weight: 6
					},
					{
						color: '#'+Math.floor(Math.random()*16777215).toString(16),
						opacity: 1,
						weight: 2
					}
				]
			}

		})
		
		routingObject.addTo(mymap);
	}


	$(document).ready(function() {
		$('#dateSpan').text('(' + getUrlParameter('date') + ')');

		var accessToken = localStorage.getItem('access-token');
		if (accessToken) {
			$.ajax({
				url: '<?php echo ROUTES_FLASK_API; ?>/route?date=' + getUrlParameter('date'),
				headers: {
					Authorization: 'Bearer ' + accessToken
				},
				success: function(data) {
					console.log(data);
					var user = JSON.parse(localStorage.getItem('user'));

					if (user.is_operacional) {
						representRoute(user, user.unidade, data);
					} else {
						entities = {};
						for (var key in data){
							data[key].routes.forEach(function(e) {
								entities[e['ID_ENTIDADE']] = e
							})

							representRoute(data[key].user, user.unidade, data[key].routes);
						}
					}

				}
			});
		}
	});

	Number.prototype.toHHMMSS = function () {
            var sec_num = parseInt(this, 10); // don't forget the second param
            var hours   = Math.floor(sec_num / 3600);
            var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
            var seconds = sec_num - (hours * 3600) - (minutes * 60);

            if (hours   < 10) {hours   = "0"+hours;}
            if (minutes < 10) {minutes = "0"+minutes;}
            if (seconds < 10) {seconds = "0"+seconds;}
            return hours+':'+minutes+':'+seconds;
        }
</script>



<?php include('../footer.php'); ?>
