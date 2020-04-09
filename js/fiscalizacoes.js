var app = angular.module('fiscalizacoesApp', ['nemLogging', 'ui-leaflet', 'ngTagsInput'])

app.factory('AuthService', function($window) {
    return {
        getToken: function() {
            return $window.localStorage.getItem('access-token');
        },
        getUser: function() {
            return $window.localStorage.getItem('user');
        },
        getRoute: function() {
            return $window.localStorage.getItem('route');
        },
        setRoute: function(route) {
            return $window.localStorage.route = route;
        }
    };
});

app.factory('AuthInterceptor', function(AuthService, $q) {
    return {
        request: function(config) {
            config.headers = config.headers || {};

            if (AuthService.getToken()) {
                config.headers['Authorization'] = 'Bearer ' + AuthService.getToken();
                //config.headers['Access-Control-Allow-Origin'] = '*';
            }

            return config;
        },

        responseError: function(response) {
            if (response.status === 401 || response.status === 403) {
                console.log('Sem autorização');
            }

            return $q.reject(response);
        }
    }
});

app.config(function($httpProvider) {
    // to inject authorization header
    $httpProvider.interceptors.push('AuthInterceptor');
});

app.controller('newCtrl', function($scope, $location, $http, $filter, $window, AuthService, leafletData, $timeout) {
    $scope.inputs = JSON.parse(AuthService.getRoute());
    $scope.inspectionStart = new Date($scope.inputs.date)
    console.log($scope.inputs);


    angular.extend($scope, {
        center: {
            lat: 39.5,
            lng: -8,
            zoom: 7
        },
        defaults: {
            tileLayer: 'https://api.tiles.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw',
            maxZoom: 18,
            tileLayerOptions: {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                    '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>'
            }
        },
        icons: customIcons,
        markers: {}
    });

    $scope.ued = [];
    $scope.getRoutes = function() {
        var copyColors = colors.slice();


        $scope.isLoading = true;
        $scope.routes = [];
        $scope.others = [];
        $scope.othersMarkers = {};
        $scope.showOthers = true;
        $scope.metrics = {};
        var entidades = [];
        if ($scope.entities) {
            entidades = Object.values($scope.entities);
        }
        $scope.inputs.entidades = entidades;
        $scope.inputs.durations = $scope.ued.durations;
        //$http.post('http://' + $location.host() + ':5050/calculate', $scope.inputs)
        $http.post(ROUTES_FLASK_API + 'calculate', $scope.inputs)
            .then(function(response) {
                console.log(response.data);
                $scope.routes = response.data.routes;
                $scope.others = response.data.others;
                $scope.ued = response.data.ued;
                $scope.metrics = response.data.metrics;

                $scope.entities = {}
                $scope.ued.entidades.forEach(function(e) {
                    $scope.entities[e['id']] = e
                });
                $scope.routingObjects = []
                for (var key in $scope.routes) {
                    $scope.routes[key].showRO = true;
                    $scope.routes[key].gmapsUrl = 'https://www.google.com/maps/dir/';

                    var L = $window.L;
                    var wps = [];
                    if ($scope.inputs.brigadas[key].start_type == 'uo') {
                        wps.push(L.Routing.waypoint(L.latLng($scope.ued.unidade.lat, $scope.ued.unidade.lng), null));
                        $scope.routes[key].gmapsUrl += $scope.ued.unidade.lat + ',' + $scope.ued.unidade.lng + '/';
                    } else if ($scope.inputs.brigadas[key].start_type == 'other') {
                        wps.push(L.Routing.waypoint(L.latLng(parseFloat($scope.inputs.brigadas[key].start_point.lat), parseFloat($scope.inputs.brigadas[key].start_point.lon)), $scope.inputs.brigadas[key].start_point.display_name));
                        $scope.routes[key].gmapsUrl += $scope.inputs.brigadas[key].start_point.lat + ',' + $scope.inputs.brigadas[key].start_point.lon + '/';
                    }

                    $scope.routes[key].routes.forEach(function(e) {
                        wps.push(L.Routing.waypoint(L.latLng(e['lat'], e['lng']), e['id']));
                        $scope.routes[key].gmapsUrl += e['lat'] + ',' + e['lng'] + '/';
                    });

                    if ($scope.inputs.brigadas[key].end_type == 'uo') {
                        wps.push(L.Routing.waypoint(L.latLng($scope.ued.unidade.lat, $scope.ued.unidade.lng), null));
                        $scope.routes[key].gmapsUrl += $scope.ued.unidade.lat + ',' + $scope.ued.unidade.lng + '/';
                    } else if ($scope.inputs.brigadas[key].end_type == 'other') {
                        wps.push(L.Routing.waypoint(L.latLng(parseFloat($scope.inputs.brigadas[key].end_point.lat), parseFloat($scope.inputs.brigadas[key].end_point.lon)), $scope.inputs.brigadas[key].end_point.display_name));
                        $scope.routes[key].gmapsUrl += $scope.inputs.brigadas[key].end_point.lat + ',' + $scope.inputs.brigadas[key].end_point.lon + '/';
                    }
                    $scope.routes[key].gmapsUrl += '&travelmode=driving';

                    // choose random color
                    var rColor = Math.floor(Math.random() * copyColors.length);
                    var selColor = copyColors[rColor]
                    copyColors.splice(rColor, 1)

                    $scope.routes[key].ro = L.Routing.control({
                        waypoints: wps,
                        createMarker: function(i, wpt, n) {
                            if (i != 0 && i != n - 1) {
                                var selectedIcon;
                                var e = $scope.entities[wpt.name];
                                if (e['utility'] < 0.5) {
                                    selectedIcon = customIcons.greenIcon;
                                } else if (e['utility'] < 0.8) {
                                    selectedIcon = customIcons.orangeIcon;
                                } else {
                                    selectedIcon = customIcons.redIcon;
                                }
                                var marker = L.marker(wpt.latLng, {
                                    icon: new L.Icon(selectedIcon)
                                })

                                var estado_h = estadoh(e['ESTADO_HORARIO']);

                                marker.bindPopup('<b>' + e['NOME'] + '</b> (' + e['id'] + ')<br>' +
                                    'Utilidade: <b>' + Math.round(e['utility'] * 10000) / 100 + '</b>/100<br>' +
                                    'Chegada prevista às: ' + $filter('toHHMMSS')($scope.inspectionStart.getHours() * 3600 + $scope.inspectionStart.getMinutes() * 60 + e['partial_duration']) + '<br>' +
                                    'Duração da inspeção: ' + $filter('toHHMMSS')(e['visit_duration']) + '<br>' +
                                    'Horário: ' + '<span class="badge badge-' + estado_h.class + '">' + estado_h.name + '</span>' + '<br>' +
                                    '<button class="btn btn-danger btn-block remove-entity-marker mt-2" entity-id="' + e['id'] + '" entity-name="' + e['NOME'] + '">Excluir da rota</button>'
                                );

                                return marker
                            } else { // ponto de partida ou chegada
                                var marker = L.marker(wpt.latLng, {
                                    icon: new L.Icon(customIcons.XXXIcon)
                                })

                                var popupMessage = wpt.name || '<b>' + $scope.ued.unidade.NOME + '</b>'
                                marker.bindPopup(popupMessage);

                                return marker
                            }
                        },
                        router: new L.Routing.OSRMv1({
                            //serviceUrl: 'http://' + $location.host() + ':5000/route/v1',
                            //serviceUrl: 'http://router.project-osrm.org/route/v1',
                            serviceUrl: ROUTES_OSRM_API + 'route/v1',
                            language: 'pt-PT'
                        }),
                        lineOptions: {
                            styles: [{
                                    color: 'black',
                                    opacity: 0.6,
                                    weight: 8
                                },
                                {
                                    color: 'white',
                                    opacity: 0.8,
                                    weight: 5
                                },
                                {
                                    color: selColor,
                                    opacity: 1,
                                    weight: 3
                                }
                            ]
                        }

                    }).on('routeselected', function(e) {
                        // split instructions
                        e.route.splittedInstructions = [
                            []
                        ];
                        i_counter = 0;
                        for (var i in e.route.instructions) {
                            e.route.splittedInstructions[i_counter].push(e.route.instructions[i])
                            if (e.route.instructions[i].type == 'WaypointReached') {
                                e.route.splittedInstructions.push([]);
                                i_counter++;
                            }
                        }

                        e.target.selectedInstructions = e.route.instructions

                        console.log(e)
                    })
                }

                // after all routing objects are created, add them to the map
                leafletData.getMap('routeMap').then(function(map) {
                    for (var key in $scope.routes) {
                        $scope.routes[key].ro.addTo(map);

                    }
                });

                console.log($scope.markers)
                    // add others entities
                for (var key in $scope.others) {
                    e = $scope.others[key];
                    var estado_h = estadoh(e['ESTADO_HORARIO']);
                    $scope.othersMarkers[e.id] = {
                        lat: e.lat,
                        lng: e.lng,
                        icon: customIcons.otherIcon,
                        message: '<b>' + e['NOME'] + '</b> (' + e['id'] + ')<br>' +
                            'Utilidade: <b>' + Math.round(e['utility'] * 10000) / 100 + '</b>/100<br>' +
                            'Horário: ' + '<span class="badge badge-' + estado_h.class + '">' + estado_h.name + '</span>' + '<br>' +
                            '<button class="btn btn-primary btn-block add-entity-marker mt-2" entity-id="' + e['id'] + '" entity-name="' + e['NOME'] + '">Adicionar à rota</button>'
                    }
                    $scope.markers = $scope.othersMarkers
                }

                var checkExist = setInterval(function() { // função para aguardar que div dos horários exista pois demora alguns ms a ser criada
                    if ($('#horario-' + $scope.inputs.brigadas[0].vehicle).length) {
                        clearInterval(checkExist);
                        // representação dos horários
                        for (var key in $scope.routes) {
                            var locations = [];
                            var events = [];

                            // primeira linha do horario
                            locations.push({ id: 'brigada', name: 'Brigada ' + $scope.inputs.brigadas[key].vehicle });

                            var tmpStart = $scope.inspectionStart;
                            var tmpEnd;
                            var beforeName = $scope.inputs.brigadas[key].start_type == 'uo' ? 'UO' : $scope.inputs.brigadas[key].start_point.display_name
                            var routes = $scope.routes[key].routes
                            routes.forEach(function(d, index) {
                                tmpEnd = new Date(tmpStart.getTime() + d['to_duration'] * 1000)
                                events.push({
                                    name: beforeName + ' -> ' + d.NOME,
                                    location: 'brigada',
                                    start: tmpStart,
                                    end: tmpEnd,
                                    className: 'oh-driving'
                                })
                                tmpStart = tmpEnd;
                                if (d['wait_duration'] > 0) {
                                    tmpEnd = new Date(tmpStart.getTime() + d['wait_duration'] * 1000)
                                    events.push({
                                        name: d.NOME + ' (Espera)',
                                        location: 'brigada',
                                        start: tmpStart,
                                        end: tmpEnd,
                                        className: 'oh-waiting'
                                    })
                                    tmpStart = tmpEnd;
                                }
                                tmpEnd = new Date(tmpStart.getTime() + d['visit_duration'] * 1000);
                                events.push({
                                    name: d.NOME,
                                    location: 'brigada',
                                    start: tmpStart,
                                    end: tmpEnd,
                                    className: 'oh-inspecting'
                                })
                                events.push({
                                    name: d.NOME,
                                    location: d.id,
                                    start: tmpStart,
                                    end: tmpEnd,
                                    className: 'oh-inspecting'
                                })
                                tmpStart = tmpEnd;
                                beforeName = d.NOME
                            });
                            tmpEnd = new Date(tmpStart.getTime() + routes[routes.length - 1]['from_duration'] * 1000); // viagem até ao ponto de chegada
                            events.push({
                                name: beforeName + ' -> ' + ($scope.inputs.brigadas[key].end_type == 'uo' ? 'UO' : $scope.inputs.brigadas[key].end_point.display_name),
                                location: 'brigada',
                                start: tmpStart,
                                end: tmpEnd,
                                className: 'oh-driving'
                            })

                            routes.forEach(function(d, index) {
                                locations.push({ id: d.id, name: d.NOME });
                                events = events.concat(binary2events($scope.inspectionStart, d.HORARIO, d.id));
                            })

                            $('#horario-' + $scope.inputs.brigadas[key].vehicle).skedTape({
                                start: $scope.inspectionStart,
                                end: new Date($scope.inspectionStart.getTime() + 86400000),
                                showEventTime: true,
                                showEventDuration: true,
                                locations: locations,
                                events: events.slice(),
                                tzOffset: 0,
                                formatters: {
                                    date: function(date) {
                                        return $.fn.skedTape.format.date(date, 'l', '/');
                                    }
                                }
                            });
                            $('#horario-' + $scope.inputs.brigadas[key].vehicle + ' div.sked-tape__time-canvas').css('min-width', '3000px');
                        }
                    }
                }, 500);


                $scope.isLoading = false;

            })
            .catch(function(response) {
                console.log('ERRO');
                console.log(response);
            });
    }

    $(document).on("click", "button.add-entity-marker", function() {
        id = parseInt($(this).attr('entity-id'));
        leafletData.getMap('routeMap').then(function(map) {
            for (var key in $scope.routes) {
                $scope.routes[key].ro.remove();
            }
            console.log(id);
            console.log($scope.entities);
            $scope.entities[id].utility = 10;
            console.log($scope.entities);
            $scope.markers = [];
            $scope.getRoutes();
        });

    })

    $(document).on("click", "button.remove-entity-marker", function() {
        id = parseInt($(this).attr('entity-id'));
        leafletData.getMap('routeMap').then(function(map) {
            for (var key in $scope.routes) {
                $scope.routes[key].ro.remove();
            }
            console.log(id);
            console.log($scope.entities);
            $scope.entities[id].utility = -10;
            console.log($scope.entities);
            $scope.markers = [];
            $scope.getRoutes();
        });

    })

    $scope.getRoutes();

    $scope.newRoutes = function() {
        leafletData.getMap('routeMap').then(function(map) {
            for (var key in $scope.routes) {
                $scope.routes[key].ro.remove();
            }
            $scope.markers = [];
            $scope.getRoutes();
        });
    }

    $scope.bringToFrontRO = function(showRO, ro) {
        // removes routing from map and backs in (that how leaflet routing machine does it)
        if (showRO) {
            leafletData.getMap('routeMap').then(function(map) {
                ro.remove();
                ro.addTo(map);
            });
        }

    }

    $scope.toggleRO = function(showRO, ro) {
        leafletData.getMap('routeMap').then(function(map) {
            if (showRO) {
                ro.addTo(map);
            } else {
                ro.remove();
            }
        });
    }

    $scope.toggleOthers = function() {
        if ($scope.showOthers) {
            console.log($scope.othersMarkers)
            $scope.markers = $scope.othersMarkers
        } else {
            $scope.markers = {}
        }
    }

});

