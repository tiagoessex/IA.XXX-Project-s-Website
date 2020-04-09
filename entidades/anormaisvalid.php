<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>


<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/duplicados.css"/>

<div class="container-fluid">

    <br>

    <h1 class="text-center">
        Validação Manual de Entidades Anómalas
    </h1>

    <br><br>

    <div class="row col-sm-12">
        <table style="width:100%" class="table" id="entsdata">
        </table>
    </div>

    <br><br>

    <div class="row text-center">
        <div class="col-sm-4"></div>
        <div class="col-sm-1">
            <button onclick="valid();" class='button button-valid' value="Valido">Válido</button>
        </div>
        <div class="col-sm-1">
            <button onclick="invalid();" class='button button-invalid' value="Invalido">Inválido</button>
        </div>
        <div class="col-sm-1">
            <button onclick="next();" class='button button-next' value="Next">Seguinte</button>
        </div>
    </div>

    <br><br><br>

    <h2>Progresso: <span style="color:blue;" class='total'></span></h2>
    <h2>Válidas: <span style="color:green;" class='total_valid'></span></h2>
    <h2>Inválidas: <span style="color:red;" class='total_invalid'></span></h2>
    <h2>Ignoradas: <span style="color:orange;" class='total_ignored'></span></h2>
    <h2>Erro: <span style="background-color: yellow;" class='error'></span></h2>

    <!-- MESSAGE MODAL -->
    <div class="modal" id="modalMessage">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Atenção!!!</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body modal-body-modalMessage">
                    error
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
                </div>

            </div>
        </div>
    </div>
    <!-- END OF MESSAGE MODAL -->

    <!-- HELP MODAL -->
    <div class="modal" id="modal-help">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Ajuda</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body modal-body-help">
                    <p>
                        Nesta página poderá proceder à validação manual das entidades identificadas como anómalas.
                    </p>
                    <p>
                        As potenciais entidades anómalas são apresentadas aos pares, cabendo ao utilizador decidir se são mesmo anómalas.
                    </p>
                    <p>
                        Existem 3 hipóteses:
                        <ul>
                            <li><i><mark style="background-color: #99FF00;border-radius: 5px; padding: 2px;color:black;">Válido</mark></i> - se anómala</li>
                            <li><i><mark style="background-color: #FF3300;border-radius: 5px; padding: 2px;color:black;">Inválido</mark></i> - se não anómala</li>
                            <li><i><mark style="background-color: #FF9900;border-radius: 5px; padding: 2px;color:black;">Ignorar</mark></i> - desconhecido</li>
                        </ul>
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

var id_unique = null;
var id_duplicada = null;

$( document ).ready(function() {
    getEnt2Validate();
});

function valid() {
  validate(1);
}


function invalid() {
  validate(0);
}

function next() {
  validate(2);
}


  function getEnt2Validate() {
      $('.table').html('');
      $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/getanormal2validate.php",  
                    method:"POST",
                    cache: false,
                    dataType:"text", 
                   success:function(response) {
                      id_unique = null;
                      id_duplicada = null;
                      populate(response);
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    }
      });
  };

  function validate(isvalid) {
        $.ajax({  
                   url:"<?php echo DOMAIN_URL; ?>entidades/srv/validadanormal.php",  
                    method:"POST",
                    data:{isvalid:isvalid, id_duplicada:id_duplicada, id_unique:id_unique},
                    cache: false,
                    dataType:"text", 
                   success:function(response) {
                      getEnt2Validate()  
                   },
                   error: function( jqXHR, status ) {
                        errorsCommon(jqXHR, status);
                    }
        });
    };



    function populate(data) {
      if (data.length == 0) {
          var str = '<br><h3 class="text-center">Todas entidades duplicadas já se encontram validadas!</h3><br>'
          $('.modal-body-modalMessage').html(str);
          $('#modalMessage').modal('show');
          return;
      }

      data = JSON.parse(data)

      id_unique = data[0]['id_unique'];
      id_duplicada = data[0]['id_duplicada'];

      $('.total').text(data[0]['sofar'] + " / " + data[0]['total']);
      $('.total_valid').text(data[0]['total_valid']);
      $('.total_invalid').text(data[0]['total_invalid']);
      $('.total_ignored').text(data[0]['total_ignored']);
      $('.error').text(data[0]['total_invalid'] / data[0]['total'] * 100 + " %");


      tab = "<tr>";
      tab += "<th> id </th>";
      tab += "<th> name </th>";
      tab += "<th> address </th>";
      tab += "<th> localidade </th>";
      tab += "<th> nif </th>";
      tab += "</tr>";

      tab += "<tr>";
      tab += "<td>" + data[0]['id_unique'] + "</td>";
      tab += "<td>" + data[0]['nome_unique'] + "</td>";
      tab += "<td>" + data[0]['morada_unique'] + "</td>";
      tab += "<td>" + data[0]['localidade_unique'] + "</td>";
      tab += "<td>" + data[0]['nif_unique'] + "</td>";
      tab += "</tr>";
    
      tab += "<tr>";
      tab += "<td>" + data[0]['id_duplicada'] + "</td>";
      tab += "<td>" + data[0]['nome_duplicada'] + "</td>";
      tab += "<td>" + data[0]['morada_duplicada'] + "</td>";
      tab += "<td>" + data[0]['localidade_duplicada'] + "</td>";
      tab += "<td>" + data[0]['nif_duplicada'] + "</td>";
      tab += "</tr>";

      $('.table').append(tab);
    }


</script>

<?php include('../footer.php'); ?>