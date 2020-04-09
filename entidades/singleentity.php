<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin="" />
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/infodisplay.css" />

<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>

<div class="container-fluid">
    <div class="col-sm-12">

        <!-- --------------------------------------------------- -->
        <!-- --------------------------------------------------- -->
        <!-- dialog -->
        <!-- --------------------------------------------------- -->
        <!-- --------------------------------------------------- -->
        <div id="searchdialog">
            <br><br><br><br>
            <div class="col-sm-6 offset-sm-3 text-center">
                <h4 class="display-9">
                    Introduza o ID ou o Nome da Entidade:
                </h4>
                <br>
                <div class="info-form">
                    <form action="" class="justify-content-center">
                        <div class="form-group">
                            <label class="sr-only">ID (prioridade)</label>
                            <input type="text" class="form-control" placeholder="ID (prioridade)" id="entity_id">
                        </div>
                        <div class="form-group">
                            <label class="sr-only">Nome</label>
                            <input type="text" class="form-control" placeholder="Nome" id="entity_name" list="entity_name_suggestions">
                            <datalist id="entity_name_suggestions" autoComplete="off">
                                </datalist>
                        </div>
                        <button type="button" class="btn btn-success btn-block" id="searchok">Ok</button>
                    </form>
                </div>
                <br>
            </div>
        </div>

        <!-- --------------------------------------------------- -->
        <!-- --------------------------------------------------- -->
        <!-- tabs -->
        <!-- --------------------------------------------------- -->
        <!-- --------------------------------------------------- -->

        <div id="infotabs" hidden>

            <?php require('_entity_data.php'); ?>

        </div>

    </div>

    <!-- 
    ************************************************
    ************************************************
    MODALS
    ************************************************
    ************************************************
    -->
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
                    Nesta página a localização de uma entidade, pode ser feita mediante dois processos:
                    <ul>
                        <li>
                            O <i>ID</i> da entidade;
                        </li>
                        <li>
                            Nome da entidade
                        </li>
                    </ul>

                    <p>
                        Caso os dois campos sejam preenchidos, apenas o <i>ID</i> será considerado. Portanto, para utilizar a procura pelo <i>nome</i>, deixe o campo <i>ID</i> vazio.
                    </p>
                    <p>
                        À medida que o nome da entidade é introduzido, o sistema irá indo fornecendo (a cada 0.5s) uma lista de pelo menos 10 entidades existentes na base de dados com os mesmos caracteres introduzidos até ao momento.
                    </p>
                    <p>
                      <!--
                        Após a seleção de uma entidade, toda a informação mais relevante sobre esta pode ser consultada, desde as <i>denúncias</i>, as <i>fiscalizações</i> e <i>processos</i> até à sua <i>localização geográfica</i>.
                      -->
                      Após a seleção de uma entidade, toda a informação disponível mais relevante sobre esta pode ser consultada, desde as <i>denúncias</i>, as <i>reclamações</i> e até à sua <i>localização geográfica</i>.
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