app.controller('newModalCtrl', function($scope, $http, $window, AuthService, $location) {
    $scope.currentStep = 1;
    $scope.inputs = JSON.parse(AuthService.getRoute());

    // DateTimePicker initialization
    if ($scope.inputs && $scope.inputs.date) {
        $scope.newDate = $scope.inputs.date
    } else {
        var d = new Date();
        $scope.newDate = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate() + ' 8:00'
    }
    $('#nova-datepicker').datetimepicker({
        uiLibrary: 'bootstrap4',
        format: 'yyyy-mm-dd HH:MM',
        value: $scope.newDate,
        change: function(e) {
            $scope.newDate = $('#nova-datepicker').val();
            var newD = new Date($scope.newDate)
            $('#break-start-timepicker').val(newD.getHours() + ':' + newD.getMinutes())
        }
    });

    // Break configuration
    $scope.hasBreak = true;
    if ($scope.inputs && $scope.inputs.break) {
        $scope.break = $scope.inputs.break;
    } else {
        $scope.break = {};
        $scope.break.start = '13:00';
        $scope.break.end = '15:00';
    }

    var startRange = parseInt($scope.break.start.substr(0, 2)) * 60 + parseInt($scope.break.start.substr(3, 2));
    var endRange = parseInt($scope.break.end.substr(0, 2)) * 60 + parseInt($scope.break.end.substr(3, 2));

    $('#slider-range').bootstrapSlider({
        range: true,
        min: 0,
        max: 1440,
        step: 15,
        value: [startRange, endRange],
        focus: true
    });

    $('#slider-range').on('change', function(ui) {
        var hours1 = Math.floor(ui.value.newValue[0] / 60);
        var minutes1 = ui.value.newValue[0] - (hours1 * 60);

        if (hours1.length == 1) hours1 = '0' + hours1;
        if (minutes1.length == 1) minutes1 = '0' + minutes1;
        if (minutes1 == 0) minutes1 = '00';

        $scope.break.start = hours1 + ':' + minutes1;
        $scope.$apply()

        var hours2 = Math.floor(ui.value.newValue[1] / 60);
        var minutes2 = ui.value.newValue[1] - (hours2 * 60);

        if (hours2.length == 1) hours2 = '0' + hours2;
        if (minutes2.length == 1) minutes2 = '0' + minutes2;
        if (minutes2 == 0) minutes2 = '00';
        if (hours2 == 24) {
            hours2 = 23;
            minutes2 = '59';
        }

        $scope.break.end = hours2 + ':' + minutes2;
        $scope.$apply()
    });
    // Break configuration (end)

    $scope.defaults = {
        tileLayer: 'https://api.tiles.mapbox.com/v4/mapbox.streets/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw',
        tileLayerOptions: {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>'
        }
    }

    $scope.addressSearch = function(type, n) {
        var guess = '';
        if (type == 'start') {
            guess = $scope.auxPoints[n].startInput;
            delete $scope.auxPoints[n].startResultSelected;
            delete $scope.brigadas[n].startPoint;
        } else if (type == 'end') {
            guess = $scope.auxPoints[n].endInput;
            delete $scope.auxPoints[n].endResultSelected;
            delete $scope.brigadas[n].endPoint;
        }

        $http.get('https://nominatim.openstreetmap.org/search?format=json&limit=6&q=' + guess)
            .then(function(response) {
                console.log(response)
                if (type == 'start') {
                    $scope.auxPoints[n].startResults = response.data;
                } else if (type == 'end') {
                    $scope.auxPoints[n].endResults = response.data;
                }
                if (response.data.length == 1) {
                    $scope.selectResult(type, n, 0); // select automatically if there is only one result
                }
            })
            .catch(function(response) {
                console.log('ERRO');
                console.log(response);
            });
    }


    $scope.selectResult = function(type, n, i) {
        if (type == 'start') {
            $scope.brigadas[n].start_point = $scope.auxPoints[n].startResults[i];
            $scope.auxPoints[n].startMarker = {
                0: {
                    lat: Number($scope.auxPoints[n].startResults[i].lat),
                    lng: Number($scope.auxPoints[n].startResults[i].lon)
                }
            }
            $scope.auxPoints[n].startBounds = {
                northEast: {
                    lat: Number($scope.auxPoints[n].startResults[i].boundingbox[1]),
                    lng: Number($scope.auxPoints[n].startResults[i].boundingbox[3])
                },
                southWest: {
                    lat: Number($scope.auxPoints[n].startResults[i].boundingbox[0]),
                    lng: Number($scope.auxPoints[n].startResults[i].boundingbox[2])
                }
            };

            $scope.auxPoints[n].startResultSelected = i; // change in last to avoid conflits in DOM
        } else if (type == 'end') {
            $scope.brigadas[n].end_point = $scope.auxPoints[n].endResults[i];
            $scope.auxPoints[n].endMarker = {
                0: {
                    lat: Number($scope.auxPoints[n].endResults[i].lat),
                    lng: Number($scope.auxPoints[n].endResults[i].lon)
                }
            }
            $scope.auxPoints[n].endBounds = {
                northEast: {
                    lat: Number($scope.auxPoints[n].endResults[i].boundingbox[1]),
                    lng: Number($scope.auxPoints[n].endResults[i].boundingbox[3])
                },
                southWest: {
                    lat: Number($scope.auxPoints[n].endResults[i].boundingbox[0]),
                    lng: Number($scope.auxPoints[n].endResults[i].boundingbox[2])
                }
            }
            $scope.auxPoints[n].endResultSelected = i; // change in last to avoid conflits in DOM
        }
        console.log($scope.auxPoints[n])
    }

    if ($scope.inputs && $scope.inputs.brigadas) {
        $scope.nBrigadas = $scope.inputs.brigadas.length;
        $scope.brigadas = $scope.inputs.brigadas;
    } else {
        $scope.nBrigadas = 0;
        $scope.brigadas = [];
    }
    $scope.auxPoints = [];

    $scope.selectedCaes = [];
    $scope.selectedActividades = [];

    if ($scope.inputs && $scope.inputs.objective_f) {
        $scope.objectiveF = $scope.inputs.objective_f;
    } else {
        $scope.objectiveF = '0';
    }
    $scope.newEntidades = [];

    $scope.addBrigada = function() {
        $scope.brigadas[$scope.nBrigadas] = { nWorkers: 2, workers: [null, null] };
        $scope.auxPoints[$scope.nBrigadas] = {};
        $scope.nBrigadas += 1;
    }
    $scope.addWorker = function(nBrigada) {
        $scope.brigadas[nBrigada].nWorkers += 1;
        $scope.brigadas[nBrigada].workers.push(null);
    };
    $scope.removeWorker = function(nBrigada) {
        if ($scope.brigadas[nBrigada].nWorkers > 1) {
            $scope.brigadas[nBrigada].nWorkers -= 1;
            $scope.brigadas[nBrigada].workers.pop();
        }
    };

    $scope.stepBack = function() {
        $scope.currentStep -= 1;
    };

    $scope.stepForward = function() {
        $scope.currentStep += 1;
        console.log($scope.brigadas);
    };

    $scope.showStep = function(step) {
        return step == $scope.currentStep;
    };

    $('#newModal').on('shown.bs.modal', function() {
        //$http.get('http://' + $location.host() + ':5050/users')
        $http.get(ROUTES_FLASK_API + 'users')
            .then(function(response) {
                $scope.users = response.data;
                $scope.availableUsers = $scope.users;
            })
            .catch(function(response) {
                console.log('ERRO');
                console.log(response);
            });
        //$http.get('http://' + $location.host() + ':5050/vehicles')
       $http.get(ROUTES_FLASK_API + 'vehicles')
            .then(function(response) {
                $scope.vehicles = response.data;
                console.log($scope.vehicles)
            })
            .catch(function(response) {
                console.log('ERRO');
                console.log(response);
            });
    });

    $scope.loadCaes = function($query) {
        //return $http.get('http://' + $location.host() + ':5050/caes', { cache: true }).then(function(response) {
        return $http.get(ROUTES_FLASK_API + 'caes', { cache: true }).then(function(response) {
            var caes = response.data;
            return caes.filter(function(cae) {
                return cae.DESC_CAE.toLowerCase().indexOf($query.toLowerCase()) != -1 || cae.CAE.indexOf($query) != -1; // procura por nome do código de atividade ou código em si
            });
        });
    };


    $scope.loadActividades = function($query) {
        //return $http.get('http://' + $location.host() + ':5050/actividades', { cache: true }).then(function(response) {
        return $http.get(ROUTES_FLASK_API + 'actividades', { cache: true }).then(function(response) {
            var actividades = response.data;
            return actividades.filter(function(a) {
                return a.DESIGNACAO.toLowerCase().indexOf($query.toLowerCase()) != -1 || a.CODIGO.toLowerCase().indexOf($query.toLowerCase()) != -1;
            });
        });
    };

    $scope.getEntidades = function() {
        //$http.get('http://' + $location.host() + ':5050/entidades', {
        $http.get(ROUTES_FLASK_API + 'entidades', {
                params: {
                    caes: $scope.selectedCaes.map(c => c.CAE),
                    codigos_actividade: $scope.selectedActividades.map(a => a.CODIGO)
                }
            })
            .then(function(response) {
                $scope.newEntidades = response.data;
                console.log($scope.newEntidades)
            })
            .catch(function(response) {
                console.log('ERRO');
                console.log(response);
            });
    }

    $('#newModal').on('hidden.bs.modal', function() {
        $scope.currentStep = 1;
    });

    $scope.calculate = function(algorithm) {
        // DEBUG
        console.log('ALGORITHM')
        console.log(algorithm)
        console.log('CAES')
        console.log($scope.selectedCaes)
        console.log('OBJECTIVE F')
        console.log($scope.objectiveF)
            // DEBUG (END)

        inputs = {
            'algorithm': algorithm,
            'caes': $scope.selectedCaes.map(c => c.CAE),
            'actividades': $scope.selectedActividades.map(a => a.CODIGO),
            'objective_f': $scope.objectiveF,
            'brigadas': $scope.brigadas,
            'date': $scope.newDate
        }
        if ($scope.hasBreak) {
            inputs.break = $scope.break;
        }
        AuthService.setRoute(JSON.stringify(inputs));
        $window.location.href = '/fiscalizacoes/nova_fiscalizacoes.php'
    }

});

