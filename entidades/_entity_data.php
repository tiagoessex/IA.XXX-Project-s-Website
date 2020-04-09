<!--

    Displays a tab system with entity's data: 
        - general information
        - denuncias
        - reclamacoes
        - fiscalizacoes
        - processos
        - geographic data

    INCLUDED IN:
        /entidades/singleentidade.php
        /entidades/activityentity.php
        /entidades/multipleentities.php
        /entidades/entitiesradius.php
        /entidades/novaentidade.php
        /denuncias/denunciasclassificadas.php
        /denuncias/denunciasanalisarclass.php
        /dashboards/geraldashboard.php
        /dashboards/denunciasdashboard.php
        /dashboards/entidadesdashboard.php
        /dashboards/fiscalizacoesdashboard.php
-->
<nav>
    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-geral-tab" data-toggle="tab" href="#nav-geral" role="tab" aria-controls="nav-geral" aria-selected="true">Geral</a>
        <a class="nav-item nav-link" id="nav-denuncias-tab" data-toggle="tab" href="#nav-denuncias" role="tab" aria-controls="nav-denuncias" aria-selected="false">Denúncias</a>
        <a class="nav-item nav-link" id="nav-reclamacoes-tab" data-toggle="tab" href="#nav-reclamacoes" role="tab" aria-controls="nav-reclamacoes" aria-selected="false">Reclamações</a>
        <a class="nav-item nav-link" id="nav-fiscalizacacoes-tab" data-toggle="tab" href="#nav-fiscalizacacoes" role="tab" aria-controls="nav-fiscalizacacoes" aria-selected="false">Fiscalizacoes</a>
        <a class="nav-item nav-link" id="nav-processos-tab" data-toggle="tab" href="#nav-processos" role="tab" aria-controls="nav-processos" aria-selected="false">Processos</a>

        <a class="nav-item nav-link" id="nav-geo-tab" data-toggle="tab" href="#nav-geo" role="tab" aria-controls="nav-geo" aria-selected="false" onclick="updateMap();">Geo stuff</a>
    </div>
</nav>
<div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-geral" role="tabpanel" aria-labelledby="nav-geral-tab">

        <b>NOME: </b><span id="geral-nome"></span>
        <br><b>ID: </b><span id="geral-id"></span>
        <br><b>D/C/F: </b><span id="geral-nuts"></span>
        <br><b>MORADA: </b><span id="geral-morada"></span>
        <br><b>CP: </b><span id="geral-cp"></span>
        <br><b>LOCALIDADE: </b><span id="geral-localidade"></span>
        <br><b>TELEFONE: </b><span id="geral-telefone"></span>
        <br><b>ACTIVA: </b><span id="geral-activa"></span>
        <br><b>NIF/NIPC: </b><span id="geral-nif"></span>
        <br><b>NATUREZA JURIDICA: </b><span id="geral-natureza-juridica"></span>
        <br><b>TIPO ENTIDADE: </b><span id="geral-tipo-entidade"></span>
        <br><b>TIPO: </b><span id="geral-tipo"></span>
        <br><b>TIPO ACTIVIDADE: </b><span id="geral-tipo-actividade"></span>
        <br><b>ACTIVIDADES: </b><br><span id="geral-actividades"></span>


    </div>
    <div class="tab-pane fade" id="nav-denuncias" role="tabpanel" aria-labelledby="nav-denuncias-tab">
        NOT CONNECTED
    </div>
    <div class="tab-pane fade" id="nav-reclamacoes" role="tabpanel" aria-labelledby="nav-reclamacoes-tab">
        NOT CONNECTED
    </div>
    <div class="tab-pane fade" id="nav-fiscalizacacoes" role="tabpanel" aria-labelledby="nav-fiscalizacacoes-tab">
        <br>
        <h3 class="text-center">
            <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;"> SEM AUTORIZAÇÃO
            <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;">
        </h3>
    </div>
    <div class="tab-pane fade" id="nav-processos" role="tabpanel" aria-labelledby="nav-processos-tab">
        <br>
        <h3 class="text-center">
            <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;"> SEM AUTORIZAÇÃO
            <img src="../images/forbidden.png" alt="forbidden" style="width:32px;height:32px;">
        </h3>
    </div>

    <div class="tab-pane fade" id="nav-geo" role="tabpanel" aria-labelledby="nav-geo-tab">
        NOT CONNECTED<br>
    </div>