<script>
    // ************************************************
    // GLOBAL VARS AND CONST
    // ************************************************
    const N_SUGGESTIONS = 10; // entity's name max suggestions
    var mymap = null;
    var marker = null;
    var char_counter = 3; // every 3 chars get suggestions for #entity_name
    var g_id_denuncia = null; // message id to be analyzed (if required)


    var timeoutID = null;
    var updatesuggest = false;


  // ************************************************
  // GENERAL STUFF
  // ************************************************

    // check entity existence and get the primary entity data
    function getData() {
        if ($('#entity_id').val() == '' &&
          $('#entity_name').val() == '') return;
        getEntityGeral();
    }

    // get the rest of the data - denuncias, ...
    // this function is called only if an entity exist
    function getData2() {
        $("#searchdialog").hide();
        $('#infotabs').removeAttr('hidden');
        getEntityGeo();
        getEntityDenuncias();
        getEntityReclamacoes();
        //getEntityFiscalizacoes(); 
        //getEntityProcessos();
        
    }


  // 'OK' button
	$("#searchok").click(function() {
		getData();
	});


  // if focus on ID field, then if 'enter' then fetch data
   $('#entity_id').on('keypress', function (e) {
         if(e.which === 13){
            $(this).attr("disabled", "disabled"); 
            getData();
            $(this).removeAttr("disabled");
         }
   });


  // ************************************************
  // FETCH BY NAME AND LIST OF SUGESTIONS
  // ************************************************

  $("#entity_name").focusout(function(){
      if (timeoutID) {
          clearTimeout(timeoutID);
          timeoutID = null;
      }
  });
 
  // if 'enter'(13) => get data
  // if no arrows => update sugestions
  $('#entity_name').on('keyup', function (e) {
      if(e.which === 13){
          $(this).attr("disabled", "disabled");
          getData();
          $(this).removeAttr("disabled");
      } else if(e.which < 37 || e.which > 40) {
          updatesuggest = true;
          if (!timeoutID) {
              timeoutID = setTimeout(getSugest, 500);
          }
      }
  });

  function getSugest() {
      //console.log("get sugestion");
      clearTimeout(timeoutID);
      timeoutID = null;
      if (!updatesuggest) {
        if ($('#entity_name').val() == '') {
          updatesuggest = false;
        }
        return;
      } 
      getSuggestions();
      updatesuggest = false;
  }




  // get list of sugested entities' name
  function getSuggestions() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/listentidades.php",  
                    method:"POST",
                    data:{name_so_far: $('#entity_name').val().toUpperCase(), number_of_entities: N_SUGGESTIONS},
                    cache: false,
                    dataType:"json", 
                   success:function(response) {
                        populateSuggestions(response);
                   },
                   error: function( jqXHR, status ) {
                        //console.log("error code > " + jqXHR.status);
                        //console.log("error > " + jqXHR.responseText);
                        var s = '<b>Problemas com base de dados</b></br>';
                        s += jqXHR.responseText;
                        $('.modal-body-modalDBError').html(s);
                        $('#modalDBError').modal('show');                    
                    }
        });
    };



  // ************************************************
  // FETCH ENTITY DATA
  // ************************************************

    function getEntityGeral() {
        $.ajax({  
                  url:"<?php echo DOMAIN_URL; ?>entidades/srv/entitygeral.php",  
                  method:"POST",
                  data:{id_entidade: $('#entity_id').val(),
                  nome_entidade: $('#entity_name').val()},
                  cache: false,
                  dataType:"json", 
                  contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                  success:function(response) {
                        populateGen(response);
                        // OK, the entity exist, so get the rest of the data
                        getData2();
                  },
                  error: function( jqXHR, status ) {
                      var s = '';
                      var error_response = JSON.parse(jqXHR.responseText);                  
                      if (jqXHR.status == 500) {    
                          s = '<h4>Problemas com base de dados</h4></br>';        
                          s += '<b>Possivel solução:</b> <i>Verifique as queries!</i></br></br>';
                          s += '<b>ERRO: </b></br>';
                          s += '<mark>' + jqXHR.responseText + '</mark>';
                      } else if (jqXHR.status == 200) { 
                          s = '<h4>Problemas com base de dados</h4></br>';
                          s += '<b>Possivel solução:</b> <i>Verifique as credenciais de acesso</i></br></br>';
                          s += '<b>ERRO: </b></br>';
                          s += '<mark>' + jqXHR.responseText + '</mark>';
                      } else if (jqXHR.status == 0) {
                          s = '<h4>Timeout</h4></br>';
                          s += '<b>Possivel solução:</b> <i>Aumente a performance das queries!</i></br></br>';
                      }
                      if (error_response['code'] == 1337) {
                                s = "<h4>Entidade especificada não existe!</h4>";
                      } else {
                                s += '<b>Problemas com base de dados</b></br>';
                                s += jqXHR.responseText;
                      }
                      $('.modal-body-modalDBError').html(s);
                      $('#modalDBError').modal('show');                    
                  }
        });
    };

    function getEntityGeo() {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/getgeocod.php",  
                    method:"POST",
                    data:{id_entidade: $('#entity_id').val()},
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

    function getEntityDenuncias() {
       $('#nav-denuncias').text('');
       $('#nav-denuncias').append('AGUARDE UM MOMENTO ...');
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/entitydenuncias.php",  
                    method:"POST",
                    data:{id_entidade: $('#entity_id').val()},
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

    function getEntityReclamacoes() {
       $('#nav-reclamacoes').text('');
       $('#nav-reclamacoes').append('AGUARDE UM MOMENTO ...');
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/entityreclamacoes.php",  
                    method:"POST",
                    data:{id_entidade: $('#entity_id').val()},
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

     function getEntityFiscalizacoes() {
       $('#nav-fiscalizacacoes').text('');
       $('#nav-fiscalizacacoes').append('AGUARDE UM MOMENTO ...');
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/entityfiscalizacoes.php",  
                    method:"POST",
                    data:{id_entidade: $('#entity_id').val()},
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

     function getEntityProcessos() {
       $('#nav-processos').text('');
       $('#nav-processos').append('AGUARDE UM MOMENTO ...');
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/entityprocessos.php",  
                    method:"POST",
                    data:{id_entidade: $('#entity_id').val()},
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





    function populateSuggestions(response) {
      var dataList = $("#entity_name_suggestions");
      dataList.empty();

        for(var i=0;i<response.length;i++) {
            dataList.append('<option value="' + response[i]['nome'].toUpperCase() + '">');
        }
    }


    function populateGeo(data) {
        $('#nav-geo').text('');

        // ******************************************
        // NAV-GEO
        // ******************************************
        //$('#nav-geo').text('');
        str = "<div class='text-center'><b>LATITUDE: </b>" + data['latitude'];
        str += "<b style='padding-left: 50px;'>LONGITUDE: </b>" + data['longitude'] + '</div>';

        str += '<div id="mapid" style="width:100%; height:400px;"></div>';


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

        mymap = L.map('mapid').setView([39.5, -8], 13);

        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 21,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
                '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox.streets'
        }).addTo(mymap);  
       marker = L.marker([data['latitude'], data['longitude']]);
       mymap.setView(new L.LatLng(data['latitude'], data['longitude']), 19);
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