function estadoh(estado_horario) {
    var estado_h_class = 'secondary';
    var estado_h_name = 'Não definido'
    if (estado_horario == 'GPLACES') {
        estado_h_class = 'info';
        estado_h_name = '<i class="fab fa-google"></i> Google'
    } else if (estado_horario == 'YELP') {
        estado_h_class = 'info';
        estado_h_name = '<i class="fab fa-yelp"></i> Yelp'
    } else if (estado_horario == 'NOT_IN_GP' || estado_horario == 'NOT_IN_YELP' || estado_horario == 'NOT_IN') {
        estado_h_class = 'danger';
        estado_h_name = 'Sem registo'
    }
    return { class: estado_h_class, name: estado_h_name }
}

// converte horarios representados em arrays binárias em horários representados em arrays com horários de abertura e fecho em Date()
function binary2events(inspectionStart, horario, id) {
    var date = new Date(inspectionStart);
    date.setHours(0, 0, 0, 0);
    var events = [];
    var previousH = 0;
    var start, end;
    horario.forEach(function(h, index) {
        if (h == 1 && previousH == 0) {
            start = new Date(date.getTime() + 1800000 * index)
        } else if (h == 0 && previousH == 1) {
            end = new Date(date.getTime() + 1800000 * index)
            events.push({ location: id, start: start, end: end, className: 'oh-open' })
        }
        previousH = h;
    })
    if (horario[horario.length - 1] == 1) {
        end = new Date(date.getTime() + 1800000 * horario.length)
        events.push({ location: id, start: start, end: end, className: 'oh-open' })
    }
    return events;
}

