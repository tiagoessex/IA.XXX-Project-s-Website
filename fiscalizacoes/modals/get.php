<div class="modal fade" id="consultarFiscalizacaoModal" tabindex="-1" role="dialog" aria-labelledby="consultarFiscalizacaoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="consultarFiscalizacaoModalLabel">Consultar Fiscalizações</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input id="consultar-datepicker" />
        </div>
        <div class="modal-footer">
          <button id="consultar-btn" class="btn btn-primary">Consultar</button>
          <button class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        var d = new Date();
        $('#consultar-datepicker').datepicker({
          uiLibrary: 'bootstrap4',
          format: 'yyyy-mm-dd',
          value: d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate()
        });

        $('#consultar-btn').click(function() {
          window.location.href = '<?php echo DOMAIN_URL; ?>fiscalizacoes/consultar_fiscalizacoes.php?date=' + $('#consultar-datepicker').val();
        });

      });
    </script>
  </div>