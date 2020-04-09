<script>
  $('head').append('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ng-tags-input/3.2.0/ng-tags-input.min.css" integrity="sha256-mHtuFesOf0HEqsoUntci7r0gMqzZaWAm6opnkZxa170=" crossorigin="anonymous" />');
</script>
<script src="http://mbenford.github.io/ngTagsInput/js/ng-tags-input.min.js"></script>

<div ng-controller="newModalCtrl">
  <div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="newModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newModalLabel">Planeamento Fiscalizações</h5>
          <div class='steps'>
            <span class='badge' ng-class="{'badge-primary': currentStep == 1}" ng-click="currentStep = 1">1</span>
            <span class='badge' ng-class="{'badge-primary': currentStep == 2}" ng-click="currentStep = 2">2</span>
            <span class='badge' ng-class="{'badge-primary': currentStep == 3}" ng-click="currentStep = 3">3</span>
            <span class='badge' ng-class="{'badge-primary': currentStep == 4}" ng-click="currentStep = 4">4</span>
            <span class='badge' ng-class="{'badge-primary': currentStep == 5}" ng-click="currentStep = 5">5</span>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div ng-show='showStep(1)'>
            <div class="row text-center">
              <div class="col-12">
                <h5>Data & Hora</h5>
                <br>
                <input id="nova-datepicker">
                <hr>
              </div>
              <div class="col-12">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" ng-model="hasBreak" id="break-switch">
                  <label class="custom-control-label" for="break-switch">
                    <h6>Pausa Refeição</h6>
                  </label>
                </div>
                <div ng-hide="!hasBreak">
                  <p>{{ break.start }} - {{ break.end }}</p>
                  <div id="slider-range"></div>
                </div>
              </div>
            </div>
          </div>


          <div ng-show='showStep(2)'>
            <div class="row text-center">
              <div class="col-12">
                <h5>Função objetivo</h5>
                <br>
              </div>
              <br>
              <div class="col-6">
                <div class="custom-control  custom-radio form-check-inline">
                  <input class="custom-control-input" type="radio" name="objectiveF" id="inlineOF1" value="0" ng-model="objectiveF">
                  <label class="custom-control-label" for="inlineOF1">Max. Utilidade</label>
                </div>
              </div>
              <div class="col-6">
                <div class="custom-control custom-radio form-check-inline">
                  <input class="custom-control-input" type="radio" name="objectiveF" id="inlineOF2" value="1" ng-model="objectiveF">
                  <label class="custom-control-label" for="inlineOF2">Max. A. Económicos</label>
                </div>
              </div>
            </div>
          </div>

          <div ng-show='showStep(3)'>
            <div class="row text-center">
              <div class="col-12">
                <h5>CAEs</h5>
                <br>

                <tags-input ng-model="selectedCaes" display-property="DESC_CAE" template="caes-tags-template" replace-spaces-with-dashes="false" add-from-autocomplete-only="true" key-property="ID">
                  <auto-complete source="loadCaes($query)" min-length="0" load-on-focus="true" load-on-empty="true" max-results-to-show="10" template="caes-autocomplete-template"></auto-complete>
                </tags-input>

                <script type="text/ng-template" id="caes-tags-template">
                  <div class="tag-template">
        <div>
          <span>{{$getDisplayText()}} ({{ data.CAE }})</span>
          <a class="remove-button" ng-click="$removeTag()">&#10006;</a>
        </div>
      </div>
    </script>
                <script type="text/ng-template" id="caes-autocomplete-template">
                  <div class="tag-template">
        <div>
          <span>{{$getDisplayText()}} ({{ data.CAE }})</span>
        </div>
      </div>
    </script>
              </div>
              <div class="col-12">
                <hr />
              </div>
              <div class="col-12">
                <h5>Actividades</h5>
                <br>

                <tags-input ng-model="selectedActividades" display-property="DESIGNACAO" template="actividades-tags-template" replace-spaces-with-dashes="false" add-from-autocomplete-only="true" key-property="ID_ACT">
                  <auto-complete source="loadActividades($query)" min-length="0" load-on-focus="true" load-on-empty="true" max-results-to-show="10" template="actividades-autocomplete-template"></auto-complete>
                </tags-input>

                <script type="text/ng-template" id="actividades-tags-template">
                  <div class="tag-template">
        <div>
          <span>{{$getDisplayText()}} ({{ data.CODIGO }})</span>
          <a class="remove-button" ng-click="$removeTag()">&#10006;</a>
        </div>
      </div>
    </script>
                <script type="text/ng-template" id="actividades-autocomplete-template">
                  <div class="tag-template">
        <div>
          <span>{{$getDisplayText()}} ({{ data.CODIGO }})</span>
        </div>
      </div>
    </script>
              </div>
            </div>
          </div>

          <div ng-show='showStep(4)'>
            <div class="row text-center">
              <div class="col-12">
                <h5>Brigadas</h5>
                <br>
              </div>
              <div class="col-12">
                <div class="row" ng-repeat="n in [] | range:nBrigadas">
                  <div class="col-12 text-left">
                    <h6>Brigada {{ n + 1}}</h6>
                  </div>
                  <div class="col-8">
                    <div class="form-group">
                      <select class="form-control worker-select" ng-model="brigadas[n].vehicle">
                        <option disabled selected value>Viatura</option>
                        <option value="{{ v.ID_VIATURA }}" ng-repeat="v in vehicles" ng-if="v.ESTADO <= 1" ng-class="{'pending-vehicle': v.ESTADO == 1}">{{ v.MATRICULA }} ({{ v.MARCA }} {{ v.MODELO }}) - {{ v.SECTOR }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-2">
                    <div class="form-group">
                      <select class="form-control" ng-model="brigadas[n].max_duration">
                        <option disabled selected value>Duração</option>
                        <option value="3600">1h</option>
                        <option value="7200">2h</option>
                        <option value="10800">3h</option>
                        <option value="14400">4h</option>
                        <option value="18000">5h</option>
                        <option value="21600">6h</option>
                        <option value="25200">7h</option>
                        <option value="28800">8h</option>
                        <option value="32400">9h</option>
                        <option value="36000">10h</option>
                        <option value="39600">11h</option>
                        <option value="43200">12h</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-1 pr-0">
                    <button class="btn btn-outline-success btn-block" ng-click="addWorker(n)">+</button>
                  </div>
                  <div class="col-1 pl-0">
                    <button class="btn btn-outline-danger btn-block" ng-click="removeWorker(n)">-</button>
                  </div>
                  <div class="col-12">
                    <hr style="margin-top: 0px;">
                  </div>
                  <div class="col-4" ng-repeat="m in [] | range:brigadas[n].nWorkers">
                    <div class="form-group">
                      <select class="form-control worker-select" ng-model="brigadas[n].workers[m]">
                        <option disabled selected value>Inspetor {{ m + 1 }}</option>
                        <option value="{{ u.id }}" ng-repeat="u in availableUsers">{{ u.nome }} ({{ u.ID_UO }})</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-12">
                    <hr style="margin-top: 0px;">
                  </div>
                  <div class="col-6">
                    <div class="form-group my-0">
                      <select class="form-control" ng-model="brigadas[n].start_type" ng-change="addGeocodeStart(n)">
                        <option disabled selected value>Partida</option>
                        <option value='uo'>UO</option>
                        <option value='other'>Outro</option>
                      </select>
                    </div>
                    <div ng-if="brigadas[n].start_type=='other'">
                      <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Morada ou local..." aria-label="Morada ou local..." ng-model="auxPoints[n].startInput" my-enter="addressSearch('start', n)">
                        <div class="input-group-append">
                          <button class="btn btn-outline-secondary" type="button" ng-click="addressSearch('start', n)"><i class="fas fa-search"></i></button>
                        </div>
                      </div>
                      <div ng-if="auxPoints[n].startResults.length > 1 && !(auxPoints[n].hasOwnProperty('startResultSelected'))" class="list-group list-group-flush">
                        <a class="list-group-item p-1" ng-repeat="r in auxPoints[n].startResults" data-placement="top" title="{{ r.display_name }}" ng-click="selectResult('start', n, $index)">{{ r.display_name | limitTo:50 }}<span ng-show="r.display_name.length > 45">...</span></a>
                      </div>
                      <leaflet ng-if="auxPoints[n].startResultSelected >= 0" id="newModalMapStart{{n}}" style="height: 30vh" markers="auxPoints[n].startMarker" bounds="auxPoints[n].startBounds" defaults="defaults"></leaflet>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group my-0">
                      <select class="form-control" ng-model="brigadas[n].end_type" ng-change="addGeocodeStart(n)">
                        <option disabled selected value>Chegada</option>
                        <option value='uo'>UO</option>
                        <option value='other'>Outro</option>
                      </select>
                    </div>
                    <div ng-if="brigadas[n].end_type=='other'">
                      <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Morada ou local..." aria-label="Morada ou local..." ng-model="auxPoints[n].endInput" my-enter="addressSearch('end', n)">
                        <div class="input-group-append">
                          <button class="btn btn-outline-secondary" type="button" ng-click="addressSearch('end', n)"><i class="fas fa-search"></i></button>
                        </div>
                      </div>
                      <div ng-if="auxPoints[n].endResults.length > 1 && !(auxPoints[n].hasOwnProperty('endResultSelected'))" class="list-group list-group-flush">
                        <a class="list-group-item p-1" ng-repeat="r in auxPoints[n].endResults" data-placement="top" title="{{ r.display_name }}" ng-click="selectResult('end', n, $index)">{{ r.display_name | limitTo:50 }}<span ng-show="r.display_name.length > 45">...</span></a>
                      </div>
                      <leaflet ng-if="auxPoints[n].endResultSelected >= 0" id="newModalMapEnd{{n}}" style="height: 30vh" markers="auxPoints[n].endMarker" bounds="auxPoints[n].endBounds" defaults="defaults"></leaflet>
                    </div>
                  </div>

                  <div class="col-12">
                    <hr style="border-top-width: 5px;">
                  </div>
                </div>

                <button class="btn btn-outline-primary btn-block mt-4" ng-click="addBrigada()">Adicionar Brigada</button>
              </div>
            </div>
          </div>

          <div ng-show='showStep(5)'>
            <div class="row text-center">
              <div class="col-12">
                <h5>Visão geral</h5>
                <br>
              </div>
              <div class="col-12">
                <p><b>Data de início:</b> {{ newDate }}</p>
                <p ng-hide="hasBreak">Sem Pausa Refeição</p>
                <p ng-hide="!hasBreak"><b>Pausa Refeição:</b> {{ break.start }}-{{ break.end }}</p>
                <p><b>Objetivo:</b> <span ng-show="objectiveF == 0">Max. Utilidade</span><span ng-show="objectiveF == 1">Max. A. Económicos</span></p>
              </div>
              <div class="col-6">
                <p><b>CAES</b></p>
                <p ng-if="selectedCaes.length == 0">Sem CAEs específicos</p>
                <ul ng-if="selectedCaes.length > 0" class="list-group list-group-flush">
                  <li class="list-group-item p-1" ng-repeat="c in selectedCaes">{{ c.DESC_CAE }} ({{ c.CAE }})</li>
                </ul>
              </div>
              <div class="col-6">
                <p><b>Actividades</b></p>
                <p ng-if="selectedActividades.length == 0">Sem actividades específicas</p>
                <ul ng-if="selectedActividades.length > 0" class="list-group list-group-flush">
                  <li class="list-group-item p-1" ng-repeat="a in selectedActividades">{{ a.DESIGNACAO }} ({{ a.CODIGO }})</li>
                </ul>
              </div>
              <div class="col-12">
                <hr />
                <h6>Brigadas</h6>
                <p ng-if="brigadas.length == 0">Sem brigadas alocadas</p>
                <br>
                <div ng-repeat="b in brigadas">
                  <p><b>Viatura:</b> {{ b.vehicle }}</p>
                  <p><b>Duração:</b> {{ b.max_duration | toHHMMSS }}</p>
                  <p><b>Inspetores:</b> {{ b.workers.join() }}</p>
                  <p><b>Partida:</b> {{ b.start }}</p>
                  <p><b>Chegada:</b> {{ b.end }}</p>
                  <br>
                </div>
              </div>
              <div class="col-12">
                <hr />
                <button type="button" class="btn btn-outline-info btn-sm" data-toggle="collapse" data-target="#entidadesCollapse" ng-click="getEntidades()">Agentes económicos</button>
                <br>
                <div class="collapse" id="entidadesCollapse">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Util.</th>
                        <th>CAEs</th>
                        <th>Actividades</th>
                      </tr>
                      <tr>
                        <th colspan="5"><input class="form-control" placeholder="Pesquisa entidade" type="text" ng-model="searchText" /></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr ng-repeat="e in newEntidades | filter:searchText">
                        <td>{{e.id}}</td>
                        <td>{{e.NOME}}</td>
                        <td>{{e.utility}}</td>
                        <td>
                          <p ng-repeat="c in e.CAES.split(',')">{{c}}</p>
                        </td>
                        <td>
                          <p ng-repeat="c in e.CODIGOS_ACTIVIDADE.split(',')">{{c}}</p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>



        </div>
        <div class="modal-footer">
          <button class='btn btn-outline-secondary mr-auto' ng-click='stepBack()' ng-hide='currentStep == 1'><i class="fas fa-chevron-left"></i> Back</button>
          <button class='btn btn-outline-secondary' ng-click='stepForward()' ng-hide='currentStep == 5'>Next <i class="fas fa-chevron-right"></i></button>
          <div class="btn-group" ng-show='currentStep == 5'>
            <button type="button" class="btn btn-secondary" ng-click="calculate('simulatedannealing')">Gerar rota</button>
            <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="sr-only">Algoritmos</span>
            </button>
            <div class="dropdown-menu">
              <button type="button" id="branchbound-btn" class="dropdown-item" ng-click="calculate('branchbound')">B.Bound</button>
              <div class="dropdown-divider"></div>
              <button type="button" id="hillclimbing-btn" class="dropdown-item" ng-click="calculate('hillclimbing')">H.Climbing</button>
              <button type="button" id="s-annealing-btn" class="dropdown-item" ng-click="calculate('simulatedannealing')">S.Annealing</button>
              <div class="dropdown-divider"></div>
              <button type="button" id="genetic-btn" class="dropdown-item" ng-click="calculate('genetic')">Genetic</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>