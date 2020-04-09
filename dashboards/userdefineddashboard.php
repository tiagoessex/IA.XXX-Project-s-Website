<?php include('../isUser.php'); ?>
<?php include('../header.php'); ?>
<?php require('../common/_errors.php'); ?>



<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/overlayspinner.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/dashboards.css"/>
<link rel="stylesheet" href="<?php echo DOMAIN_URL; ?>css/datatables.css"/>
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/colreorder/1.5.1/css/colReorder.dataTables.min.css" rel="stylesheet">


<div class="container-fluid">    


    <div class="row"> 
        <div class="col-sm-4" style="margin-left: 15px; background-color: rgb(106, 90, 205);border-radius: 10px; padding: 5px;color:white;"> 
            De: <input type="date" id="datepicker1" min="<?php echo MIN_DATE; ?>" max="<?php echo MAX_DATE; ?>" value="<?php echo MIN_DATE; ?>">
            Até: <input type="date" id="datepicker2" min="<?php echo MIN_DATE; ?>" max="<?php echo MAX_DATE; ?>" value="<?php echo MAX_DATE; ?>">   
        </div>
        <button type="button" class="btn btn-danger" id="recalc" style="margin-left: 15px; border-radius: 10px; padding: 5px;color:white;" onclick="Calc();"><i class='fas fa-redo-alt' style='font-size:18px'></i> Actualizar</button>

        <button type="button" class="btn btn-success" id="report" style="margin-left: 15px; border-radius: 10px; padding: 5px;color:white;" disabled><i class='fas fa-file-alt' style='font-size:18px'></i> Gerar Relatório</button>

    </div>

  <br>


        <div class="text-primary text-center mt-2">
            <img src="../images/inprogress.png" alt="inprogress" style="width:256px;height:256px;"> 
      </div>
  

    <!-- HELP MODAL -->
    <div class="modal" id="modal-help">
      <div class="modal-dialog">
        <div class="modal-content">
        
          <div class="modal-header">
            <h4 class="modal-title">Ajuda</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          
          <div class="modal-body modal-body-help text-justify">
               
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
          </div>
          
        </div>
      </div>
    </div>
    <!-- END OF HELP MODAL -->


</div>


<script src="<?php echo DOMAIN_URL; ?>js/utils.js"></script>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/colreorder/1.5.1/js/dataTables.colReorder.min.js"></script>

<script>

    var ano_start = null;
    var mes_start = null;
    var ano_end = null;
    var mes_end = null;


    $( document ).ready(function() {
        //$('#datepicker2').val(new Date(Date.now()).toISOString().split('T')[0]);
       // console.log($('#datepicker2').val());
       Calc();

       
    });

    function Calc() {

        var start = $('#datepicker1').val().split('-');
        ano_start = start[0];
        mes_start = start[1];
        var end = $('#datepicker2').val().split('-');
        ano_end = end[0];
        mes_end = end[1];
    }



// ******************************************
// 
// ******************************************

</script>


<?php include('../footer.php'); ?>