</div>

<script type="text/javascript">

    // function called by getEntityGeral() in file X.php
    function populateGen(data) {
        $('#med_title').text(data['nome']);

        // if entity was chosen by name then
        // set the entity's id in the respective form field
        if ($('#entity_id').val() == "")
          $('#entity_id').val(data['id']);

        // ******************************************
        // NAV-GERAL
        // ******************************************

        $("#geral-nome").html(data['nome']);
        $("#geral-id").html(data['id']);
        $("#geral-nuts").html(data['path']?data['path']:"-");
        $("#geral-morada").html(data['morada']?data['morada']:"-");
        $("#geral-cp").html(data['codigo_postal']?data['codigo_postal']:"-");
        $("#geral-localidade").html(data['localidade_cp']?data['localidade_cp']:"-");
        $("#geral-telefone").html(data['telefone']?data['telefone']:"-");
        $("#geral-activa").html(data['activa']?data['activa']:"-");
        $("#geral-nif").html(data['nif_nipc']?data['nif_nipc']:"-");
        $("#geral-natureza-juridica").html(data['natureza_juridica']?data['natureza_juridica']:"-");
        $("#geral-tipo-entidade").html(data['tipo_entidade']?data['tipo_entidade']:"-");
        var str;
        if (data['is_pai'] == 'F') {
             str = "Estabelecimento";
        } else if (data['is_pai'] == 'P') {
             str = "Sede";
        } else {
             str = "-";
        }
        $("#geral-tipo").html(str);
        $("#geral-tipo-actividade").html(data['tipo_actividade']?data['tipo_actividade']:"-");
        str = '';
        var infras = (data['actividade'].replace('+ ','')).split(" + ");
        for(k=0;k<infras.length;k++) {
            str += "<span style='padding-left:60px;'>" + infras[k] + "</span><br>";
        }
        $("#geral-actividades").html(str);

        // necessary otherwise on opening a new modal the active tab will be
        // the one active during the last consult
        $('a[href="#nav-geral"]').click();
    }


    // function called by getEntityProcessos() in file X.php
    function populateProcessos(data) {
        $('#nav-processos').text('');

        if (data.length == 0) {
            var str = '<br><h3 class="text-center">NÃO EXISTEM PROCESSOS</h3><br>'
            $('#nav-processos').append(str);
            return;
        }

        var str = '<div id="accordion_p">';
        for(var i=0;i<data.length;i++) {
            str += `
                <div class="card">
                        <div class="card-header">
                            <a class="card-link"  onClick='stop();' data-toggle="collapse" href="#collapse_p` + i + '">'
            str += '[' + data[i]['ID_PROCESSO'] +']' + ' ' + data[i]['NUP'];
            str += `
                            </a>
                        </div>
                        <div id="collapse_p` + i + `" class="collapse" data-parent="#accordion_p">
                            <div class="card-body">`;


            str += "<b>ID PROCESSO: </b>" + (data[i]['ID_PROCESSO']?data[i]['ID_PROCESSO']:"-");
            str += "<br><b>PAPEL NO PROCESSO: </b>" + (data[i]['PAPEL_NO_PROCESSO']?data[i]['PAPEL_NO_PROCESSO']:"-");
            str += "<br><b>NUP: </b>" + (data[i]['NUP']?data[i]['NUP']:"-");
            str += "<br><b>DESCRIÇÃO: </b>" + (data[i]['DESCRICAO']?data[i]['DESCRICAO']:"-");
            str += "<br><b>ESTADO: </b>" + (data[i]['ESTADO']?data[i]['ESTADO']:"-");
            str += "<br><b>TIPO DE PROCESSO: </b>" + (data[i]['TIPO_PROCESSO']?data[i]['TIPO_PROCESSO']:"-");
            str += "<br><b>INFRACCAO: </b>" + (data[i]['INFRACCAO']?data[i]['INFRACCAO']:"-");   
            str += "<br><b>DATA DA SITUAÇÃO: </b>" + (data[i]['DT_SITUACAO']?data[i]['DT_SITUACAO']:"-");
            str += "<br><b>DATA DO ENVIO: </b>" + (data[i]['DT_ENVIO']?data[i]['DT_ENVIO']:"-");
            str += "<br><b>DATA DE INICIO: </b>" + (data[i]['DT_INICIO']?data[i]['DT_INICIO']:"-");
            str += "<br><b>DATA DE FIM: </b>" + (data[i]['DT_FIM']?data[i]['DT_FIM']:"-");

            str += `
            </div>
            </div>
            </div>
            `;

        }
        str += '</div>';
        $('#nav-processos').append(str);

    }


    // function called by getEntityFiscalizacoes() in file X.php
    function populateFiscalizacoes(data) {
        $('#nav-fiscalizacacoes').text('');

        if (data.length == 0) {
            var str = '<br><h3 class="text-center">NÃO EXISTEM FISCALIZACOES</h3><br>'
            $('#nav-fiscalizacacoes').append(str);
            return;
        }

        var str = '<div id="accordion_f">';
        for(var i=0;i<data.length;i++) {
            str += `
                <div class="card">
                        <div class="card-header">
                            <a class="card-link" onClick='stop();' data-toggle="collapse" href="#collapse_f` + i + '">'
            str += '[' + data[i]['ID_FISCALIZACAO'] +']' + ' ' + data[i]['NUF'];
            str += `
                            </a>
                        </div>
                        <div id="collapse_f` + i + `" class="collapse" data-parent="#accordion_f">
                            <div class="card-body">`;
           

            str += "<b>ID FISCALIZACAO: </b>" + (data[i]['ID_FISCALIZACAO']?data[i]['ID_FISCALIZACAO']:"-");
            str += "<br><b>NUF: </b>" + (data[i]['NUF']?data[i]['NUF']:"-");
            str += "<br><b>ID DO ALVO: </b>" + (data[i]['ID_ALVO']?data[i]['ID_ALVO']:"-");
            str += "<br><b>TIPO DE ALVO: </b>" + (data[i]['TIPO_ALVO']?data[i]['TIPO_ALVO']:"-");
            str += "<br><b>TIPO DE FISCALIZACAO: </b>" + (data[i]['TIPO_FISCALIZACAO']?data[i]['TIPO_FISCALIZACAO']:"-");
            str += "<br><b>AREA OPERACIONAL: </b>" + (data[i]['AREA_OPERACIONAL']?data[i]['AREA_OPERACIONAL']:"-");            
            str += "<br><b>MOTIVO DA FISCALIZAÇÃO: </b>" + (data[i]['MOTIVO_FISC']?data[i]['MOTIVO_FISC']:"-");
            str += "<br><b>OBSERVACOES: </b>" + (data[i]['OBSERVACOES']?data[i]['OBSERVACOES']:"-");
            str += "<br><b>DATA DE ENTRADA DA QUEIXA: </b>" + (data[i]['DT_ENTRADA_QUEIXA']?data[i]['DT_ENTRADA_QUEIXA']:"-");
            str += "<br><b>DATA AVERIGUACAO: </b>" + (data[i]['DT_AVERIGUACAO']?data[i]['DT_AVERIGUACAO']:"-");
            str += "<br><b>ESTADO: </b>" + (data[i]['ESTADO']?data[i]['ESTADO']:"-");
            str += "<br><b>ID BRIGADA: </b>" + (data[i]['ID_BRIGADA']?data[i]['ID_BRIGADA']:"-");
            str += "<br><b>NUMERO DA BRIGADA: </b>" + (data[i]['NUMERO_BRIGADA']?data[i]['NUMERO_BRIGADA']:"-");

            

            str += `
            </div>
            </div>
            </div>
            `;
        }
        str += '</div>';
        $('#nav-fiscalizacacoes').append(str);

    }



    // function called by getEntityDenuncias() in file X.php
    function populateDenuncias(data) {
        $('#nav-denuncias').text('');

        if (data.length == 0) {
            var str = '<br><h3 class="text-center">NÃO EXISTEM DENUNCIAS</h3><br>'
            $('#nav-denuncias').append(str);
            return;
        }

        var str = '<div id="accordion">';
        for(var i=0;i<data.length;i++) {
            str += `
                <div class="card">
                        <div class="card-header">
                            <a class="card-link" onClick='stop();' data-toggle="collapse" href="#collapse` + i + '">'
  
            str += '[' + data[i]['ID_DENUNCIA'] +']' + ' ' + data[i]['NID'];
            
            str += `
                            </a>
                        </div>
                        <div id="collapse` + i + `" class="collapse" data-parent="#accordion">
                            <div class="card-body">`;

            str += "<b>ID DENUNCIA: </b>" + (data[i]['ID_DENUNCIA']?data[i]['ID_DENUNCIA']:"-");
            str += "<br><b>NID: </b>" + (data[i]['NID']?data[i]['NID']:"-");
            str += "<br><b>COMPETENCIA: </b>" + (data[i]['COMPETENCIA']?data[i]['COMPETENCIA']:"-");
            str += "<br><b>ESTADO: </b>" + (data[i]['ESTADO']?data[i]['ESTADO']:"-");
            str += "<br><b>ESTADO DE AVERIGUACAO: </b>" + (data[i]['ESTADO_DE_AVERIGUACAO']?data[i]['ESTADO_DE_AVERIGUACAO']:"-");
            str += "<br><b>DATA DE ENVIO: </b>" + (data[i]['DATA_DE_ENVIO']?data[i]['DATA_DE_ENVIO']:"-");
            str += "<br><b>TIPO: </b>" + (data[i]['TIPO']?data[i]['TIPO']:"-");
             str += "<br><b>REMETIDA POR DENUNCIANTE?: </b>" + (data[i]['REMETIDA_POR_DENUNCIANTE']?data[i]['REMETIDA_POR_DENUNCIANTE']:"-");
            str += "<br><b>DENUNCIANTE: </b>" + (data[i]['DENUNCIANTE']?data[i]['DENUNCIANTE']:"-");
            str += "<br><b>TIPO DE DENUNCIANTE: </b>" + (data[i]['TIPO_DENUNCIANTE']?data[i]['TIPO_DENUNCIANTE']:"-");            
            str += "<br><b>CONTEUDO: </b>" + (data[i]['CONTEUDO']?data[i]['CONTEUDO']:"-");
            str += "<br><b>ACTIVIDADE: </b>" + (data[i]['ACTIVIDADE']?data[i]['ACTIVIDADE']:"-");


          str += "<br><b>CLASSIFICACAO CONTEUDO: </b><br>";
            if (data[i]['CLASSIFICACAO_CONTEUDO']) {
              var infras = (data[i]['CLASSIFICACAO_CONTEUDO'].replace('+ ','')).split(" + ");
              for(k=0;k<infras.length;k++) {
                  str += "<span style='padding-left:60px;'>" + infras[k] + "</span><br>";
              }
            }

            str += "<br><b>INFRACCOES RELACIONADAS: </b><br>";
            if (data[i]['INFRACCOES']) {
              var infras = (data[i]['INFRACCOES'].replace('+ ','')).split(" + ");
              for(k=0;k<infras.length;k++) {
                  str += "<span style='padding-left:60px;'>" + infras[k] + "</span><br>";
              }
            }

            str += "<br><b>FISCALIZACOES RELACIONADAS: </b><br>";
            if (data[i]['FISCALIZACOES_RELACIONADAS']) {
              var infras = (data[i]['FISCALIZACOES_RELACIONADAS'].replace(' + ','')).split("+ ");
              for(k=0;k<infras.length;k++) {
                  str += "<span style='padding-left:60px;'>" + infras[k] + "</span><br>";
              }
            }

            str += "<br><b>PROCESSOS RELACIONADOS: </b><br>";
            if (data[i]['PROCESSOS_RELACIONADOS']) {
              var infras = (data[i]['PROCESSOS_RELACIONADOS'].replace(' + ','')).split("+ ");
              for(k=0;k<infras.length;k++) {
                  str += "<span style='padding-left:60px;'>" + infras[k] + "</span><br>";
              }
            }

            if (data[i]['HAS_MESSAGE']) {
                str += "<b><br><a class='bg-primary text-white' href='javascript:void(0)' onclick='getMessage(" + data[i]['ID_DENUNCIA'] + ",\"D\");'>[Ver mensagem]</a></b>";
            } else {
                str += "<b><br><a class='bg-secondary text-white' href='javascript:void(0)'>[Não existe mensagem]</a></b>";
            }

            str += `
            </div>
            </div>
            </div>
            `;


        }
        str += '</div>';
        $('#nav-denuncias').append(str);

    }

    // function called by getEntityReclamacoes() in file X.php
    function populateReclamacoes(data) {
        $('#nav-reclamacoes').text('');

        if (data.length == 0) {
            var str = '<br><h3 class="text-center">NÃO EXISTEM RECLAMAÇÕES</h3><br>'
            $('#nav-reclamacoes').append(str);
            return;
        }

        var str = '<div id="accordion_r">';
        for(var i=0;i<data.length;i++) {
            str += `
                <div class="card">
                        <div class="card-header">
                            <a class="card-link" onClick='stop();' data-toggle="collapse" href="#collapse_r` + i + '">'

            str += '[' + data[i]['ID_RECLAMACAO'] +']' + ' ' + data[i]['NID'];

            
            str += `
                            </a>
                        </div>
                        <div id="collapse_r` + i + `" class="collapse" data-parent="#accordion_r">
                            <div class="card-body">`;
           
            str += "<b>ID_RECLAMACAO: </b>" + (data[i]['ID_RECLAMACAO']?data[i]['ID_RECLAMACAO']:"-");
            str += "<br><b>NID: </b>" + (data[i]['NID']?data[i]['NID']:"-");
            str += "<br><b>COMPETENCIA: </b>" + (data[i]['COMPETENCIA']?data[i]['COMPETENCIA']:"-");
            str += "<br><b>ESTADO: </b>" + (data[i]['ESTADO']?data[i]['ESTADO']:"-");
            str += "<br><b>ESTADO DE AVERIGUACAO: </b>" + (data[i]['ESTADO_DE_AVERIGUACAO']?data[i]['ESTADO_DE_AVERIGUACAO']:"-");           
            str += "<br><b>DATA DE ENVIO: </b>" + (data[i]['DATA_DE_ENVIO']?data[i]['DATA_DE_ENVIO']:"-");
            str += "<br><b>TIPO: </b>" + (data[i]['TIPO']?data[i]['TIPO']:"-");
             str += "<br><b>REMETIDA POR DENUNCIANTE?: </b>" + (data[i]['REMETIDA_POR_DENUNCIANTE']?data[i]['REMETIDA_POR_DENUNCIANTE']:"-");
            str += "<br><b>DENUNCIANTE: </b>" + (data[i]['DENUNCIANTE']?data[i]['DENUNCIANTE']:"-");
            str += "<br><b>TIPO DE DENUNCIANTE: </b>" + (data[i]['TIPO_DENUNCIANTE']?data[i]['TIPO_DENUNCIANTE']:"-");            
            str += "<br><b>CONTEUDO: </b>" + (data[i]['CONTEUDO']?data[i]['CONTEUDO']:"-");
            str += "<br><b>ACTIVIDADE: </b>" + (data[i]['ACTIVIDADE']?data[i]['ACTIVIDADE']:"-");

          str += "<br><b>CLASSIFICACAO CONTEUDO: </b><br>";
            if (data[i]['CLASSIFICACAO_CONTEUDO']) {
              var infras = (data[i]['CLASSIFICACAO_CONTEUDO'].replace('+ ','')).split(" + ");
              for(k=0;k<infras.length;k++) {
                  str += "<span style='padding-left:60px;'>" + infras[k] + "</span><br>";
              }
            }
            
            str += "<br><b>INFRACCOES RELACIONADAS: </b><br>";
            if (data[i]['INFRACCOES']) {
              var infras = (data[i]['INFRACCOES'].replace('+ ','')).split(" + ");
              for(k=0;k<infras.length;k++) {
                  str += "<span style='padding-left:60px;'>" + infras[k] + "</span><br>";
              }
            }

            str += "<br><b>FISCALIZACOES RELACIONADAS: </b><br>";
            if (data[i]['FISCALIZACOES_RELACIONADAS']) {
              var infras = (data[i]['FISCALIZACOES_RELACIONADAS'].replace(' + ','')).split("+ ");
              for(k=0;k<infras.length;k++) {
                  str += "<span style='padding-left:60px;'>" + infras[k] + "</span><br>";
              }
            }

            str += "<br><b>PROCESSOS RELACIONADOS: </b><br>";
            if (data[i]['PROCESSOS_RELACIONADOS']) {
              var infras = (data[i]['PROCESSOS_RELACIONADOS'].replace(' + ','')).split("+ ");
              for(k=0;k<infras.length;k++) {
                  str += "<span style='padding-left:60px;'>" + infras[k] + "</span><br>";
              }
            }

            //str += "<br><b>INFRACCOES: </b>" + data[i]['INFRACCOES'];
           // str += "<b><br><a href='javascript:void(0)' onclick='getMessage(" + data[i]['ID_RECLAMACAO'] +",\"R\");'>[Ver mensagem]</a></b>";
            
            if (data[i]['HAS_MESSAGE']) {
                str += "<b><br><a class='bg-primary text-white' href='javascript:void(0)' onclick='getMessage(" + data[i]['ID_RECLAMACAO'] + ",\"R\");'>[Ver mensagem]</a></b>";
            } else {
                str += "<b><br><a class='bg-secondary text-white' href='javascript:void(0)'>[Não existe mensagem]</a></b>";
            }


            str += `
            </div>
            </div>
            </div>
            `;


        }
        str += '</div>';
        $('#nav-reclamacoes').append(str);
    }








    /*
        since the map is redered when "hidden", it's necessary to 
        render it again when showed
        called when nav-geo-tab tab is clicked
    */
    function updateMap() {
		// for all others (tabs are inside a modal)
		// this check must come before mymap, since they also use it for their main map
        if (typeof tabmap !== 'undefined')
          setTimeout(function(){ tabmap.invalidateSize(); }, 400);
        // for singleentity.php (tabs are in main view)
        else if (typeof mymap !== 'undefined')
          setTimeout(function(){ mymap.invalidateSize(); marker.addTo(mymap);}, 400);
        
      }




    // this prevent a '#XXX' being added to the url everytime
    // a tab is selected
    $('.nav-tabs').click(function(event){
          event.preventDefault();
    });

    // this prevent a '#XXX' being added to the url everytime
    // an accordion is selected
    function stop() {
        event.preventDefault();
    }
</script>
