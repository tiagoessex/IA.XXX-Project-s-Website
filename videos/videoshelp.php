<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>

<style>
    .video-title {
                background-color: #CCFFFF;
                border-radius: 10px;
                padding: 5px;
                color: black;
                text-align: center;
            }
</style>

<div class="container-fluid">

    <br>

    <nav>
        <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-Entidades-tab" data-toggle="tab" href="#nav-Entidades" role="tab" aria-controls="nav-Entidades" aria-selected="true">Entidades</a>
            <a class="nav-item nav-link" id="nav-Densidades-tab" data-toggle="tab" href="#nav-Densidades" role="tab" aria-controls="nav-Densidades" aria-selected="false">Densidades</a>
            <a class="nav-item nav-link" id="nav-Fiscalizacoes-tab" data-toggle="tab" href="#nav-Fiscalizacoes" role="tab" aria-controls="nav-Fiscalizacoes" aria-selected="false">Fiscalizações</a>
            <a class="nav-item nav-link" id="nav-Denuncias-tab" data-toggle="tab" href="#nav-Denuncias" role="tab" aria-controls="nav-Denuncias" aria-selected="false">Denúncias</a>
        </div>
    </nav>

    <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-Entidades" role="tabpanel" aria-labelledby="nav-Entidades-tab">

            <div class="row">
                <div class="col-sm-6">
                    <h5 class="video-title">Procurar por Nome/Id</h5>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video controls><source src="<?php echo DOMAIN_URL; ?>videos/videos/ent_proc_nome_id.webm" type="video/webm"></video>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h5 class="video-title">Procurar por Actividade</h5>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video controls><source src="<?php echo DOMAIN_URL; ?>videos/videos/ent_actividade.webm" type="video/webm"></video>
                    </div>
                </div>
            </div>

            <br>
            <br>

            <div class="row">
                <div class="col-sm-6">
                    <h5 class="video-title">Procurar por Região</h5>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video controls><source src="<?php echo DOMAIN_URL; ?>videos/videos/ent_regiao.webm" type="video/webm"></video>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h5 class="video-title">Procurar no Raio de ...</h5>
                     <div class="embed-responsive embed-responsive-16by9">
                        <video controls><source src="<?php echo DOMAIN_URL; ?>videos/videos/ent_raio.webm" type="video/webm"></video>
                    </div>    
                </div>
            </div>

            <br>
            <br>

            <div class="row">
                <div class="col-sm-6">
                    <h5 class="video-title">Nova Entidade</h5>
                     <div class="embed-responsive embed-responsive-16by9">
                        <video controls><source src="<?php echo DOMAIN_URL; ?>videos/videos/ent_nova_ent.webm" type="video/webm"></video>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h5 class="video-title">Procurar no Raio de ... (Google)</h5>
                     <div class="embed-responsive embed-responsive-16by9">
                        <video controls><source src="<?php echo DOMAIN_URL; ?>videos/videos/ent_raio_google.webm" type="video/webm"></video>
                    </div>
                </div>
            </div>

        </div>

        <div class="tab-pane fade show" id="nav-Densidades" role="tabpanel" aria-labelledby="nav-Densidades-tab">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="video-title">Densidades</h5>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video controls><source src="<?php echo DOMAIN_URL; ?>videos/videos/densidades.webm" type="video/webm"></video>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade show" id="nav-Fiscalizacoes" role="tabpanel" aria-labelledby="nav-Fiscalizacoes-tab">
            <div class="text-primary text-center mt-2">
                <img src="../images/inprogress.png" alt="inprogress" style="width:256px;height:256px;">
            </div>
        </div>

        <div class="tab-pane fade show" id="nav-Denuncias" role="tabpanel" aria-labelledby="nav-Denuncias-tab">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="video-title">Analisar uma Denúncia</h5>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video controls><source src="<?php echo DOMAIN_URL; ?>videos/videos/denuncias_single.webm" type="video/webm"></video>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h5 class="video-title">Analisar Denúncias</h5>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video controls><source src="<?php echo DOMAIN_URL; ?>videos/videos/denuncias_analisar.webm" type="video/webm"></video>
                    </div>
                </div>
            </div>

            <br>
            <br>

            <div class="row">
                <div class="col-sm-6">
                    <h5 class="video-title">Consultar e Alterar</h5>
                    <div class="embed-responsive embed-responsive-16by9">
                        <video controls><source src="<?php echo DOMAIN_URL; ?>videos/videos/denuncias_consultar_alterar.webm" type="video/webm"></video>
                    </div>
                </div>
                <div class="col-sm-6">
                    <h5 class="video-title">Real vs Previsto</h5>
                     <div class="embed-responsive embed-responsive-16by9">
                        <video controls><source src="<?php echo DOMAIN_URL; ?>videos/videos/denuncias_real_vs_previsto.webm" type="video/webm"></video>
                    </div>    
                </div>
            </div>
        </div>

        

    </div>

    <br>

    <!-- HELP MODAL -->
    <div class="modal" id="modal-help">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Ajuda</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body modal-body-help text-justify">
                    <p>
                        Ajuda para esta página? Não me parece.
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

    // this prevent a '#XXX' being added to the url everytime
    // a tab is selected
    $('.nav-tabs').click(function(event){
          event.preventDefault();
    });

</script>

<?php include('../footer.php'); ?>