app.filter('range', function() {
    return function(input, total) {
        total = parseInt(total);

        for (var i = 0; i < total; i++) {
            input.push(i);
        }

        return input;
    };
});

app.filter('toHHMMSS', function() {
    return function(x) {
        var sec_num = parseInt(x, 10); // don't forget the second param
        var hours = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
        var seconds = sec_num - (hours * 3600) - (minutes * 60);

        if (hours < 10) {
            hours = "0" + hours;
        }
        if (minutes < 10) {
            minutes = "0" + minutes;
        }
        if (seconds < 10) {
            seconds = "0" + seconds;
        }
        return hours + ':' + minutes + ':' + seconds;
    };
});

app.filter('toHHMM', function() {
    return function(x) {
        var sec_num = parseInt(x, 10); // don't forget the second param
        var hours = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num - (hours * 3600)) / 60);

        if (hours < 10) {
            hours = "0" + hours;
        }
        if (minutes < 10) {
            minutes = "0" + minutes;
        }
        return hours + ':' + minutes;
    };
});
app.filter('timestampToString', function() {
    return function(unixTimestamp) {
        return new Date(unixTimestamp).toLocaleTimeString('pt-PT', { hour: '2-digit', minute: '2-digit' });
    };
});

app.filter('toKM', function() {
    return function(x) {
        return Math.round(x / 100) / 10
    };
});

app.directive('myEnter', function() {
    return function(scope, element, attrs) {
        element.bind("keydown keypress", function(event) {
            if (event.which === 13) {
                scope.$apply(function() {
                    scope.$eval(attrs.myEnter);
                });

                event.preventDefault();
            }
        });
    };
});