<!--
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
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/toggleswitch.css"/>


<!-- DENUNCIA/RECLAMACAO MESSAGE MODAL -->
<div class="modal" id="modal_message">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title modal-title-message">Mensagem</h4>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <!--
                <button type="button" class="btn btn-primary mr-auto" onclick="analizeMsg();">Analisar <i class="fas fa-sync-alt analyzer_spinner"></i></button>
                -->
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-content-message">
                <div class="modal-body modal-body-message">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe id="iframe" class="embed-responsive-item" allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="mr-auto" style="margin-top: -20px;zoom: 0.8;-moz-transform: scale(0.8);">
                    Class. 1
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Class. 1 => 1 actividade (~5 segundos). Class. 2 => 3 actividades (~5 segundos).">
                        <label class="switchA">
                            <input type="checkbox" id="model">
                            <span class="sliderA"></span>
                        </label>
                    </a>
                    Class. 2
                </div>
                <!-- analizeMsg() is in _modal_analysis.php -->
                <button type="button" class="btn btn-primary" onclick="analizeMsg();">Analisar <i class="fas fa-sync-alt analyzer_spinner"></i></button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
            </div>

        </div>
    </div>
</div>
<!-- END OF DENUNCIA/RECLAMACAO MESSAGE MODAL -->

<script type="text/javascript">


    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip({trigger : 'hover'});
    });


    // called in _entity_data.php when a complaint has text and the user
    // clicks on "[Ver mensagem]" href
   function getMessage(id_denuncia, den_or_rec) {
      // saves the message id or denuncia id in a global var, in case the user choses to analyze the message
      g_id_denuncia = id_denuncia;
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/messagecontent.php",  
                    method:"POST",
                    data:{id_correspondencia: id_denuncia, den_or_rec: den_or_rec},
                    cache: false,
                    dataType:"text", 
                    contentType: "application/x-www-form-urlencoded;charset=ISO-8859-1",
                   success:function(response) {
                       showMessage(response); 
                   },
                   error: function( jqXHR, status ) {
                        c(jqXHR, status);
                    }
        });
    };


    
    function showMessage(msg) {
        var doc = document.getElementById("iframe").contentWindow.document;
        doc.open();
        if (msg.indexOf('<html') !== -1)
           doc.write(msg);
        else
            doc.write('<pre>' + msg + '</pre>');        
        doc.close();
        $('#modal_message').modal('show');
    }


</script>
