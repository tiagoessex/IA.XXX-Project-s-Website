<?php include('../header.php'); ?>

<script>
    $('head').append('<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>');
    $('head').append('<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />');

    $('head').append('<link rel="stylesheet" href="../css/overlayspinner.css"/>');
    $('head').append('<link rel="stylesheet" href="../css/map.css"/>');
    $('head').append('<link rel="stylesheet" href="../css/densidades.css"/>');
    $('head').append('<link rel="stylesheet" href="../css/horario.css"/>');
    $('head').append('<link rel="stylesheet" href="../external/skedTape/jquery.skedTape.css"/>');
</script>

<script type="text/javascript" src="<?php echo DOMAIN_URL; ?>external/skedTape/jquery.skedTape.js"></script>

<div class="container-fluid" ng-controller="newCtrl">
    <div ng-class="{'loading' : isLoading}"></div>

    <div id="inspections-row" class="row">

        <div id="inspections-leftbar" class="col-2 px-1 border-right">
            <h5>Nova Fiscalização<br><span id="dateSpan" class="text-muted">{{ inputs.date }}</span></h5>
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link my-1" ng-class="{active: $index==0}" id="v-pills-{{ inputs.brigadas[$index].vehicle }}-tab" data-toggle="pill" href="#v-pills-{{ inputs.brigadas[$index].vehicle }}" role="tab" aria-controls="v-pills-{{ inputs.brigadas[$index].vehicle }}" aria-selected="true" ng-repeat="r in routes" ng-click="bringToFrontRO(r.showRO, r.ro)">
                    <div class="row">
                        <div class="col-9">
                            <p>Brigada {{ inputs.brigadas[$index].vehicle }}</p>
                        </div>
                        <div class="col-3">
                            <div class="custom-control custom-switch" ng-click="$event.stopPropagation();">
                                <input type="checkbox" class="custom-control-input" id="customSwitch{{ inputs.brigadas[$index].vehicle }}" ng-model="r.showRO" ng-change="toggleRO(r.showRO, r.ro)">
                                <label class="custom-control-label" for="customSwitch{{ inputs.brigadas[$index].vehicle }}"></label>
                            </div>
                        </div>
                        <div class="col-12">
                            <p><small><b>{{ r.routes.length }}</b> entidades</small></p>
                            <p><small><i class="fas fa-clock"></i> <b>{{ (r.routes[r.routes.length - 1].partial_duration + r.routes[r.routes.length - 1].from_duration) | toHHMM }}</b></small></p>
                            <p><small><i class="fas fa-car-side"></i> <b>{{ r.ro._selectedRoute.summary.totalDistance | toKM }} kms</b> ({{ r.ro._selectedRoute.summary.totalTime | toHHMM }})</small></p>
                            <p><small><i class="fas fa-route"></i> <span ng-if="inputs.brigadas[$index].start_type=='uo'">UO</span><span ng-if="inputs.brigadas[$index].start_type=='other'" data-placement="top" title="{{ inputs.brigadas[$index].start_point.display_name }}">{{ inputs.brigadas[$index].start_point.display_name | limitTo:12 }} </span> -> <span ng-if="inputs.brigadas[$index].end_type=='uo'">UO</span><span ng-if="inputs.brigadas[$index].end_type=='other'" data-placement="top" title="{{ inputs.brigadas[$index].end_point.display_name }}">{{ inputs.brigadas[$index].end_point.display_name | limitTo:12 }}</span></small></p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- <div ng-if="routes.length > 0"> -->
                <br>
                <div class="custom-control custom-switch" ng-click="$event.stopPropagation();">
                    <input type="checkbox" class="custom-control-input" id="customSwitchOthers" ng-model="showOthers" ng-change="toggleOthers()">
                    <label class="custom-control-label" for="customSwitchOthers">Entidades sem rota</label>
                </div>
                <br>
                <p class="small">Informação da solução:</p>
                <ul class="pl-4">
                    <li class="small">Utilidade total: {{ metrics.utility }}</li>
                    <li class="small">Tempo perdido: {{ metrics.lost_time | toHHMM }}</li>
                </ul>
                <br>
                <div class="form-group">
                    <select class="form-control" ng-model="inputs.algorithm">
                        <option value="branchbound">B.Bound</option>
                        <option value="hillclimbing">H.Climbing</option>
                        <option value="simulatedannealing">S.Annealing</option>
                        <option value="genetic">Genetic</option>
                    </select>
                </div>
                <br>
                <button class="btn btn-sm btn-block btn-primary" ng-click="newRoutes()"><i class="fas fa-sync-alt"></i> Gerar nova rota</button>

                <br>
                <button class="btn btn-sm btn-block btn-primary" data-toggle="modal" data-target="#newModal"><i class="fas fa-edit"></i> Editar parâmetros</button>
            <!-- </div> -->
        </div>
        <div id="inspections-rightbar" class="col-10">
            <div class="row">
                <div class="col-12 p-0">
                    <leaflet id="routeMap" class="mr-3 mt-1 ml-1" style="height: 70vh; box-shadow: 0 0 2px 1px black;" center="center" defaults="defaults" markers="markers"></leaflet>
                </div>
                <div class="col-12 mt-2">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade" ng-class="{'active show': $index==0}" id="v-pills-{{ inputs.brigadas[$index].vehicle }}" role="tabpanel" aria-labelledby="v-pills-{{ inputs.brigadas[$index].vehicle }}-tab" ng-repeat="r in routes">

                            <div class="row">
                                <div class="col-12">
                                    <div id="horario-{{ inputs.brigadas[$index].vehicle }}"></div>
                                    <hr />
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-7 border-right">
                                            <table id="vehicle_table_{{ inputs.brigadas[$index].vehicle }}" class="table table-sm table-striped text-center route-table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Nome</th>
                                                        <th>Utilidade</th>
                                                        <th>Chegada</th>
                                                        <th>Espera</th>
                                                        <th>Duração</th>
                                                        <th>Partida</th>
                                                        <th><i class="fas fa-map-signs"></i></th>
                                                    </tr>
                                                </thead>
                                                <tr>
                                                    <td colspan="3"><span ng-if="inputs.brigadas[$index].start_type=='uo'">{{ ued.unidade.NOME }}</span><span ng-if="inputs.brigadas[$index].start_type=='other'">{{ inputs.brigadas[$index].start_point.display_name | limitTo:50 }}</span></td>
                                                    <td> --- </td>
                                                    <td> --- </td>
                                                    <td> --- </td>
                                                    <td>{{ inspectionStart.getTime() | timestampToString }}</td>
                                                    <td><button class="btn btn-success" type="button" ng-click="r.ro.selectedInstructions=r.ro._selectedRoute.instructions"><i class="fas fa-list-ol"></i></button>
                                                        <a class="btn btn-secondary" href="{{ r.gmapsUrl }}" target="_blank"><i class="fas fa-directions"></i></a></td>
                                                </tr>
                                                <tr ng-repeat="d in r.routes">
                                                    <td>{{ d.id }}</td>
                                                    <td>{{ d.NOME }}</td>
                                                    <td>{{ d.utility }}</td>
                                                    <td>{{ (inspectionStart.getTime() + (d.partial_duration - d.wait_duration - d.visit_duration) * 1000) | timestampToString }}</td>
                                                    <td><span ng-if="d.wait_duration > 0">{{ d.wait_duration | toHHMM }}</span><span ng-if="d.wait_duration == 0"> --- </span></td>
                                                    <td>{{ d.visit_duration | toHHMM }}</td>
                                                    <td>{{ (inspectionStart.getTime() + (d.partial_duration) * 1000) | timestampToString }}</td>
                                                    <td><button class="btn btn-success" type="button" ng-click="r.ro.selectedInstructions=r.ro._selectedRoute.splittedInstructions[$index]"><i class="fas fa-list-ol"></i></button>
                                                        <a class="btn btn-secondary" href="https://www.google.com/maps/dir/{{ r.gmapsUrl.split('/')[$index + 5] }}/{{ r.gmapsUrl.split('/')[$index + 6] }}/?travelmode=driving" target="_blank"><i class="fas fa-directions"></i></a></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"><span ng-if="inputs.brigadas[$index].end_type=='uo'">{{ ued.unidade.NOME }}</span><span ng-if="inputs.brigadas[$index].end_type=='other'">{{ inputs.brigadas[$index].end_point.display_name | limitTo:50 }}</span></td>
                                                    <td>{{ (inspectionStart.getTime() + (r.routes[r.routes.length - 1].partial_duration + r.routes[r.routes.length - 1].from_duration) * 1000) | timestampToString }}</td>
                                                    <td> --- </td>
                                                    <td> --- </td>
                                                    <td> --- </td>
                                                    <td><button class="btn btn-success" type="button" ng-click="r.ro.selectedInstructions = r.ro._selectedRoute.splittedInstructions[r.routes.length]"><i class="fas fa-list-ol"></i></button>
                                                        <a class="btn btn-secondary" href="https://www.google.com/maps/dir/{{ r.gmapsUrl.split('/')[r.routes.length + 5] }}/{{ r.gmapsUrl.split('/')[r.routes.length + 6] }}/?travelmode=driving" target="_blank"><i class="fas fa-directions"></i></a></button></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-5 instructions-table-div">
                                            <table class="table table-sm instructions-table">
                                                <tbody>
                                                    <tr ng-repeat="i in r.ro.selectedInstructions">
                                                        <td><span class="leaflet-routing-icon" ng-class="{'leaflet-routing-icon-depart': i.type=='Head',
											'leaflet-routing-icon-enter-roundabout': i.type=='Roundabout',
											'leaflet-routing-icon-bear-right': i.type=='SlightRight',
											'leaflet-routing-icon-continue': i.type=='Straight',
											'leaflet-routing-icon-turn-right': i.type=='OffRamp' || i.type=='Right' || (i.type=='Fork' && i.modifier=='Right'),
											'leaflet-routing-icon-turn-left': i.type=='Merge' || i.type=='Left' || (i.type=='Fork' && i.modifier=='Left'),
											'leaflet-routing-icon-bear-left': i.type=='SlightLeft',
											'leaflet-routing-icon-via': i.type=='WaypointReached',
											'leaflet-routing-icon-arrive': i.type=='DestinationReached'}"></span></td>
                                                        <td data-placement="top" title="{{ i.text }}">{{ i.text | limitTo:50 }}<span ng-show="i.text.length > 50">...</span></td>
                                                        <td>{{ r.ro._formatter.formatDistance(i.distance) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<script type="text/javascript" src="<?php echo DOMAIN_URL; ?>js/utils.js"></script>
<script type="text/javascript" src="<?php echo DOMAIN_URL; ?>js/fiscalizacoes_utils.js"></script>

<?php include('../footer.php'); ?>